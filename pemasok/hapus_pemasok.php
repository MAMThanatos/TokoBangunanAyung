<?php
require '../config/koneksi.php';
$id = (int)$_GET['id'];

try {
    $sql = "DELETE FROM pemasok WHERE pemasok_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    header("Location: kelola_pemasok.php?status=hapus_sukses");
} catch (PDOException $e) {
    die("Gagal menghapus: Pemasok ini mungkin masih terhubung dengan data pembelian barang.");
}
?>