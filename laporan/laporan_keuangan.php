<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

// Filter tanggal
$tanggal_dari = $_GET['dari'] ?? date('Y-m-01');
$tanggal_sampai = $_GET['sampai'] ?? date('Y-m-d');

// Hitung total penjualan
$sql_penjualan = "SELECT COALESCE(SUM(total), 0) as total_penjualan, COUNT(*) as jumlah_transaksi
                  FROM penjualan 
                  WHERE DATE(tanggal) BETWEEN ? AND ?";
$stmt_penjualan = $pdo->prepare($sql_penjualan);
$stmt_penjualan->execute([$tanggal_dari, $tanggal_sampai]);
$data_penjualan = $stmt_penjualan->fetch();

// Hitung total pembelian
$sql_pembelian = "SELECT COALESCE(SUM(total), 0) as total_pembelian, COUNT(*) as jumlah_transaksi
                  FROM pembelian 
                  WHERE DATE(tanggal) BETWEEN ? AND ?";
$stmt_pembelian = $pdo->prepare($sql_pembelian);
$stmt_pembelian->execute([$tanggal_dari, $tanggal_sampai]);
$data_pembelian = $stmt_pembelian->fetch();

// Hitung pemasukan lain
$sql_pemasukan = "SELECT COALESCE(SUM(jumlah), 0) as total
                  FROM transaksi_keuangan 
                  WHERE jenis = 'pemasukan' 
                  AND keterangan NOT LIKE 'Penjualan%'
                  AND DATE(tanggal) BETWEEN ? AND ?";
$stmt_pemasukan = $pdo->prepare($sql_pemasukan);
$stmt_pemasukan->execute([$tanggal_dari, $tanggal_sampai]);
$pemasukan_lain = $stmt_pemasukan->fetch()['total'];

// Hitung pengeluaran lain
$sql_pengeluaran = "SELECT COALESCE(SUM(jumlah), 0) as total
                    FROM transaksi_keuangan 
                    WHERE jenis = 'pengeluaran' 
                    AND keterangan NOT LIKE 'Pembelian%'
                    AND DATE(tanggal) BETWEEN ? AND ?";
$stmt_pengeluaran = $pdo->prepare($sql_pengeluaran);
$stmt_pengeluaran->execute([$tanggal_dari, $tanggal_sampai]);
$pengeluaran_lain = $stmt_pengeluaran->fetch()['total'];

// Hitung laba kotor (penjualan - pembelian)
$laba_kotor = $data_penjualan['total_penjualan'] - $data_pembelian['total_pembelian'];

// Hitung laba bersih
$total_pemasukan = $data_penjualan['total_penjualan'] + $pemasukan_lain;
$total_pengeluaran = $data_pembelian['total_pembelian'] + $pengeluaran_lain;
$laba_bersih = $total_pemasukan - $total_pengeluaran;

// Ambil transaksi keuangan lainnya
$sql_transaksi = "SELECT * FROM transaksi_keuangan 
                  WHERE DATE(tanggal) BETWEEN ? AND ?
                  AND (keterangan NOT LIKE 'Penjualan%' AND keterangan NOT LIKE 'Pembelian%')
                  ORDER BY tanggal DESC";
$stmt_transaksi = $pdo->prepare($sql_transaksi);
$stmt_transaksi->execute([$tanggal_dari, $tanggal_sampai]);
$transaksi_lain = $stmt_transaksi->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>📊 Laporan Keuangan</h2>
            <div>
                <a href="tambah_transaksi.php" class="btn-submit">+ Transaksi Lain</a>
                <a href="../dashboard.php" class="btn-cancel">Kembali</a>
            </div>
        </div>
        <hr>

        <!-- Filter Tanggal -->
        <form method="GET" style="margin-bottom:20px;">
            <div style="display:flex; gap:10px; align-items:end;">
                <div>
                    <label>Dari Tanggal:</label>
                    <input type="date" name="dari" value="<?php echo $tanggal_dari; ?>">
                </div>
                <div>
                    <label>Sampai Tanggal:</label>
                    <input type="date" name="sampai" value="<?php echo $tanggal_sampai; ?>">
                </div>
                <button type="submit" class="btn-submit">🔍 Filter</button>
                <a href="laporan_keuangan.php" class="btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Ringkasan Keuangan -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 30px;">
            
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-label">Total Penjualan</div>
                <div class="stat-value">Rp <?php echo number_format($data_penjualan['total_penjualan'], 0, ',', '.'); ?></div>
                <div class="stat-desc"><?php echo $data_penjualan['jumlah_transaksi']; ?> transaksi</div>
            </div>

            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-label">Total Pembelian</div>
                <div class="stat-value">Rp <?php echo number_format($data_pembelian['total_pembelian'], 0, ',', '.'); ?></div>
                <div class="stat-desc"><?php echo $data_pembelian['jumlah_transaksi']; ?> transaksi</div>
            </div>

            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-label">Laba Kotor</div>
                <div class="stat-value">Rp <?php echo number_format($laba_kotor, 0, ',', '.'); ?></div>
                <div class="stat-desc">Penjualan - Pembelian</div>
            </div>

            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="stat-label">Laba Bersih</div>
                <div class="stat-value">Rp <?php echo number_format($laba_bersih, 0, ',', '.'); ?></div>
                <div class="stat-desc">Setelah semua transaksi</div>
            </div>

        </div>

        <!-- Detail Pemasukan & Pengeluaran -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            
            <div class="info-box">
                <h3 style="color: #43e97b;">💰 Pemasukan</h3>
                <table style="width:100%;">
                    <tr>
                        <td>Penjualan</td>
                        <td style="text-align:right;"><b>Rp <?php echo number_format($data_penjualan['total_penjualan'], 0, ',', '.'); ?></b></td>
                    </tr>
                    <tr>
                        <td>Pemasukan Lainnya</td>
                        <td style="text-align:right;"><b>Rp <?php echo number_format($pemasukan_lain, 0, ',', '.'); ?></b></td>
                    </tr>
                    <tr style="border-top: 2px solid #ddd;">
                        <td><b>TOTAL PEMASUKAN</b></td>
                        <td style="text-align:right;"><b style="color:#43e97b;">Rp <?php echo number_format($total_pemasukan, 0, ',', '.'); ?></b></td>
                    </tr>
                </table>
            </div>

            <div class="info-box">
                <h3 style="color: #f5576c;">💸 Pengeluaran</h3>
                <table style="width:100%;">
                    <tr>
                        <td>Pembelian Barang</td>
                        <td style="text-align:right;"><b>Rp <?php echo number_format($data_pembelian['total_pembelian'], 0, ',', '.'); ?></b></td>
                    </tr>
                    <tr>
                        <td>Pengeluaran Lainnya</td>
                        <td style="text-align:right;"><b>Rp <?php echo number_format($pengeluaran_lain, 0, ',', '.'); ?></b></td>
                    </tr>
                    <tr style="border-top: 2px solid #ddd;">
                        <td><b>TOTAL PENGELUARAN</b></td>
                        <td style="text-align:right;"><b style="color:#f5576c;">Rp <?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></b></td>
                    </tr>
                </table>
            </div>

        </div>

        <!-- Transaksi Keuangan Lainnya -->
        <h3>Transaksi Keuangan Lainnya</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transaksi_lain)): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada transaksi lainnya</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($transaksi_lain as $t): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($t['tanggal'])); ?></td>
                        <td>
                            <span class="badge-<?php echo $t['jenis']; ?>">
                                <?php echo ucfirst($t['jenis']); ?>
                            </span>
                        </td>
                        <td><?php echo $t['keterangan']; ?></td>
                        <td>
                            <b style="color: <?php echo $t['jenis'] == 'pemasukan' ? '#43e97b' : '#f5576c'; ?>;">
                                Rp <?php echo number_format($t['jumlah'], 0, ',', '.'); ?>
                            </b>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</body>
</html>