<?php
require '../config/koneksi.php';

$id = (int)$_GET['id'];

if ($id <= 0) {
    header("Location: kelola_barang.php");
    exit();
}

try {
    $sql = "DELETE FROM barang WHERE barang_id = ?";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([$id]);
    
    header("Location: kelola_barang.php?status=sukses_hapus");
    exit();

} catch (PDOException $e) {
    die("Gagal menghapus data barang: " . $e->getMessage());
}
?>