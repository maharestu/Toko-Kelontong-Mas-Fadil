<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan, laba, dan rekap pembayaran dari database.
     */
    public function index(Request $request): View
    {
        Carbon::setLocale('id');

        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();

        $yesterdayStart = $now->copy()->subDay()->startOfDay();
        $yesterdayEnd = $now->copy()->subDay()->endOfDay();

        $weekStart = $now->copy()->subDays(6)->startOfDay();
        $weekEnd = $todayEnd->copy();
        $prevWeekStart = $now->copy()->subDays(13)->startOfDay();
        $prevWeekEnd = $now->copy()->subDays(7)->endOfDay();

        $monthStart = $now->copy()->startOfMonth()->startOfDay();
        $monthEnd = $todayEnd->copy();
        $prevMonth = $now->copy()->subMonthNoOverflow();
        $prevMonthStart = $prevMonth->copy()->startOfMonth()->startOfDay();
        $prevMonthEnd = $prevMonth->copy()->endOfMonth()->endOfDay();

        $reportData = [
            'today' => $this->buildPeriodReport(
                periodKey: 'today',
                label: 'Hari Ini',
                fileSuffix: 'Hari_Ini',
                dateRange: $now->translatedFormat('l, d M Y'),
                start: $todayStart,
                end: $todayEnd,
                prevStart: $yesterdayStart,
                prevEnd: $yesterdayEnd,
            ),
            'week' => $this->buildPeriodReport(
                periodKey: 'week',
                label: 'Minggu Ini',
                fileSuffix: 'Minggu_Ini',
                dateRange: $weekStart->translatedFormat('d M').' – '.$now->translatedFormat('d M Y'),
                start: $weekStart,
                end: $weekEnd,
                prevStart: $prevWeekStart,
                prevEnd: $prevWeekEnd,
            ),
            'month' => $this->buildPeriodReport(
                periodKey: 'month',
                label: 'Bulan Ini',
                fileSuffix: 'Bulan_Ini',
                dateRange: $monthStart->translatedFormat('j M').' – '.$now->translatedFormat('j M Y'),
                start: $monthStart,
                end: $monthEnd,
                prevStart: $prevMonthStart,
                prevEnd: $prevMonthEnd,
            ),
        ];

        return view('laporan.index', [
            'reportData' => $reportData,
        ]);
    }

    /**
     * Bangun struktur laporan lengkap untuk satu periode waktu.
     *
     * @return array<string, mixed>
     */
    private function buildPeriodReport(
        string $periodKey,
        string $label,
        string $fileSuffix,
        string $dateRange,
        Carbon $start,
        Carbon $end,
        Carbon $prevStart,
        Carbon $prevEnd,
    ): array {
        $sales = Sale::with(['saleDetails.product', 'payments'])
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $omzet = (float) $sales->sum('total_amount');
        $laba = (float) $sales->sum(fn (Sale $sale): float => $this->calculateSaleProfit($sale));
        $trxCount = $sales->count();
        $avgTrx = $trxCount > 0 ? round($omzet / $trxCount) : 0.0;
        $marginPct = $omzet > 0 ? round(($laba / $omzet) * 100, 1) : 0.0;

        $prevTotals = $this->getPeriodTotals($prevStart, $prevEnd);

        $chart = $this->buildChartData($periodKey, $sales, $start, $end);
        $defaultBarIndex = $this->findPeakBarIndex($chart);

        return [
            'label' => $label,
            'fileSuffix' => $fileSuffix,
            'dateRange' => $dateRange,
            'omzet' => $omzet,
            'omzetTrend' => $this->formatTrend($omzet, $prevTotals['omzet']),
            'laba' => $laba,
            'labaTrend' => $this->formatTrend($laba, $prevTotals['laba']),
            'margin' => 'Margin '.number_format($marginPct, 1, ',', '.').'%',
            'trxCount' => $trxCount,
            'trxTrend' => $this->formatTrend((float) $trxCount, (float) $prevTotals['trxCount']),
            'avgTrx' => $avgTrx,
            'avgTrend' => $this->formatTrend($avgTrx, $prevTotals['avgTrx']),
            'chart' => $chart,
            'defaultBarIndex' => $defaultBarIndex,
            'products' => $this->buildTopProducts($sales),
            'transactions' => $this->buildTransactionsList($sales),
            'payments' => $this->buildPaymentsSummary($start, $end),
        ];
    }

    /**
     * Hitung total omzet, laba, jumlah transaksi, dan rata-rata untuk periode pembanding.
     *
     * @return array{omzet: float, laba: float, trxCount: int, avgTrx: float}
     */
    private function getPeriodTotals(Carbon $start, Carbon $end): array
    {
        $salesQuery = Sale::whereBetween('created_at', [$start, $end]);
        $omzet = (float) (clone $salesQuery)->sum('total_amount');
        $trxCount = (clone $salesQuery)->count();

        $laba = (float) SaleDetail::whereHas('sale', function ($query) use ($start, $end): void {
            $query->whereBetween('created_at', [$start, $end]);
        })->sum(DB::raw('(selling_price_snapshot - cost_price_snapshot) * quantity'));

        $avgTrx = $trxCount > 0 ? round($omzet / $trxCount) : 0.0;

        return [
            'omzet' => $omzet,
            'laba' => $laba,
            'trxCount' => $trxCount,
            'avgTrx' => $avgTrx,
        ];
    }

    private function calculateSaleProfit(Sale $sale): float
    {
        return (float) $sale->saleDetails->sum(function (SaleDetail $detail): float {
            return ((float) $detail->selling_price_snapshot - (float) $detail->cost_price_snapshot) * $detail->quantity;
        });
    }

    /**
     * Format persentase pertumbuhan terhadap periode pembanding.
     *
     * @return array{text: string, positive: bool}
     */
    private function formatTrend(float $current, float $previous): array
    {
        if ($previous > 0) {
            $pct = round((($current - $previous) / $previous) * 100, 1);
        } else {
            $pct = $current > 0 ? 100.0 : 0.0;
        }

        $prefix = $pct > 0 ? '+' : '';

        return [
            'text' => $prefix.number_format($pct, 1, ',', '.').'%',
            'positive' => $pct >= 0,
        ];
    }

    /**
     * Bangun data batang grafik sesuai periode.
     *
     * @param  Collection<int, Sale>  $sales
     * @return array<int, array{label: string, fullDate: string, omzet: float, laba: float}>
     */
    private function buildChartData(string $periodKey, Collection $sales, Carbon $start, Carbon $end): array
    {
        if ($sales->isEmpty()) {
            return [];
        }

        if ($periodKey === 'today') {
            $dateStr = $start->translatedFormat('l, d M');
            $slots = [
                ['label' => '<08', 'fullDate' => "{$dateStr} • 00:00–08:00", 'omzet' => 0.0, 'laba' => 0.0],
                ['label' => '08:00', 'fullDate' => "{$dateStr} • 08:00–10:00", 'omzet' => 0.0, 'laba' => 0.0],
                ['label' => '10:00', 'fullDate' => "{$dateStr} • 10:00–12:00", 'omzet' => 0.0, 'laba' => 0.0],
                ['label' => '12:00', 'fullDate' => "{$dateStr} • 12:00–14:00", 'omzet' => 0.0, 'laba' => 0.0],
                ['label' => '14:00', 'fullDate' => "{$dateStr} • 14:00–16:00", 'omzet' => 0.0, 'laba' => 0.0],
                ['label' => '16:00', 'fullDate' => "{$dateStr} • 16:00–18:00", 'omzet' => 0.0, 'laba' => 0.0],
                ['label' => '18:00', 'fullDate' => "{$dateStr} • 18:00–20:00", 'omzet' => 0.0, 'laba' => 0.0],
                ['label' => '20+', 'fullDate' => "{$dateStr} • 20:00–24:00", 'omzet' => 0.0, 'laba' => 0.0],
            ];

            foreach ($sales as $sale) {
                $hour = $sale->created_at->hour;
                $slotIdx = match (true) {
                    $hour < 8 => 0,
                    $hour >= 20 => 7,
                    default => intdiv($hour - 8, 2) + 1,
                };

                $slots[$slotIdx]['omzet'] += (float) $sale->total_amount;
                $slots[$slotIdx]['laba'] += $this->calculateSaleProfit($sale);
            }

            return $slots;
        }

        if ($periodKey === 'week') {
            $daysShort = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            $buckets = [];

            for ($i = 0; $i < 7; $i++) {
                $day = $start->copy()->addDays($i);
                $buckets[$day->toDateString()] = [
                    'label' => $daysShort[$day->dayOfWeek] ?? '',
                    'fullDate' => $day->translatedFormat('l, d M Y'),
                    'omzet' => 0.0,
                    'laba' => 0.0,
                ];
            }

            foreach ($sales as $sale) {
                $dateKey = $sale->created_at->toDateString();
                if (isset($buckets[$dateKey])) {
                    $buckets[$dateKey]['omzet'] += (float) $sale->total_amount;
                    $buckets[$dateKey]['laba'] += $this->calculateSaleProfit($sale);
                }
            }

            return array_values($buckets);
        }

        // Periode bulan: kelompokkan per minggu dalam bulan berjalan
        $daysInMonth = $start->daysInMonth;
        $weekRanges = [
            [1, 7],
            [8, 14],
            [15, 21],
            [22, 28],
        ];
        if ($daysInMonth >= 29) {
            $weekRanges[] = [29, $daysInMonth];
        }

        $buckets = [];
        foreach ($weekRanges as $idx => [$fromDay, $toDay]) {
            $rangeStart = $start->copy()->day($fromDay);
            $rangeEnd = $start->copy()->day(min($toDay, $end->day));
            $buckets[$idx] = [
                'label' => 'Mg '.($idx + 1),
                'fullDate' => $rangeStart->translatedFormat('d M').' – '.$rangeEnd->translatedFormat('d M Y'),
                'omzet' => 0.0,
                'laba' => 0.0,
            ];
        }

        foreach ($sales as $sale) {
            $dayOfMonth = $sale->created_at->day;
            $bucketIdx = min((int) floor(($dayOfMonth - 1) / 7), count($buckets) - 1);
            $buckets[$bucketIdx]['omzet'] += (float) $sale->total_amount;
            $buckets[$bucketIdx]['laba'] += $this->calculateSaleProfit($sale);
        }

        return array_values($buckets);
    }

    /**
     * Pilih indeks batang dengan nilai omzet tertinggi sebagai highlight awal.
     *
     * @param  array<int, array{omzet: float}>  $chart
     */
    private function findPeakBarIndex(array $chart): int
    {
        if (empty($chart)) {
            return 0;
        }

        $peakIndex = 0;
        $peakValue = -1.0;

        foreach ($chart as $index => $item) {
            if ($item['omzet'] > $peakValue) {
                $peakValue = $item['omzet'];
                $peakIndex = $index;
            }
        }

        return $peakIndex;
    }

    /**
     * Agregasi produk terlaris pada periode terkait (maksimal 10 item).
     *
     * @param  Collection<int, Sale>  $sales
     * @return array<int, array{name: string, unit: string, sold: int, revenue: float, profit: float}>
     */
    private function buildTopProducts(Collection $sales): array
    {
        return $sales
            ->flatMap(fn (Sale $sale): Collection => $sale->saleDetails)
            ->groupBy('product_id')
            ->map(function (Collection $details): array {
                $first = $details->first();

                return [
                    'name' => $first?->product?->name ?? 'Produk',
                    'unit' => $first?->product?->unit ?? 'pcs',
                    'sold' => (int) $details->sum('quantity'),
                    'revenue' => (float) $details->sum('subtotal'),
                    'profit' => (float) $details->sum(function (SaleDetail $d): float {
                        return ((float) $d->selling_price_snapshot - (float) $d->cost_price_snapshot) * $d->quantity;
                    }),
                ];
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * Susun daftar transaksi terbaru pada periode terkait (maksimal 20 nota).
     *
     * @param  Collection<int, Sale>  $sales
     * @return array<int, array{id: string, time: string, fullTime: string, method: string, customer: string, itemsCount: int, total: float}>
     */
    private function buildTransactionsList(Collection $sales): array
    {
        return $sales
            ->take(20)
            ->map(function (Sale $sale): array {
                return [
                    'id' => '#TRX-'.str_pad((string) $sale->id, 4, '0', STR_PAD_LEFT),
                    'time' => $sale->created_at->format('H:i'),
                    'fullTime' => $sale->created_at->translatedFormat('d M Y').' • '.$sale->created_at->format('H:i').' WIB',
                    'method' => $this->resolveSaleMethodLabel($sale),
                    'customer' => $sale->customer_name ?: 'Pelanggan Umum',
                    'itemsCount' => (int) $sale->saleDetails->sum('quantity'),
                    'total' => (float) $sale->total_amount,
                ];
            })
            ->values()
            ->all();
    }

    private function resolveSaleMethodLabel(Sale $sale): string
    {
        if ($sale->payment_type === PaymentType::Kredit) {
            $paidSum = (float) $sale->payments->sum('amount');
            if ($paidSum >= (float) $sale->total_amount && $paidSum > 0) {
                return 'Kasbon (Lunas)';
            }

            if ($paidSum > 0) {
                return 'Kasbon (Cicil)';
            }

            return 'Kasbon';
        }

        $firstPayment = $sale->payments->first();
        if (! $firstPayment) {
            return 'Tunai';
        }

        return match ($firstPayment->payment_method) {
            PaymentMethod::Qris => 'QRIS',
            PaymentMethod::Transfer => 'Transfer',
            default => 'Tunai',
        };
    }

    /**
     * Rekap pembayaran masuk berdasarkan metode pembayaran pada rentang waktu terkait.
     *
     * @return array<int, array{method: string, trx: int, amount: float, percent: int}>
     */
    private function buildPaymentsSummary(Carbon $start, Carbon $end): array
    {
        $payments = Payment::whereBetween('paid_at', [$start, $end])->get();

        if ($payments->isEmpty()) {
            return [];
        }

        $totalAmount = (float) $payments->sum('amount');

        $labels = [
            PaymentMethod::Cash->value => 'Tunai',
            PaymentMethod::Qris->value => 'QRIS',
            PaymentMethod::Transfer->value => 'Transfer',
        ];

        return $payments
            ->groupBy(function (Payment $payment): string {
                return $payment->payment_method instanceof PaymentMethod
                    ? $payment->payment_method->value
                    : (string) $payment->payment_method;
            })
            ->map(function (Collection $group, string $methodKey) use ($labels, $totalAmount): array {
                $amount = (float) $group->sum('amount');
                $percent = $totalAmount > 0 ? (int) round(($amount / $totalAmount) * 100) : 0;

                return [
                    'method' => $labels[$methodKey] ?? ucfirst($methodKey),
                    'trx' => $group->count(),
                    'amount' => $amount,
                    'percent' => $percent,
                ];
            })
            ->sortByDesc('amount')
            ->values()
            ->all();
    }
}
