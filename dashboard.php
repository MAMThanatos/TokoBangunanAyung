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
            <div>
                <h2>Dashboard Utama</h2>
                <p style="margin:0; color:#666;">Selamat Datang, <b><?php echo htmlspecialchars($nama); ?></b></p>
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
            
            <?php if ($role == 'admin' || $role == 'owner'): ?>
            <a href="Barang/kelola_barang.php" class="card">
                <div class="icon">📦</div>
                <h3>Stok Barang</h3>
                <p>Kelola stok, harga modal, dan harga jual.</p>
            </a>
            <?php endif; ?>

            <?php if ($role == 'admin'): ?>
            <a href="Pemasok/kelola_pemasok.php" class="card">
                <div class="icon">🏭</div>
                <h3>Data Pemasok</h3>
                <p>Database supplier tempat kulakan.</p>
            </a>
            <?php endif; ?>

            <a href="#" class="card" style="opacity: 0.7;">
                <div class="icon">👥</div>
                <h3>Pelanggan</h3>
                <p>Data buku alamat pelanggan. (Coming Soon)</p>
            </a>

            <a href="#" class="card" style="opacity: 0.7;">
                <div class="icon">🛒</div>
                <h3>Kasir</h3>
                <p>Mesin kasir penjualan. (Coming Soon)</p>
            </a>

            <?php if ($role == 'admin' || $role == 'owner'): ?>
            <a href="#" class="card" style="opacity: 0.7;">
                <div class="icon">📊</div>
                <h3>Laporan</h3>
                <p>Laporan keuangan dan laba rugi.</p>
            </a>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>