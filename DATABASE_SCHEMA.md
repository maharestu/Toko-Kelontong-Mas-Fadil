# Rancangan Skema Database (ERD) — Toko Kelontong Mas Adil

Dokumen ini mendefinisikan skema basis data, spesifikasi kolom, tipe data, serta relasi antar tabel berdasarkan ERD **Toko Kelontong Mas Adil**.

---

## 1. Daftar Tabel & Urutan Migrasi

Untuk menjaga integritas referensial foreign key, urutan pembuatan migrasi adalah:
1. `users` (penyesuaian kolom `role`)
2. `categories`
3. `products`
4. `sales`
5. `sale_details`
6. `deliveries`
7. `restocks`
8. `restock_details`
9. `stock_movements`

---

## 2. Rincian Skema Tabel

### 1. `users`
Tabel akun pengguna, kasir, admin, atau kurir.
*Note: Menyesuaikan tabel default Laravel dengan menambahkan kolom `role`.*

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `role` | VARCHAR(50) / ENUM | Role pengguna (e.g. `admin`, `kasir`, `kurir`) |
| `name` | VARCHAR(255) | Nama pengguna |
| `email` | VARCHAR(255) | Unique |
| `email_verified_at` | TIMESTAMP | Nullable |
| `password` | VARCHAR(255) | Hash password |
| `remember_token` | VARCHAR(100) | Nullable |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 2. `categories`
Kategori produk (misal: Sembako, Minuman, Makanan Ringan, Sabun & Kebersihan, dll).

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
| `category_id` | BIGINT UNSIGNED | Foreign Key -> `categories.id` (`cascadeOnDelete` / `restrictOnDelete`) |
| `name` | VARCHAR(255) | Nama barang |
| `unit` | VARCHAR(50) | Satuan barang (pcs, kg, renteng, dus, pack, dll) |
| `stock` | INT | Jumlah stok saat ini (default: 0) |
| `current_purchase_price` | DECIMAL(15, 2) | Harga beli / modal terakhir |
| `current_selling_price` | DECIMAL(15, 2) | Harga jual saat ini |
| `is_active` | BOOLEAN | Status aktif barang (default: true) |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 4. `sales`
Transaksi penjualan / kasir.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `user_id` | BIGINT UNSIGNED | Foreign Key -> `users.id` (Kasir / user pencatat) |
| `customer_name` | VARCHAR(255) | Nullable (Bisa kosong jika pembeli umum/walk-in) |
| `order_type` | VARCHAR(50) | Jenis pesanan (misal: `dine-in` / `takeaway` / `delivery` / `offline`) |
| `total_amount` | DECIMAL(15, 2) | Total harga belanja |
| `created_at` | TIMESTAMP | Waktu transaksi dibuat |
| `updated_at` | TIMESTAMP | Nullable |

---

### 5. `sale_details`
Rincian item produk yang terjual dalam transaksi penjualan.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `sale_id` | BIGINT UNSIGNED | Foreign Key -> `sales.id` (`cascadeOnDelete`) |
| `product_id` | BIGINT UNSIGNED | Foreign Key -> `products.id` (`restrictOnDelete`) |
| `quantity` | INT | Jumlah barang yang dibeli |
| `selling_price_snapshot` | DECIMAL(15, 2) | Snapshot harga jual per unit saat transaksi |
| `cost_price_snapshot` | DECIMAL(15, 2) | Snapshot harga modal per unit saat transaksi |
| `subtotal` | DECIMAL(15, 2) | Subtotal harga (`quantity * selling_price_snapshot`) |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 6. `deliveries`
Informasi pengantaran pesanan penjualan (Relasi 1:1 dengan `sales`).

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `sale_id` | BIGINT UNSIGNED | Foreign Key -> `sales.id` (UNIQUE, `cascadeOnDelete`) |
| `delivery_address` | TEXT | Alamat pengiriman |
| `scheduled_at` | TIMESTAMP | Nullable / Jadwal pengantaran |
| `status` | VARCHAR(50) | Status pengantaran (misal: `pending`, `delivering`, `completed`, `cancelled`) |
| `started_at` | TIMESTAMP | Nullable, waktu kurir berangkat |
| `completed_at` | TIMESTAMP | Nullable, waktu barang tiba/selesai |
| `courier_id` | BIGINT UNSIGNED | Foreign Key -> `users.id`, Nullable (`nullOnDelete`) |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 7. `restocks`
Transaksi kulakan / restock produk dari supplier.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `user_id` | BIGINT UNSIGNED | Foreign Key -> `users.id` (Admin/staff yang mencatat) |
| `restock_date` | DATE / TIMESTAMP | Tanggal barang masuk / restock |
| `notes` | TEXT | Nullable, catatan nota/supplier |
| `total_cost` | DECIMAL(15, 2) | Total biaya kulakan |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 8. `restock_details`
Rincian item barang yang dikulak/di-restock.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `restock_id` | BIGINT UNSIGNED | Foreign Key -> `restocks.id` (`cascadeOnDelete`) |
| `product_id` | BIGINT UNSIGNED | Foreign Key -> `products.id` (`restrictOnDelete`) |
| `quantity` | INT | Jumlah kuantitas barang masuk |
| `purchase_price` | DECIMAL(15, 2) | Harga beli modal satuan barang |
| `selling_price` | DECIMAL(15, 2) | Harga jual yang direncanakan / diperbarui |
| `subtotal` | DECIMAL(15, 2) | Subtotal biaya (`quantity * purchase_price`) |
| `created_at` | TIMESTAMP | Nullable |
| `updated_at` | TIMESTAMP | Nullable |

---

### 9. `stock_movements`
Kartu stok atau catatan riwayat mutasi keluar/masuk barang secara komprehensif.

| Kolom | Tipe Data | Atribut / Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `product_id` | BIGINT UNSIGNED | Foreign Key -> `products.id` (`cascadeOnDelete`) |
| `user_id` | BIGINT UNSIGNED | Foreign Key -> `users.id` (Pengguna yang memicu mutasi) |
| `movement_type` | VARCHAR(50) | Tipe pergerakan (e.g. `sale`, `restock`, `adjustment`, `loss`, `return`) |
| `quantity_change` | INT | Jumlah perubahan kuantitas (bisa positif `+` atau negatif `-`) |
| `reference_type` | VARCHAR(255) | Polymorphic / Nama model/tabel sumber (e.g. `Sale`, `Restock`, `Manual`) |
| `reference_id` | BIGINT UNSIGNED | Nullable, ID sumber transaksi referensi |
| `created_at` | TIMESTAMP | Nullable / default `now()` |
| `updated_at` | TIMESTAMP | Nullable |

---

## 3. Relasi Antar Tabel (Entity Relationships)

```
[CATEGORIES] 1 ----- <N> [PRODUCTS]
                           |       ^
                           |       |
                           |       +---------------------------------+
                           v                                         |
[USERS] 1 ----- <N> [SALES] 1 ----- <N> [SALE_DETAILS] >-------------+
   |   1               1                                             |
   |   |               |                                             |
   |   |               +------- (1:1) ------ [DELIVERIES] >-(courier)+
   |   |
   |   +------- <N> [RESTOCKS] 1 --- <N> [RESTOCK_DETAILS] >---------+
   |                       |                                         |
   |                       +-----------------------------------------+
   v                                                                 |
[USERS] 1 ------------------- <N> [STOCK_MOVEMENTS] >----------------+
```

