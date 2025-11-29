<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

$success_msg = "";
$error_msg = "";

// Handle Form Submission untuk Transaksi Lain
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_transaksi_lain'])) {
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

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter tanggal
$tanggal_dari = $_GET['dari'] ?? date('Y-m-01');
$tanggal_sampai = $_GET['sampai'] ?? date('Y-m-d');

// Query data penjualan
// PERBAIKAN: nama_pelanggan diganti jadi nama_pelangkan sesuai database
$sql = "SELECT p.*, pel.nama_pelangkan as nama_pelanggan, u.nama as nama_kasir 
        FROM penjualan p 
        LEFT JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id
        LEFT JOIN users u ON p.user_id = u.user_id
        WHERE DATE(p.tanggal) BETWEEN ? AND ?
        ORDER BY p.tanggal DESC
        LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $tanggal_dari, PDO::PARAM_STR);
$stmt->bindParam(2, $tanggal_sampai, PDO::PARAM_STR);
$stmt->bindParam(3, $limit, PDO::PARAM_INT);
$stmt->bindParam(4, $offset, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll();

// Hitung total
$sql_count = "SELECT COUNT(*) as total FROM penjualan WHERE DATE(tanggal) BETWEEN ? AND ?";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute([$tanggal_dari, $tanggal_sampai]);
$total_data = $stmt_count->fetch()['total'];
$total_pages = ceil($total_data / $limit);

// Query transaksi lain (non penjualan/pembelian)
$sql_lain = "SELECT * FROM transaksi_keuangan 
             WHERE keterangan NOT LIKE 'Penjualan%' 
             AND keterangan NOT LIKE 'Pembelian%' 
             AND DATE(tanggal) BETWEEN ? AND ?
             ORDER BY tanggal DESC 
             LIMIT 10";
$stmt_lain = $pdo->prepare($sql_lain);
$stmt_lain->execute([$tanggal_dari, $tanggal_sampai]);
$transaksi_lain = $stmt_lain->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>📋 Riwayat Transaksi</h2>
            <a href="../dashboard.php" class="btn-cancel">Kembali</a>
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
                <a href="riwayat_penjualan.php" class="btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Tambah Transaksi Operasional Section -->
        <div style="background: #f9fbfd; border: 2px solid #1a3b52; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
            <h3 style="margin-top: 0; color: #1a3b52;">💸 Tambah Transaksi Operasional</h3>
            <p style="color: #666; font-size: 13px; margin-bottom: 15px;">Catat pengeluaran atau pemasukan operasional di luar penjualan/pembelian (contoh: bayar listrik, biaya kebersihan, dll)</p>
            
            <form method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Jenis Transaksi</label>
                        <select name="jenis" required>
                            <option value="pengeluaran">Pengeluaran (Biaya)</option>
                            <option value="pemasukan">Pemasukan (Pendapatan Lain)</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Bayar Listrik, Uang Kebersihan" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Jumlah (Rp)</label>
                        <input type="number" name="jumlah" placeholder="0" required>
                    </div>
                </div>
                <button type="submit" name="tambah_transaksi_lain" class="btn-submit" style="margin-top: 15px;">Simpan Transaksi</button>
            </form>
        </div>

        <!-- Riwayat Transaksi Operasional (jika ada) -->
        <?php if (count($transaksi_lain) > 0): ?>
        <div style="background: #fff3e0; border: 1px solid #c76f35; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
            <h4 style="margin-top: 0; color: #c76f35;">📝 Transaksi Operasional Terbaru (10 terakhir)</h4>
            <table style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksi_lain as $row): ?>
                    <tr>
                        <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                        <td>
                            <?php if ($row['jenis'] == 'pemasukan'): ?>
                                <span class="badge-pemasukan">Pemasukan</span>
                            <?php else: ?>
                                <span class="badge-pengeluaran">Pengeluaran</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['keterangan'] ?? ''); ?></td>
                        <td style="text-align:right;"><b>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></b></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                💡 Untuk melihat semua transaksi operasional, silakan buka <a href="../laporan/laporan_keuangan.php" style="color: #c76f35; font-weight: 600;">Laporan Keuangan</a>
            </p>
        </div>
        <?php endif; ?>

        <!-- Riwayat Penjualan -->
        <h3 style="color: #1a3b52;">🛒 Riwayat Penjualan</h3>
        <table>
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($data as $d): ?>
                    <tr>
                        <td>#<?php echo str_pad($d['penjualan_id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($d['tanggal'])); ?></td>
                        <td><?php echo $d['nama_pelanggan'] ?: 'Umum'; ?></td>
                        <td><?php echo $d['nama_kasir']; ?></td>
                        <td><b>Rp <?php echo number_format($d['total'], 0, ',', '.'); ?></b></td>
                        <td>
                            <a href="detail_penjualan.php?id=<?php echo $d['penjualan_id']; ?>" class="btn-secondary">Detail</a>
                            <a href="struk.php?id=<?php echo $d['penjualan_id']; ?>" class="btn-submit">Struk</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&dari=<?php echo $tanggal_dari; ?>&sampai=<?php echo $tanggal_sampai; ?>" 
                   class="<?php echo $page == $i ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>