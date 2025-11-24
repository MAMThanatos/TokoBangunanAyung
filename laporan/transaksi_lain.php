<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $keterangan = $_POST['keterangan'];
    $jumlah = $_POST['jumlah'];
    $user_id = $_SESSION['user_id'];

    if (!empty($tanggal) && !empty($jenis) && !empty($keterangan) && !empty($jumlah)) {
        try {
            $sql = "INSERT INTO transaksi_keuangan (tanggal, jenis, keterangan, jumlah, user_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$tanggal, $jenis, $keterangan, $jumlah, $user_id]);
            $success_msg = "Transaksi berhasil disimpan!";
        } catch (PDOException $e) {
            $error_msg = "Error: " . $e->getMessage();
        }
    } else {
        $error_msg = "Mohon lengkapi semua data.";
    }
}

$sql_data = "SELECT * FROM transaksi_keuangan WHERE keterangan NOT LIKE 'Penjualan%' AND keterangan NOT LIKE 'Pembelian%' ORDER BY tanggal DESC LIMIT 50";
$stmt_data = $pdo->query($sql_data);
$transaksi_list = $stmt_data->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Transaksi Lain</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>💸 Kelola Transaksi Lain</h2>
            <a href="laporan_keuangan.php" class="btn-cancel">Kembali ke Laporan</a>
        </div>
        <hr>

        <?php if ($success_msg): ?>
            <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <div class="transaksi-wrapper">
            <!-- Form Input -->
            <div class="card">
                <h3>Tambah Transaksi</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Transaksi</label>
                        <select name="jenis" required>
                            <option value="pengeluaran">Pengeluaran (Biaya)</option>
                            <option value="pemasukan">Pemasukan (Pendapatan Lain)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Bayar Listrik, Uang Kebersihan" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah (Rp)</label>
                        <input type="number" name="jumlah" placeholder="0" required>
                    </div>
                    <button type="submit" class="btn-submit" style="width:100%;">Simpan</button>
                </form>
            </div>

            <!-- Table List -->
            <div class="card">
                <h3>Riwayat Transaksi Lain</h3>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($transaksi_list) > 0): ?>
                                <?php foreach ($transaksi_list as $row): ?>
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                    <td>
                                        <?php if ($row['jenis'] == 'pemasukan'): ?>
                                            <span style="color:green; font-weight:bold;">Pemasukan</span>
                                        <?php else: ?>
                                            <span style="color:red; font-weight:bold;">Pengeluaran</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                                    <td style="text-align:right;">Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align:center;">Belum ada data transaksi lain.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>