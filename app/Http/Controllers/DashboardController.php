<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard ringkasan toko.
     */
    public function index(Request $request): View
    {
        Carbon::setLocale('id');
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. Data Penjualan Hari Ini
        $todaySales = Sale::whereDate('created_at', $today);
        $todayTotal = (float) $todaySales->sum('total_amount');
        $todayCount = $todaySales->count();
        $todayAvg = $todayCount > 0 ? $todayTotal / $todayCount : 0.0;

        // Penjualan Kemarin & Persentase Perubahan
        $yesterdayTotal = (float) Sale::whereDate('created_at', $yesterday)->sum('total_amount');
        if ($yesterdayTotal > 0) {
            $growthPercentage = round((($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100, 1);
        } else {
            $growthPercentage = $todayTotal > 0 ? 100.0 : 0.0;
        }

        // Estimasi Laba Kotor Hari Ini & Margin
        $todayProfit = (float) SaleDetail::whereHas('sale', function ($query) use ($today): void {
            $query->whereDate('created_at', $today);
        })->sum(DB::raw('(selling_price_snapshot - cost_price_snapshot) * quantity'));

        $effectiveMargin = $todayTotal > 0 ? round(($todayProfit / $todayTotal) * 100, 1) : 0.0;

        // 2. Tren Penjualan 7 Hari Terakhir (1 query agregasi di PHP agar lintas driver DB)
        $trendStart = $today->copy()->subDays(6)->startOfDay();
        $trendEnd = $today->copy()->endOfDay();

        $salesByDate = Sale::whereBetween('created_at', [$trendStart, $trendEnd])
            ->get(['total_amount', 'created_at'])
            ->groupBy(fn (Sale $sale): string => $sale->created_at->toDateString())
            ->map(fn ($group): float => (float) $group->sum('total_amount'));

        $sevenDaysTrend = [];
        $totalSevenDays = 0.0;
        $maxSales = 0.0;

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateKey = $date->toDateString();
            $dayTotal = (float) ($salesByDate->get($dateKey) ?? 0.0);
            $totalSevenDays += $dayTotal;

            if ($dayTotal > $maxSales) {
                $maxSales = $dayTotal;
            }

            $sevenDaysTrend[] = [
                'date' => $dateKey,
                'day_label' => $i === 0 ? 'Hari Ini' : $this->getIndonesianDayShort($date->dayOfWeek),
                'total' => $dayTotal,
                'short_amount' => $this->formatShortAmount($dayTotal),
                'is_today' => $i === 0,
            ];
        }

        foreach ($sevenDaysTrend as &$trend) {
            if ($maxSales > 0 && $trend['total'] > 0) {
                $heightPct = (int) round(($trend['total'] / $maxSales) * 100);
                $trend['height_pct'] = max(20, $heightPct);
            } else {
                $trend['height_pct'] = 12;
            }
        }
        unset($trend);

        $sevenDaysAvg = $totalSevenDays / 7;

        // 3. Stok Menipis (Stok <= 15) & Total Produk Kritis (Stok <= 5)
        $lowStockBaseQuery = Product::where('is_active', true)
            ->where('stock', '<=', 15);

        $criticalCount = (clone $lowStockBaseQuery)
            ->where('stock', '<=', 5)
            ->count();

        $lowStockProducts = $lowStockBaseQuery
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get()
            ->map(function (Product $product): array {
                $isUrgent = $product->stock <= 5;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'unit' => $product->unit,
                    'stock' => $product->stock,
                    'min_stock' => $isUrgent ? 10 : 20,
                    'status_label' => $isUrgent ? 'MENDESAK' : 'MENIPIS',
                    'is_urgent' => $isUrgent,
                ];
            });

        // 4. Informasi Shift & Tanggal
        $indonesianDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $indonesianMonths = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $now = Carbon::now();
        $currentDateFormatted = $indonesianDays[$now->dayOfWeek].', '.$now->day.' '.$indonesianMonths[$now->month].' '.$now->year;

        $hour = $now->hour;
        $shiftName = ($hour >= 6 && $hour < 15) ? 'Shift Pagi' : (($hour >= 15 && $hour < 22) ? 'Shift Sore' : 'Shift Malam');

        return view('dashboard', [
            'todayTotal' => $todayTotal,
            'todayCount' => $todayCount,
            'todayAvg' => $todayAvg,
            'yesterdayTotal' => $yesterdayTotal,
            'growthPercentage' => $growthPercentage,
            'todayProfit' => $todayProfit,
            'effectiveMargin' => $effectiveMargin,
            'sevenDaysTrend' => $sevenDaysTrend,
            'sevenDaysAvg' => $sevenDaysAvg,
            'lowStockProducts' => $lowStockProducts,
            'criticalCount' => $criticalCount,
            'currentDateFormatted' => $currentDateFormatted,
            'shiftName' => $shiftName,
        ]);
    }

    private function getIndonesianDayShort(int $dayOfWeek): string
    {
        $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        return $days[$dayOfWeek] ?? '';
    }

    private function formatShortAmount(float $amount): string
    {
        if ($amount >= 1000000) {
            return round($amount / 1000000, 1).'jt';
        }

        if ($amount >= 1000) {
            return round($amount / 1000, 0).'rb';
        }

        return (string) ((int) round($amount));
    }
}
