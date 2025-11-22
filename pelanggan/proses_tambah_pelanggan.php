<?php
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $nama = $_POST['nama_pelangkan'];
    $alamat = $_POST['alamat'];
    $tlp = $_POST['no_tlp'];
    $tgl = date('Y-m-d H:i:s');

    try {
        $sql = "INSERT INTO pelanggan (nama_pelangkan, alamat, no_tlp, created_at) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $alamat, $tlp, $tgl]);
        
        header("Location: kelola_pelanggan.php?status=sukses");
        exit();

    } catch (PDOException $e) {
        die("Gagal menyimpan: " . $e->getMessage());
    }
}
?>