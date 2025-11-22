<?php
require '../config/koneksi.php';

$sql = "SELECT * FROM pelanggan ORDER BY nama_pelangkan ASC";
$stmt = $pdo->query($sql);
$daftar_pelanggan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Pelanggan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Data Pelanggan</h2>
            <a href="../dashboard.php" class="btn-cancel" style="font-size:12px;">Kembali ke Dashboard</a>
        </div>
        
        <hr>

        <a href="tambah_pelanggan.php" class="btn-submit">Tambah Pelanggan Baru</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_pelanggan as $pelanggan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pelanggan['pelanggan_id']); ?></td>
                    <!-- Sesuai database kamu: nama_pelangkan (pakai k) -->
                    <td><?php echo htmlspecialchars($pelanggan['nama_pelangkan']); ?></td>
                    <td><?php echo htmlspecialchars($pelanggan['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($pelanggan['no_tlp']); ?></td>
                    <td>
                        <a href="edit_pelanggan.php?id=<?php echo $pelanggan['pelanggan_id']; ?>" class="btn-edit">Edit</a>
                        <a href="hapus_pelanggan.php?id=<?php echo $pelanggan['pelanggan_id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Yakin ingin menghapus pelanggan ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>