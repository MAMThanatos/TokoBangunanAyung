# Simulasi Alur Transaksi Toko Bangunan Ayung
**Dokumen ini menjelaskan simulasi logika transaksi (Use Case Flow) tanpa koneksi database.**

Simulasi ini menggambarkan bagaimana sistem memproses data dari Pemasok, Barang, Pelanggan, hingga menjadi Laporan Keuangan.

---

## A. Data Awal (Persiapan Database)

### 1. Daftar Stok Barang (10 Item)
| ID | Nama Barang | Kategori | Harga Beli (Rp) | Harga Jual (Rp) | Stok Awal |
|----|-------------|----------|-----------------|-----------------|-----------|
| B01 | Semen Tiga Roda 50kg | Material | 50.000 | 60.000 | 100 |
| B02 | Cat Tembok Dulux 5kg | Cat | 120.000 | 145.000 | 20 |
| B03 | Pasir Beton (1 Pick up) | Material | 300.000 | 400.000 | 5 |
| B04 | Paku Payung 1kg | Perkakas | 15.000 | 20.000 | 50 |
| B05 | Besi Beton 8mm (12m) | Material | 35.000 | 45.000 | 200 |
| B06 | Keramik Lantai 40x40 | Lantai | 55.000 | 65.000 | 40 |
| B07 | Pipa PVC 3/4 inch | Pipa | 25.000 | 35.000 | 60 |
| B08 | Triplek 4mm | Kayu | 45.000 | 55.000 | 30 |
| B09 | Thinner A (Kaleng) | Cat | 20.000 | 30.000 | 25 |
| B10 | Kuas Cat 3 inch | Perkakas | 8.000 | 12.000 | 15 |

### 2. Daftar Pemasok (5 Supplier)
1.  **PT. Semen Indonesia** (Suplier Semen)
2.  **Toko Cat Warna Warni** (Suplier Cat, Thinner, Kuas)
3.  **TB. Besi & Baja** (Suplier Besi, Paku)
4.  **Distributor Keramik Mulia** (Suplier Keramik, Pasir)
5.  **Agen Pipa Jaya** (Suplier Pipa, Sambungan)

### 3. Daftar Pelanggan (5 Customer)
1.  **Budi Santoso** (Pelanggan Umum)
2.  **CV. Konstruksi Jaya** (Member/Kontraktor)
3.  **Pak RT 05** (Pelanggan Umum)
4.  **Ibu Siti** (Member)
5.  **Proyek Perumahan Griya** (Kontraktor Besar)

---

## B. Skenario Transaksi Harian

### Skenario 1: Penjualan Eceran (Kasir)
**Pelanggan**: Budi Santoso
**Aksi**: Membeli bahan untuk renovasi kecil.
1.  Kasir input: **2 Sak Semen** (2 x 60.000 = 120.000)
2.  Kasir input: **1 Kg Paku** (1 x 20.000 = 20.000)
3.  Kasir input: **1 Kuas Cat** (1 x 12.000 = 12.000)
4.  **Total Belanja**: Rp 152.000
5.  **Pembayaran**: Tunai Rp 200.000 -> **Kembalian**: Rp 48.000
**Efek Sistem**:
- Stok Semen: 100 -> 98
- Stok Paku: 50 -> 49
- Stok Kuas: 15 -> 14
- Kas Masuk: +Rp 152.000

### Skenario 2: Penjualan Proyek Besar (Kasir)
**Pelanggan**: CV. Konstruksi Jaya
**Aksi**: Membeli material untuk proyek bangunan.
1.  Kasir input: **50 Batang Besi Beton** (50 x 45.000 = 2.250.000)
2.  Kasir input: **2 Pick up Pasir** (2 x 400.000 = 800.000)
3.  **Total Belanja**: Rp 3.050.000
**Efek Sistem**:
- Stok Besi: 200 -> 150
- Stok Pasir: 5 -> 3
- Kas Masuk: +Rp 3.050.000
- Laba Transaksi Ini: (10.000 x 50) + (100.000 x 2) = Rp 700.000

### Skenario 3: Pembelian Stok Menipis (Restock)
**Kondisi**: Stok Pasir tinggal 3, perlu tambah stok.
**Aksi**: Owner melakukan pembelian ke **Distributor Keramik Mulia**.
1.  Owner input: **10 Pick up Pasir**.
2.  Harga Beli (Update): Naik jadi **Rp 310.000** / pick up.
3.  **Total Pengeluaran**: 10 x 310.000 = Rp 3.100.000.
**Efek Sistem**:
- Stok Pasir: 3 + 10 = 13
- Harga Beli Pasir di Database terupdate jadi Rp 310.000
- Kas Keluar: Rp 3.100.000

### Skenario 4: Penjualan Cat (Kasir)
**Pelanggan**: Ibu Siti
**Aksi**: Membeli cat untuk kamar.
1.  Kasir input: **2 Galon Cat Tembok** (2 x 145.000 = 290.000)
2.  Kasir input: **1 Kaleng Thinner** (1 x 30.000 = 30.000)
3.  **Total Belanja**: Rp 320.000
**Efek Sistem**:
- Stok Cat: 20 -> 18
- Stok Thinner: 25 -> 24
- Kas Masuk: +Rp 320.000

---

## C. Rekapitulasi Laporan Harian

Setelah 4 transaksi di atas, Owner membuka menu **Laporan Keuangan**.

### 1. Laporan Stok Terkini
- Semen: 98
- Besi: 150
- Pasir: 13 (Baru restock)
- Cat: 18
- ... (dan seterusnya)

### 2. Laporan Laba/Rugi Harian
- **Total Pemasukan (Penjualan)**:
  - Skenario 1: Rp 152.000
  - Skenario 2: Rp 3.050.000
  - Skenario 4: Rp 320.000
  - **Total Omset**: **Rp 3.522.000**

- **Total Pengeluaran (Pembelian)**:
  - Skenario 3: **Rp 3.100.000**

- **Arus Kas (Cashflow)**:
  - 3.522.000 - 3.100.000 = **Positif Rp 422.000**

- **Estimasi Laba Bersih (Profit Penjualan)**:
  - Dihitung dari margin setiap barang yang terjual hari ini (bukan dari arus kas).
  - Contoh: Margin Besi (10rb x 50) + Pasir (100rb x 2) + dst.

---

## Kesimpulan Simulasi
Tanpa menyimpan ke database permanen, logika aplikasi memastikan:
1.  Stok tidak boleh minus saat penjualan.
2.  Harga beli terakhir akan menjadi acuan HPP (Harga Pokok Penjualan).
3.  Laba dihitung dari selisih Harga Jual dan Harga Beli per item.