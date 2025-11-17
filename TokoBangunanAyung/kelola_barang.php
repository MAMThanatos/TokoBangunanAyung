<?php
require 'koneksi.php';

$sql = "SELECT barang_id, nama_barang, satuan, harga_jual, stok FROM barang ORDER BY nama_barang ASC";
$stmt = $pdo->query($sql);
$daftar_barang = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Daftar Stok Barang - Toko Ayung</h2>

        <a href="tambah_barang.php" class="btn-submit">Tambah Barang Baru</a>

        <table>
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th> </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_barang as $barang): ?>
                
                <tr>
                    <td><?php echo htmlspecialchars($barang['barang_id']); ?></td>
                    <td><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($barang['satuan']); ?></td>
                    <td>Rp <?php echo number_format($barang['harga_jual'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($barang['stok']); ?></td>
                    
                    <td>
                        <a href="edit_barang.php?id=<?php echo $barang['barang_id']; ?>" class="btn-edit">Edit</a>
                        
                        <a href="hapus_barang.php?id=<?php echo $barang['barang_id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">Hapus</a>
                    </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>