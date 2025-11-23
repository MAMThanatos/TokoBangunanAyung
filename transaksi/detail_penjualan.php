<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

$penjualan_id = $_GET['id'] ?? 0;

// Ambil data penjualan
$sql = "SELECT p.*, pel.nama_pelangkan, pel.alamat, pel.no_tlp, u.nama as nama_kasir 
        FROM penjualan p 
        LEFT JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id
        LEFT JOIN users u ON p.user_id = u.user_id
        WHERE p.penjualan_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$penjualan_id]);
$penjualan = $stmt->fetch();

if (!$penjualan) {
    echo "Data tidak ditemukan";
    exit();
}

// Ambil detail penjualan
$sql_detail = "SELECT dp.*, b.nama_barang, b.satuan 
               FROM detail_penjualan dp
               JOIN barang b ON dp.barang_id = b.barang_id
               WHERE dp.penjualan_id = ?";
$stmt_detail = $pdo->prepare($sql_detail);
$stmt_detail->execute([$penjualan_id]);
$detail = $stmt_detail->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Penjualan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>📄 Detail Penjualan #<?php echo str_pad($penjualan_id, 5, '0', STR_PAD_LEFT); ?></h2>
            <a href="riwayat_penjualan.php" class="btn-cancel">Kembali</a>
        </div>
        <hr>

        <div class="info-box">
            <table style="width:100%;">
                <tr>
                    <td width="150"><b>Tanggal Transaksi</b></td>
                    <td>: <?php echo date('d F Y, H:i', strtotime($penjualan['tanggal'])); ?> WIB</td>
                </tr>
                <tr>
                    <td><b>Kasir</b></td>
                    <td>: <?php echo $penjualan['nama_kasir']; ?></td>
                </tr>
                <tr>
                    <td><b>Pelanggan</b></td>
                    <td>: <?php echo $penjualan['nama_pelangkan'] ?: 'Umum (Cash)'; ?></td>
                </tr>
                <?php if ($penjualan['nama_pelangkan']): ?>
                <tr>
                    <td><b>Alamat</b></td>
                    <td>: <?php echo $penjualan['alamat']; ?></td>
                </tr>
                <tr>
                    <td><b>No. Telepon</b></td>
                    <td>: <?php echo $penjualan['no_tlp']; ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <h3>Daftar Barang</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($detail as $d): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $d['nama_barang']; ?></td>
                    <td>Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $d['qty']; ?> <?php echo $d['satuan']; ?></td>
                    <td><b>Rp <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></b></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;"><b>TOTAL BELANJA:</b></td>
                    <td><b style="color:#ff9f43;">Rp <?php echo number_format($penjualan['total'], 0, ',', '.'); ?></b></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right;">Uang Bayar:</td>
                    <td>Rp <?php echo number_format($penjualan['bayar'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right;">Kembalian:</td>
                    <td>Rp <?php echo number_format($penjualan['kembalian'], 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top:20px;">
            <a href="struk.php?id=<?php echo $penjualan_id; ?>" class="btn-submit">🖨️ Cetak Struk</a>
        </div>

    </div>

</body>
</html>