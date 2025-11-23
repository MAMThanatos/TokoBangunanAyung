<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

$pembelian_id = $_GET['id'] ?? 0;

// Ambil data pembelian
$sql = "SELECT p.*, ps.nama_pemasok, ps.alamat, ps.no_tlp, u.nama as nama_user 
        FROM pembelian p 
        LEFT JOIN pemasok ps ON p.pemasok_id = ps.pemasok_id
        LEFT JOIN users u ON p.user_id = u.user_id
        WHERE p.pembelian_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$pembelian_id]);
$pembelian = $stmt->fetch();

if (!$pembelian) {
    echo "Data tidak ditemukan";
    exit();
}

// Ambil detail pembelian
$sql_detail = "SELECT dp.*, b.nama_barang, b.satuan 
               FROM detail_pembelian dp
               JOIN barang b ON dp.barang_id = b.barang_id
               WHERE dp.pembelian_id = ?";
$stmt_detail = $pdo->prepare($sql_detail);
$stmt_detail->execute([$pembelian_id]);
$detail = $stmt_detail->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pembelian</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>📄 Detail Pembelian #<?php echo str_pad($pembelian_id, 5, '0', STR_PAD_LEFT); ?></h2>
            <a href="kelola_pembelian.php" class="btn-cancel">Kembali</a>
        </div>
        <hr>

        <div class="info-box">
            <table style="width:100%;">
                <tr>
                    <td width="150"><b>Tanggal Pembelian</b></td>
                    <td>: <?php echo date('d F Y, H:i', strtotime($pembelian['tanggal'])); ?> WIB</td>
                </tr>
                <tr>
                    <td><b>Diinput oleh</b></td>
                    <td>: <?php echo $pembelian['nama_user']; ?></td>
                </tr>
                <tr>
                    <td><b>Pemasok</b></td>
                    <td>: <?php echo $pembelian['nama_pemasok'] ?: '-'; ?></td>
                </tr>
                <?php if ($pembelian['nama_pemasok']): ?>
                <tr>
                    <td><b>Alamat</b></td>
                    <td>: <?php echo $pembelian['alamat']; ?></td>
                </tr>
                <tr>
                    <td><b>No. Telepon</b></td>
                    <td>: <?php echo $pembelian['no_tlp']; ?></td>
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
                    <th>Harga Beli</th>
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
                    <td colspan="4" style="text-align:right;"><b>TOTAL PEMBELIAN:</b></td>
                    <td><b style="color:#ff9f43;">Rp <?php echo number_format($pembelian['total'], 0, ',', '.'); ?></b></td>
                </tr>
            </tfoot>
        </table>

    </div>

</body>
</html>