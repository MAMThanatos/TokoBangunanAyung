<?php
require '../config/koneksi.php';

$id = (int)$_GET['id'];

try {
    $sql = "DELETE FROM pelanggan WHERE pelanggan_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    header("Location: kelola_pelanggan.php?status=hapus_sukses");
} catch (PDOException $e) {
    // Pesan error jika pelanggan sudah pernah belanja (constraint foreign key)
    die("Gagal menghapus: Pelanggan ini memiliki riwayat transaksi. Data tidak bisa dihapus demi arsip keuangan.");
}
?>

<!-- Di dashboard.php -->
<a href="Pelanggan/kelola_pelanggan.php" class="card">
    <div class="icon">👥</div>
    <h3>Pelanggan</h3>
    <p>Data buku alamat pelanggan.</p>
</a>