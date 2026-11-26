<?php

namespace App\Http\Controllers;

use App\Enums\MovementType;
use App\Models\Product;
use App\Models\Restock;
use App\Models\RestockDetail;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RestockController extends Controller
{
    /**
     * Tampilkan riwayat pencatatan restock barang masuk.
     */
    public function index(Request $request): View
    {
        Carbon::setLocale('id');

        $restocks = Restock::with(['user', 'restockDetails.product'])
            ->orderBy('restock_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyQuery = Restock::whereMonth('restock_date', $currentMonth)
            ->whereYear('restock_date', $currentYear);

        $monthlyCount = $monthlyQuery->count();
        $monthlyTotal = (float) $monthlyQuery->sum('total_cost');

        return view('restocks.index', [
            'restocks' => $restocks,
            'monthlyCount' => $monthlyCount,
            'monthlyTotal' => $monthlyTotal,
        ]);
    }

    /**
     * Tampilkan halaman form tambah restock barang masuk.
     */
    public function create(): View
    {
        Carbon::setLocale('id');
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('restocks.create', [
            'products' => $products,
        ]);
    }

    /**
     * Tampilkan detail transaksi restock berdasarkan ID.
     */
    public function show(int $id): View
    {
        Carbon::setLocale('id');

        $restock = Restock::with(['user', 'restockDetails.product'])->findOrFail($id);

        $totalQty = $restock->restockDetails->sum('quantity');
        $details = $restock->restockDetails->map(fn ($d): array => [
            'name' => $d->product?->name ?? 'Produk',
            'unit' => $d->product?->unit ?? 'pcs',
            'qty' => $d->quantity,
            'buy' => (float) $d->purchase_price,
            'sell' => (float) $d->selling_price,
            'subtotal' => (float) $d->subtotal,
        ])->toArray();

        $datePart = Carbon::parse($restock->restock_date)->translatedFormat('d M Y');
        $timePart = $restock->created_at ? $restock->created_at->format('H:i').' WIB' : '';

        return view('restocks.show', [
            'code' => '#RST-'.Carbon::parse($restock->restock_date)->format('Y').'-'.str_pad((string) $restock->id, 3, '0', STR_PAD_LEFT),
            'time' => trim($datePart.($timePart !== '' ? ', '.$timePart : '')),
            'author' => $restock->user?->name ?? 'Mas Adil',
            'notes' => $restock->notes,
            'details' => $details,
            'total_cost' => (float) $restock->total_cost,
            'total_qty' => $totalQty,
        ]);
    }

    /**
     * Simpan transaksi restock baru, tambah stok, dan catat mutasi stok.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'restock_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.selling_price' => 'nullable|numeric|min:0',
        ]);

        $userId = Auth::id() ?? 1;

        DB::transaction(function () use ($validated, $userId): void {
            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalCost += ($item['quantity'] * $item['purchase_price']);
            }

            $restock = Restock::create([
                'user_id' => $userId,
                'restock_date' => $validated['restock_date'],
                'notes' => $validated['notes'] ?? 'Restock barang masuk toko',
                'total_cost' => $totalCost,
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $subtotal = $item['quantity'] * $item['purchase_price'];
                $sellPrice = isset($item['selling_price']) && $item['selling_price'] !== ''
                    ? $item['selling_price']
                    : $product->current_selling_price;

                RestockDetail::create([
                    'restock_id' => $restock->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'selling_price' => $sellPrice,
                    'subtotal' => $subtotal,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $userId,
                    'movement_type' => MovementType::Restock,
                    'quantity_change' => $item['quantity'],
                    'reference_type' => Restock::class,
                    'reference_id' => $restock->id,
                ]);

                $product->increment('stock', $item['quantity']);
                $product->update([
                    'current_purchase_price' => $item['purchase_price'],
                    'current_selling_price' => $sellPrice,
                ]);
            }
        });

        return redirect()->route('restocks.index')->with('success', 'Pencatatan restock barang masuk berhasil disimpan!');
    }
}
