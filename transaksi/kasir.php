<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

// Cek role - Admin dan Kasir boleh akses
if ($_SESSION['role'] != 'kasir' && $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak! Hanya Kasir dan Admin yang dapat menggunakan fitur ini.'); window.location.href='../dashboard.php';</script>";
    exit();
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$sql_barang = "SELECT * FROM barang ORDER BY nama_barang ASC";
$stmt_barang = $pdo->query($sql_barang);
$barang_list = $stmt_barang->fetchAll();

$sql_pelanggan = "SELECT * FROM pelanggan ORDER BY nama_pelangkan ASC";
$stmt_pelanggan = $pdo->query($sql_pelanggan);
$pelanggan_list = $stmt_pelanggan->fetchAll();

$total_belanja = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $total_belanja += $item['subtotal'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penjualan Toko Ayung</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .kasir-container {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 20px auto;
        }
        .panel-kiri { flex: 1; }
        .panel-kanan { flex: 2; }
        
        .total-box {
            background: #1a3b52; 
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
            margin-bottom: 20px;
        }
        .total-angka { font-size: 32px; font-weight: bold; color: #ff9f43; /* Orange */ }
    </style>
</head>
<body>

    <!-- Header Sederhana -->
    <div class="container" style="margin-top: 10px; padding: 15px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0;">🛒 Input Penjualan</h3>
            <a href="../dashboard.php" class="btn-cancel">Kembali ke Dashboard</a>
        </div>
    </div>

    <div class="kasir-container">
        
        <!-- PANEL KIRI: INPUT BARANG -->
        <div class="container panel-kiri" style="margin:0; padding: 20px;">
            <h4>Input Transaksi</h4>
            <hr>
            
            <!-- Form Tambah ke Keranjang -->
            <form action="proses_keranjang.php" method="POST">
                <input type="hidden" name="aksi" value="tambah">
                
                <div class="form-group">
                    <label>Pilih Barang</label>
                    <select name="barang_id" class="form-control" required style="width:100%; padding:10px;">
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang_list as $b): ?>
                            <option value="<?php echo $b['barang_id']; ?>">
                                <?php echo $b['nama_barang']; ?> (Stok: <?php echo $b['stok']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah (Qty)</label>
                    <input type="number" name="qty" value="1" min="1" required>
                </div>

                <button type="submit" class="btn-submit" style="width:100%;">+ Masuk Keranjang</button>
            </form>
        </div>

        <!-- PANEL KANAN: DAFTAR BELANJAAN -->
        <div class="container panel-kanan" style="margin:0; padding: 20px;">
            
            <!-- Kotak Total Besar -->
            <div class="total-box">
                Total Bayar
                <div class="total-angka">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></div>
            </div>

            <!-- Tabel Keranjang -->
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (empty($_SESSION['keranjang'])) {
                        echo "<tr><td colspan='6' style='text-align:center;'>Keranjang masih kosong</td></tr>";
                    } else {
                        foreach ($_SESSION['keranjang'] as $id => $isi): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $isi['nama']; ?></td>
                        <td>Rp <?php echo number_format($isi['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $isi['qty']; ?> <?php echo $isi['satuan']; ?></td>
                        <td>Rp <?php echo number_format($isi['subtotal'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="proses_keranjang.php?aksi=hapus&id=<?php echo $id; ?>" 
                               class="btn-delete" style="padding: 5px 10px;">X</a>
                        </td>
                    </tr>
                    <?php endforeach; } ?>
                </tbody>
            </table>

            <hr>

            <!-- Form Pembayaran Akhir -->
            <form action="proses_bayar.php" method="POST" onsubmit="return confirm('Proses transaksi ini?');">
                <div style="display:flex; gap:15px;">
                    <div style="flex:1;">
                        <label>Pilih Pelanggan</label>
                        <select name="pelanggan_id" style="width:100%; padding:10px;">
                            <option value="">-- Pelanggan Umum (Cash) --</option>
                            <?php foreach ($pelanggan_list as $p): ?>
                                <option value="<?php echo $p['pelanggan_id']; ?>">
                                    <?php echo $p['nama_pelangkan']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex:1;">
                        <label>Tanggal Penjualan</label>
                        <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required style="width:100%; padding:8px;">
                    </div>
                </div>
                
                <div style="margin-top: 15px;">
                    <label>Uang Bayar (Rp)</label>
                    <input type="number" name="bayar" required placeholder="Tanpa titik" style="width: 100%; padding: 10px;">
                </div>
                
                <br>
                <button type="submit" class="btn-submit" style="width:100%; font-size:18px; padding:15px;">
                    🖨️ PROSES BAYAR & CETAK
                </button>
            </form>

        </div>
    </div>

</body>
</html>