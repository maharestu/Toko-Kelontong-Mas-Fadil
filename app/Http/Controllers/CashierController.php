<?php

namespace App\Http\Controllers;

use App\Enums\MovementType;
use App\Enums\OrderType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierController extends Controller
{
    /**
     * Tampilkan halaman utama kasir (POS katalog produk & keranjang).
     */
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->whereRaw('is_active = true')
            ->orderBy('name', 'asc')
            ->get();

        $categories = Category::withCount(['products' => fn ($query) => $query->whereRaw('is_active = true')])
            ->orderBy('name', 'asc')
            ->get();

        $customers = Customer::orderBy('name', 'asc')->get();
        $totalActiveProducts = $products->count();

        return view('cashier.index', [
            'products' => $products,
            'categories' => $categories,
            'customers' => $customers,
            'totalActiveProducts' => $totalActiveProducts,
        ]);
    }

    /**
     * Proses transaksi penjualan kasir dan simpan ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_type' => 'nullable|string|in:umum,registered',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'order_type' => 'required|string|in:direct,delivery',
            'payment_type' => 'required|string|in:langsung,kredit',
            'payment_method' => 'nullable|string|in:cash,transfer,qris',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id() ?? 1;

        $saleData = DB::transaction(function () use ($validated, $userId) {
            $productIds = collect($validated['items'])->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $totalAmount = 0;
            $itemsDetail = [];

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    abort(422, 'Produk tidak ditemukan.');
                }

                if ($product->stock < $item['quantity']) {
                    abort(422, "Stok untuk {$product->name} tidak mencukupi (sisa {$product->stock}).");
                }

                $sellingPrice = (float) $product->current_selling_price;
                $costPrice = (float) $product->current_purchase_price;
                $subtotal = $sellingPrice * $item['quantity'];
                $totalAmount += $subtotal;

                $itemsDetail[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'selling_price' => $sellingPrice,
                    'cost_price' => $costPrice,
                    'subtotal' => $subtotal,
                ];
            }

            $customerName = 'Pelanggan Umum';
            $customerId = null;

            if (($validated['customer_type'] ?? 'umum') === 'registered' && ! empty($validated['customer_id'])) {
                $customer = Customer::find($validated['customer_id']);
                if ($customer) {
                    $customerId = $customer->id;
                    $customerName = $customer->name;
                }
            } elseif (! empty($validated['customer_name'])) {
                $customerName = trim($validated['customer_name']);
            }

            $orderTypeEnum = $validated['order_type'] === 'delivery' ? OrderType::Delivery : OrderType::Direct;
            $paymentTypeEnum = $validated['payment_type'] === 'kredit' ? PaymentType::Kredit : PaymentType::Langsung;

            $sale = Sale::create([
                'user_id' => $userId,
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'order_type' => $orderTypeEnum,
                'total_amount' => $totalAmount,
                'payment_type' => $paymentTypeEnum,
                'due_date' => $paymentTypeEnum === PaymentType::Kredit ? ($validated['due_date'] ?? null) : null,
            ]);

            foreach ($itemsDetail as $detail) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $detail['product']->id,
                    'quantity' => $detail['quantity'],
                    'selling_price_snapshot' => $detail['selling_price'],
                    'cost_price_snapshot' => $detail['cost_price'],
                    'subtotal' => $detail['subtotal'],
                ]);

                // Kurangi stok produk
                $detail['product']->decrement('stock', $detail['quantity']);

                // Catat mutasi stok
                StockMovement::create([
                    'product_id' => $detail['product']->id,
                    'user_id' => $userId,
                    'movement_type' => MovementType::Sale,
                    'quantity_change' => -$detail['quantity'],
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'notes' => "Penjualan Kasir Nota #{$sale->id}",
                ]);
            }

            // Catat Pembayaran jika Tunai/Langsung
            $paidAmount = (float) ($validated['paid_amount'] ?? $totalAmount);
            $change = max(0, $paidAmount - $totalAmount);

            if ($paymentTypeEnum === PaymentType::Langsung) {
                $paymentMethod = match ($validated['payment_method'] ?? 'cash') {
                    'transfer' => PaymentMethod::Transfer,
                    'qris' => PaymentMethod::Qris,
                    default => PaymentMethod::Cash,
                };

                Payment::create([
                    'sale_id' => $sale->id,
                    'user_id' => $userId,
                    'amount' => min($paidAmount, $totalAmount),
                    'payment_method' => $paymentMethod,
                    'paid_at' => now(),
                    'notes' => 'Pembayaran Kasir Langsung',
                ]);
            }

            return [
                'sale_id' => $sale->id,
                'customer_name' => $customerName,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change' => $change,
                'item_count' => count($itemsDetail),
                'total_qty' => collect($itemsDetail)->sum('quantity'),
            ];
        });

        return redirect()
            ->route('cashier.index')
            ->with('success', 'Transaksi kasir berhasil diselesaikan!')
            ->with('sale_receipt', $saleData);
    }
}
