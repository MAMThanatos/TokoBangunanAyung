# Sistem Informasi Keuangan Toko Bahan Bangunan Ayung

Implementasi Sistem Informasi Keuangan pada Toko Bahan Bangunan Ayung Berbasis Web

# Kelompok
Muhamad Aziz Mufashshal
Muhammad Nur Fahruroji Assyidiq
Muhammad Rifqy Pratama

## 📋 Fitur Lengkap

### 1. Autentikasi & Dashboard
- ✅ Login dengan role (Admin/Owner/Kasir)
- ✅ Dashboard dengan menu navigasi berdasarkan role
- ✅ Logout

### 2. Master Data
- ✅ **Kelola Barang**: CRUD barang dengan stok, harga beli, harga jual
- ✅ **Kelola Pemasok**: CRUD data pemasok/supplier
- ✅ **Kelola Pelanggan**: CRUD data pelanggan

### 3. Transaksi Penjualan
- ✅ **Kasir (POS)**: 
  - Sistem keranjang belanja
  - Pilih barang dan qty
  - Pilih pelanggan (opsional)
  - Input uang bayar dan hitung kembalian
  - Validasi stok otomatis
- ✅ **Proses Pembayaran**:
  - Simpan transaksi penjualan
  - Kurangi stok otomatis
  - Catat ke transaksi keuangan
- ✅ **Struk Pembayaran**: Cetak struk transaksi
- ✅ **Riwayat Penjualan**: 
  - Daftar semua transaksi penjualan
  - Filter berdasarkan tanggal
  - Detail transaksi
  - Pagination

### 4. Transaksi Pembelian
- ✅ **Input Pembelian**:
  - Sistem keranjang pembelian
  - Pilih barang dan qty
  - Input harga beli
  - Pilih pemasok
- ✅ **Proses Pembelian**:
  - Simpan transaksi pembelian
  - Tambah stok otomatis
  - Update harga beli barang
  - Catat ke transaksi keuangan
- ✅ **Riwayat Pembelian**:
  - Daftar semua transaksi pembelian
  - Filter berdasarkan tanggal
  - Detail transaksi
  - Pagination

### 5. Laporan Keuangan
- ✅ **Dashboard Keuangan**:
  - Total penjualan
  - Total pembelian
  - Laba kotor (penjualan - pembelian)
  - Laba bersih (setelah semua transaksi)
- ✅ **Detail Pemasukan & Pengeluaran**:
  - Breakdown pemasukan (penjualan + lainnya)
  - Breakdown pengeluaran (pembelian + lainnya)
- ✅ **Transaksi Keuangan Lainnya**:
  - Input pemasukan/pengeluaran di luar operasional
  - Contoh: biaya listrik, penjualan aset, dll
- ✅ **Filter Laporan**: Filter berdasarkan periode tanggal

## 🗂️ Struktur Database

### Tabel Utama:
1. **users** - Data pengguna sistem
2. **barang** - Master data barang
3. **pemasok** - Master data pemasok
4. **pelanggan** - Master data pelanggan
5. **penjualan** - Header transaksi penjualan
6. **detail_penjualan** - Detail item penjualan
7. **pembelian** - Header transaksi pembelian
8. **detail_pembelian** - Detail item pembelian
9. **transaksi_keuangan** - Transaksi keuangan lainnya

## 📁 Struktur File

```
TokoBangunanAyung/
├── assets/
│   └── style.css              # CSS terpusat untuk semua halaman
├── barang/                    # Modul barang
│   ├── kelola_barang.php
│   ├── tambah_barang.php
│   ├── edit_barang.php
│   └── ...
├── pemasok/                   # Modul pemasok
├── pelanggan/                 # Modul pelanggan
├── transaksi/                 # Modul transaksi penjualan
│   ├── kasir.php             # POS kasir
│   ├── proses_keranjang.php  # Proses keranjang
│   ├── proses_bayar.php      # Proses pembayaran
│   ├── struk.php             # Cetak struk
│   ├── riwayat_penjualan.php # History penjualan
│   └── detail_penjualan.php  # Detail transaksi
├── pembelian/                 # Modul pembelian
│   ├── kelola_pembelian.php  # Riwayat pembelian
│   ├── tambah_pembelian.php  # Input pembelian
│   ├── detail_pembelian.php  # Detail pembelian
│   └── ...
├── laporan/                   # Modul laporan
│   ├── laporan_keuangan.php  # Dashboard keuangan
│   └── tambah_transaksi.php  # Input transaksi lain
├── config/
│   └── koneksi.php           # Koneksi database
├── database/
│   └── db_tba.sql            # SQL database
├── login.php
├── dashboard.php
└── logout.php
```

## 🎨 Desain & UI

- **Tema Warna**: Biru Navy (#1a3b52) dan Oranye (#c76f35)
- **Font**: Poppins, Segoe UI
- **Responsive**: Mobile-friendly
- **CSS Terpusat**: Semua styling di `assets/style.css`

## 🚀 Cara Instalasi

1. **Import Database**:
   ```sql
   -- Import file database/db_tba.sql ke MySQL
   ```

2. **Konfigurasi Koneksi**:
   - Edit `config/koneksi.php`
   - Sesuaikan host, username, password, dan nama database

3. **Akses Aplikasi**:
   - Buka `login.php` di browser
   - Login dengan kredensial yang sudah dibuat

## 📊 Fitur Utama

### Sistem Kasir (POS)
- Interface 2 panel: input barang (kiri) dan keranjang (kanan)
- Real-time total belanja
- Validasi stok otomatis
- Support pelanggan umum atau terdaftar

### Manajemen Stok
- Stok otomatis berkurang saat penjualan
- Stok otomatis bertambah saat pembelian
- Update harga beli otomatis saat pembelian

### Laporan Keuangan
- Dashboard visual dengan card statistik
- Perhitungan laba kotor dan bersih
- Filter periode laporan
- Detail breakdown pemasukan & pengeluaran

## 🔐 Role & Akses

- **Admin**: Full access ke semua fitur
- **Owner**: Akses ke laporan, barang, pembelian (tidak bisa kelola pemasok)
- **Kasir**: Akses ke kasir, pelanggan, riwayat penjualan

## 💡 Teknologi

- **Backend**: PHP 8.x dengan PDO
- **Database**: MySQL 8.x
- **Frontend**: HTML5, CSS3, JavaScript
- **Design**: Custom CSS

## 📝 Catatan Pengembangan

- Semua transaksi menggunakan database transaction untuk data integrity
- Validasi stok di sisi server
- Session management untuk keamanan
- Prepared statements untuk mencegah SQL injection
- Responsive design untuk mobile access

## 🎯 Fitur yang Sudah Lengkap

✅ Autentikasi & Authorization
✅ Master Data (Barang, Pemasok, Pelanggan)
✅ Transaksi Penjualan (Kasir + Riwayat)
✅ Transaksi Pembelian (Input + Riwayat)
✅ Laporan Keuangan (Dashboard + Detail)
✅ Cetak Struk
✅ Filter & Pagination
✅ CSS Terpusat
✅ Responsive Design

---

**Developed for**: Toko Bahan Bangunan Ayung
**Version**: 1.0 Complete
**Last Update**: 2025