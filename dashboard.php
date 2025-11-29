<?php
session_start();

if (!isset($_SESSION['status'])) {
    header("Location: login.php");
    exit();
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toko Ayung</title>
    
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="container">
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <img src="gambar/Gemini_Generated_Image_wnd8p5wnd8p5wnd8.png" alt="Logo Toko Ayung" style="height: 60px; width: auto;">
                <div>
                    <h2 style="margin: 0;">Dashboard Utama</h2>
                    <p style="margin:0; color:#666;">Selamat Datang, <b><?php echo htmlspecialchars($nama); ?></b></p>
                </div>
            </div>
            
            <div style="text-align: right;">
                <span class="user-badge">
                    Status: <span class="role-tag"><?php echo strtoupper($role); ?></span>
                </span>
                <br>
                <a href="logout.php" class="btn-cancel" style="font-size: 12px; padding: 5px 10px; margin-top: 5px;">Keluar (Logout)</a>
            </div>
        </div>
        
        <hr>

        <div class="menu-grid">
            
            <!-- Stok Barang - Admin & Owner Only -->
            <?php if ($role == 'admin' || $role == 'owner'): ?>
            <a href="barang/kelola_barang.php" class="card">
                <div class="icon">📦</div>
                <h3>Stok Barang</h3>
                <p>Kelola stok, harga modal, dan harga jual.</p>
            </a>
            <?php endif; ?>

            <!-- Data Pemasok - Admin Only -->
            <?php if ($role == 'admin'): ?>
            <a href="pemasok/kelola_pemasok.php" class="card">
                <div class="icon">🏭</div>
                <h3>Data Pemasok</h3>
                <p>Database supplier tempat kulakan.</p>
            </a>
            <?php endif; ?>

            <!-- Pelanggan - All Roles -->
            <a href="pelanggan/kelola_pelanggan.php" class="card">
                <div class="icon">👥</div>
                <h3>Pelanggan</h3>
                <p>Data buku alamat pelanggan.</p>
            </a>

            <!-- Kasir - Admin & Kasir Only -->
            <?php if ($role == 'admin' || $role == 'kasir'): ?>
            <a href="transaksi/kasir.php" class="card">
                <div class="icon">🛒</div>
                <h3>Penjualan</h3>
                <p>Mencatat penjualan.</p>
            </a>
            <?php endif; ?>

            <!-- Pembelian - Admin Only -->
            <?php if ($role == 'admin' || $role == 'owner'): ?>
            <a href="pembelian/kelola_pembelian.php" class="card">
                <div class="icon">🧾</div>
                <h3>Pembelian</h3>
                <p>Mencatat pembelian.</p>
            </a>
            <?php endif; ?>

            <!-- Laporan - Admin & Owner Only -->
            <?php if ($role == 'admin' || $role == 'owner'): ?>
            <a href="laporan/laporan_keuangan.php" class="card">
                <div class="icon">📊</div>
                <h3>Laporan</h3>
                <p>Laporan keuangan dan laba rugi.</p>
            </a>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>