<?php
require '../config/koneksi.php';

// Ambil data dari tabel pemasok
$sql = "SELECT * FROM pemasok ORDER BY nama_pemasok ASC";
$stmt = $pdo->query($sql);
$daftar_pemasok = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Pemasok</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Data Pemasok (Supplier) - Toko Ayung</h2>
            <a href="../dashboard.php" class="btn-cancel">Kembali ke Dashboard</a>
        </div>
        
        <a href="kelola_barang.php" class="btn-cancel">Lihat Stok Barang</a>
        <br><br>

        <a href="tambah_pemasok.php" class="btn-submit">Tambah Pemasok Baru</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pemasok</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_pemasok as $pemasok): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pemasok['pemasok_id']); ?></td>
                    <td><?php echo htmlspecialchars($pemasok['nama_pemasok']); ?></td>
                    <td><?php echo htmlspecialchars($pemasok['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($pemasok['no_tlp']); ?></td>
                    <td>
                        <a href="edit_pemasok.php?id=<?php echo $pemasok['pemasok_id']; ?>" class="btn-edit">Edit</a>
                        <a href="hapus_pemasok.php?id=<?php echo $pemasok['pemasok_id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Yakin ingin menghapus pemasok ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>