<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

// SECURITY CHECK: Hanya Admin dan Owner yang boleh akses
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'owner') {
    echo "<script>alert('Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.'); window.location.href='../dashboard.php';</script>";
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
$sql = "SELECT p.*, ps.nama_pemasok, u.nama as nama_user 
        FROM pembelian p 
        LEFT JOIN pemasok ps ON p.pemasok_id = ps.pemasok_id
        LEFT JOIN users u ON p.user_id = u.user_id
        WHERE DATE(p.tanggal) BETWEEN ? AND ?
        ORDER BY p.tanggal DESC
        LIMIT ? OFFSET ?";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $tanggal_dari);
$stmt->bindValue(2, $tanggal_sampai);
$stmt->bindValue(3, $limit, PDO::PARAM_INT);
$stmt->bindValue(4, $offset, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetchAll();

// Hitung total
$sql_count = "SELECT COUNT(*) as total FROM pembelian WHERE DATE(tanggal) BETWEEN ? AND ?";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute([$tanggal_dari, $tanggal_sampai]);
$total_data = $stmt_count->fetch()['total'];
$total_pages = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>📦 Riwayat Pembelian (Stok Masuk)</h2>
            <div>
                <a href="tambah_pembelian.php" class="btn-submit">+ Tambah Pembelian</a>
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
                <a href="kelola_pembelian.php" class="btn-secondary">Reset</a>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>No. Pembelian</th>
                    <th>Tanggal</th>
                    <th>Pemasok</th>
                    <th>User</th>
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
                        <td>#<?php echo str_pad($d['pembelian_id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($d['tanggal'])); ?></td>
                        <td><?php echo $d['nama_pemasok'] ?: '-'; ?></td>
                        <td><?php echo $d['nama_user']; ?></td>
                        <td><b>Rp <?php echo number_format($d['total'], 0, ',', '.'); ?></b></td>
                        <td>
                            <a href="detail_pembelian.php?id=<?php echo $d['pembelian_id']; ?>" class="btn-secondary">Detail</a>
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