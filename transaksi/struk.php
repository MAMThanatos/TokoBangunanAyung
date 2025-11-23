<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

$penjualan_id = $_GET['id'] ?? 0;

// Ambil data penjualan
$sql = "SELECT p.*, pel.nama_pelangkan, u.nama as nama_kasir 
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
    <title>Struk Pembayaran</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <div class="container" style="max-width: 600px; margin: 20px auto;">
        
        <div class="struk-header">
            <h2 style="margin:0; text-align:center;">TOKO BAHAN BANGUNAN AYUNG</h2>
            <p style="text-align:center; margin:5px 0;">Jl. Contoh No. 123, Telp: (0361) 123456</p>
            <hr>
        </div>

        <div class="struk-info">
            <table style="width:100%; font-size:14px;">
                <tr>
                    <td>No. Transaksi</td>
                    <td>: <b>#<?php echo str_pad($penjualan_id, 5, '0', STR_PAD_LEFT); ?></b></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: <?php echo date('d/m/Y H:i', strtotime($penjualan['tanggal'])); ?></td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: <?php echo $penjualan['nama_kasir']; ?></td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td>: <?php echo $penjualan['nama_pelangkan'] ?: 'Umum'; ?></td>
                </tr>
            </table>
        </div>

        <hr>

        <table style="width:100%; font-size:14px;">
            <thead>
                <tr>
                    <th style="text-align:left;">Barang</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:right;">Harga</th>
                    <th style="text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detail as $d): ?>
                <tr>
                    <td><?php echo $d['nama_barang']; ?></td>
                    <td style="text-align:center;"><?php echo $d['qty']; ?> <?php echo $d['satuan']; ?></td>
                    <td style="text-align:right;">Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                    <td style="text-align:right;">Rp <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <div class="struk-total">
            <table style="width:100%; font-size:16px;">
                <tr>
                    <td style="text-align:right; padding:5px;"><b>TOTAL:</b></td>
                    <td style="text-align:right; padding:5px; width:40%;"><b>Rp <?php echo number_format($penjualan['total'], 0, ',', '.'); ?></b></td>
                </tr>
                <tr>
                    <td style="text-align:right; padding:5px;">Bayar:</td>
                    <td style="text-align:right; padding:5px;">Rp <?php echo number_format($penjualan['bayar'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td style="text-align:right; padding:5px;">Kembalian:</td>
                    <td style="text-align:right; padding:5px;">Rp <?php echo number_format($penjualan['kembalian'], 0, ',', '.'); ?></td>
                </tr>
            </table>
        </div>

        <hr>
        <p style="text-align:center; font-size:12px;">Terima kasih atas kunjungan Anda!</p>

        <div class="no-print" style="text-align:center; margin-top:20px;">
            <button onclick="window.print()" class="btn-submit">🖨️ Cetak Struk</button>
            <a href="kasir.php" class="btn-cancel">Transaksi Baru</a>
            <a href="riwayat_penjualan.php" class="btn-secondary">Lihat Riwayat</a>
        </div>

    </div>

</body>
</html>