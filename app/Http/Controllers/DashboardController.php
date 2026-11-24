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
    public function index(Request $request): View
    {
        Carbon::setLocale('id');
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. Data Penjualan Hari Ini
        $todaySales = Sale::whereDate('created_at', $today);
        $todayTotal = (float) $todaySales->sum('total_amount');
        $todayCount = $todaySales->count();
        $todayAvg = $todayCount > 0 ? $todayTotal / $todayCount : 0;

        // Penjualan Kemarin & Persentase Perubahan
        $yesterdayTotal = (float) Sale::whereDate('created_at', $yesterday)->sum('total_amount');
        if ($yesterdayTotal > 0) {
            $growthPercentage = round((($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100, 1);
        } else {
            $growthPercentage = $todayTotal > 0 ? 100.0 : 0.0;
        }

        // Estimasi Laba Kotor Hari Ini & Margin
        $todayProfit = (float) SaleDetail::whereHas('sale', function ($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->sum(DB::raw('(selling_price_snapshot - cost_price_snapshot) * quantity'));

        $effectiveMargin = $todayTotal > 0 ? round(($todayProfit / $todayTotal) * 100, 1) : 0;

        // 2. Tren Penjualan 7 Hari Terakhir
        $sevenDaysTrend = [];
        $totalSevenDays = 0;
        $maxSales = 1; // Menghindari pembagian dengan 0

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayTotal = (float) Sale::whereDate('created_at', $date)->sum('total_amount');
            $totalSevenDays += $dayTotal;

            if ($dayTotal > $maxSales) {
                $maxSales = $dayTotal;
            }

            $dayLabel = $i === 0 ? 'Hari Ini' : $this->getIndonesianDayShort($date->dayOfWeek);

            // Format ringkas (misal: 3.5jt atau 85rb)
            $shortAmount = $this->formatShortAmount($dayTotal);

            $sevenDaysTrend[] = [
                'date' => $date->toDateString(),
                'day_label' => $dayLabel,
                'total' => $dayTotal,
                'short_amount' => $shortAmount,
                'is_today' => $i === 0,
            ];
        }

        // Hitung persentase tinggi bar visual (min 15% agar selalu ada bar yang terlihat)
        foreach ($sevenDaysTrend as &$trend) {
            if ($maxSales > 0 && $trend['total'] > 0) {
                $heightPct = round(($trend['total'] / $maxSales) * 100);
                $trend['height_pct'] = max(20, $heightPct);
            } else {
                $trend['height_pct'] = 15;
            }
        }
        unset($trend);

        $sevenDaysAvg = $totalSevenDays / 7;

        // 3. Stok Menipis (Stok <= 15 atau batas minimum)
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock', '<=', 15)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get()
            ->map(function ($product) {
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

        $criticalCount = $lowStockProducts->where('is_urgent', true)->count();

        // 4. Informasi Shift & Tanggal
        $indonesianDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $indonesianMonths = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $currentDateFormatted = $indonesianDays[Carbon::now()->dayOfWeek] . ', ' . 
            Carbon::now()->day . ' ' . 
            $indonesianMonths[Carbon::now()->month] . ' ' . 
            Carbon::now()->year;

        $hour = Carbon::now()->hour;
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
            return round($amount / 1000000, 1) . 'M';
        }
        if ($amount >= 1000) {
            return round($amount / 1000, 0) . 'rb';
        }
        return (string) $amount;
    }
}
