<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter tanggal
$tanggal_dari = $_GET['dari'] ?? date('Y-m-01');
$tanggal_sampai = $_GET['sampai'] ?? date('Y-m-d');

// Query data
$sql = "SELECT p.*, pel.nama_pelangkan, u.nama as nama_kasir 
        FROM penjualan p 
        LEFT JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id
        LEFT JOIN users u ON p.user_id = u.user_id
        WHERE DATE(p.tanggal) BETWEEN ? AND ?
        ORDER BY p.tanggal DESC
        LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$tanggal_dari, $tanggal_sampai, $limit, $offset]);
$data = $stmt->fetchAll();

// Hitung total
$sql_count = "SELECT COUNT(*) as total FROM penjualan WHERE DATE(tanggal) BETWEEN ? AND ?";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute([$tanggal_dari, $tanggal_sampai]);
$total_data = $stmt_count->fetch()['total'];
$total_pages = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Penjualan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>📋 Riwayat Penjualan</h2>
            <a href="../dashboard.php" class="btn-cancel">Kembali</a>
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
                <a href="riwayat_penjualan.php" class="btn-secondary">Reset</a>
            </div>
        </form>

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
                        <td><?php echo $d['nama_pelangkan'] ?: 'Umum'; ?></td>
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