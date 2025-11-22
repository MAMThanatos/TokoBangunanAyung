<?php
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nama = $_POST['nama_pemasok'];
    $alamat = $_POST['alamat'];
    $tlp = $_POST['no_tlp'];
    $tgl = date('Y-m-d H:i:s'); // Waktu otomatis

    try {
        $sql = "INSERT INTO pemasok (nama_pemasok, alamat, no_tlp, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $alamat, $tlp, $tgl]);
        
        header("Location: kelola_pemasok.php?status=sukses");
    } catch (PDOException $e) {
        die("Gagal: " . $e->getMessage());
    }
}
?>