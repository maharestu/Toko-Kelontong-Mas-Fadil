# Rancangan Skema Database (ERD) — Toko Kelontong Mas Adil (Dengan Piutang & Kasbon)

Dokumen ini mendefinisikan skema basis data, spesifikasi kolom, tipe data, serta relasi antar tabel berdasarkan ERD **Toko Kelontong Mas Adil (Dengan Piutang & Kasbon)**.

---

## 1. Daftar Tabel & Urutan Pembuatan

1. `users` (penyesuaian kolom `role`)
2. `categories`
3. `products` (termasuk kolom `image`)
4. `customers` (pelanggan tetap / data kasbon)
5. `sales` (termasuk `customer_id`, `payment_type`, `due_date`)
6. `sale_details`
7. `deliveries`
8. `payments` (riwayat angsuran/pembayaran kasbon & transaksi)
9. `restocks`
10. `restock_details`
11. `stock_movements` (polymorphic morphs ke `sales`, `restocks`, dll)

---

## 2. Rincian Skema Tabel

### 1. `users`
Tabel pengguna, kasir, admin, atau kurir.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `role` | VARCHAR(50) / ENUM | Role pengguna (`admin`, `kasir`, `kurir`) |
| `name` | VARCHAR(255) | Nama pengguna |
| `email` | VARCHAR(255) | Unique |
| `email_verified_at` | TIMESTAMP | Nullable |
| `password` | VARCHAR(255) | Hash password |
| `remember_token` | VARCHAR(100) | Nullable |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 2. `categories`
Kategori produk (Sembako, Minuman, Makanan Ringan, Sabun, dll).

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `name` | VARCHAR(255) | Nama kategori |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 3. `products`
Daftar barang dagangan di toko.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `category_id` | BIGINT UNSIGNED | Foreign Key -> `categories.id` (`restrictOnDelete`) |
| `name` | VARCHAR(255) | Nama barang |
| `unit` | VARCHAR(50) | Satuan barang (pcs, kg, renteng, dus, pack) |
| `stock` | INT | Jumlah stok saat ini (default: 0) |
| `current_purchase_price` | DECIMAL(15, 2) | Harga beli / modal terakhir |
| `current_selling_price` | DECIMAL(15, 2) | Harga jual saat ini |
| `is_active` | BOOLEAN | Status aktif barang (default: true) |
| `image` | VARCHAR(255) | Nullable, path gambar produk |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 4. `customers`
Data pelanggan tetap / kasbon.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `name` | VARCHAR(255) | Nama pelanggan |
| `phone` | VARCHAR(50) | Nullable, kontak / no. WhatsApp |
| `address` | TEXT | Nullable, alamat pelanggan |
| `notes` | TEXT | Nullable, catatan (limit kasbon, dll) |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 5. `sales`
Transaksi kasir / penjualan barang.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `user_id` | BIGINT UNSIGNED | Foreign Key -> `users.id` (Kasir, `restrictOnDelete`) |
| `customer_id` | BIGINT UNSIGNED | Nullable, Foreign Key -> `customers.id` (`restrictOnDelete`) |
| `customer_name` | VARCHAR(255) | Nullable (nama pembeli walk-in) |
| `order_type` | VARCHAR(50) | Jenis pesanan (`direct`, `delivery`) |
| `total_amount` | DECIMAL(15, 2) | Total harga belanja |
| `payment_type` | VARCHAR(50) | Status/cara pembayaran (e.g. `cash`, `credit` / kasbon) |
| `due_date` | DATE | Nullable, tanggal jatuh tempo kasbon |
| `created_at` | TIMESTAMP | Waktu transaksi dibuat |
| `updated_at` | TIMESTAMP | Nullable |

---

### 6. `sale_details`
Rincian item barang yang dibeli pada transaksi penjualan.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `sale_id` | BIGINT UNSIGNED | Foreign Key -> `sales.id` (`cascadeOnDelete`) |
| `product_id` | BIGINT UNSIGNED | Foreign Key -> `products.id` (`restrictOnDelete`) |
| `quantity` | INT | Kuantitas barang |
| `selling_price_snapshot` | DECIMAL(15, 2) | Snapshot harga jual per unit saat transaksi |
| `cost_price_snapshot` | DECIMAL(15, 2) | Snapshot harga modal per unit saat transaksi |
| `subtotal` | DECIMAL(15, 2) | Subtotal (`quantity * selling_price_snapshot`) |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 7. `deliveries`
Informasi pengiriman kurir (Relasi 1:1 dengan `sales`).

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `sale_id` | BIGINT UNSIGNED | Foreign Key -> `sales.id` (UNIQUE, `cascadeOnDelete`) |
| `delivery_address` | TEXT | Alamat tujuan pengantaran |
| `scheduled_at` | TIMESTAMP | Nullable, jadwal kirim |
| `status` | ENUM / VARCHAR(50) | Status (`pending`, `delivering`, `completed`, `cancelled`) |
| `started_at` | TIMESTAMP | Nullable, waktu kurir jalan |
| `completed_at` | TIMESTAMP | Nullable, waktu pengantaran selesai |
| `courier_id` | BIGINT UNSIGNED | Nullable, Foreign Key -> `users.id` (`nullOnDelete`) |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 8. `payments`
Histori pembayaran/cicilan kasbon atau pembayaran kasir.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `sale_id` | BIGINT UNSIGNED | Foreign Key -> `sales.id` (`restrictOnDelete`) |
| `user_id` | BIGINT UNSIGNED | Foreign Key -> `users.id` (Kasir penerima bayar, `restrictOnDelete`) |
| `amount` | DECIMAL(15, 2) | Nominal yang dibayar |
| `payment_method` | VARCHAR(50) | Metode (`cash`, `transfer`, `qris`, dll) |
| `paid_at` | TIMESTAMP | Waktu pembayaran dilakukan |
| `notes` | TEXT | Nullable, catatan nota / transfer |
| `created_at` | TIMESTAMP | Nullable |

---

### 9. `restocks`
Transaksi kulakan produk.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `user_id` | BIGINT UNSIGNED | Foreign Key -> `users.id` (Admin/staff kulakan, `restrictOnDelete`) |
| `restock_date` | DATE | Tanggal barang masuk |
| `notes` | TEXT | Nullable, catatan |
| `total_cost` | DECIMAL(15, 2) | Total modal belanja kulakan |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 10. `restock_details`
Rincian item kulakan.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `restock_id` | BIGINT UNSIGNED | Foreign Key -> `restocks.id` (`cascadeOnDelete`) |
| `product_id` | BIGINT UNSIGNED | Foreign Key -> `products.id` (`restrictOnDelete`) |
| `quantity` | INT | Kuantitas masuk |
| `purchase_price` | DECIMAL(15, 2) | Harga beli modal satuan |
| `selling_price` | DECIMAL(15, 2) | Harga jual baru |
| `subtotal` | DECIMAL(15, 2) | Subtotal modal |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 11. `stock_movements`
Kartu stok dan mutasi barang secara menyeluruh (*audit trail*).

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `product_id` | BIGINT UNSIGNED | Foreign Key -> `products.id` (`restrictOnDelete`) |
| `user_id` | BIGINT UNSIGNED | Foreign Key -> `users.id` (`restrictOnDelete`) |
| `movement_type` | VARCHAR(50) | Tipe pergerakan (`sale`, `restock`, `adjustment`, `loss`, `return`) |
| `quantity_change` | INT | Perubahan kuantitas (+/-) |
| `reference_type` | VARCHAR(255) | Polymorphic model sumber |
| `reference_id` | BIGINT UNSIGNED | Nullable, ID sumber |
| `created_at` | TIMESTAMP | Waktu pencatatan mutasi |
| `updated_at` | TIMESTAMP | Nullable |
