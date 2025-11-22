<?php
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST['pelanggan_id'];
    $nama = $_POST['nama_pelangkan'];
    $alamat = $_POST['alamat'];
    $tlp = $_POST['no_tlp'];

    try {
        // Query Update
        $sql = "UPDATE pelanggan 
                SET nama_pelangkan = ?, 
                    alamat = ?, 
                    no_tlp = ?
                WHERE pelanggan_id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $alamat, $tlp, $id]);
        
        header("Location: kelola_pelanggan.php?status=update_sukses");
        exit();

    } catch (PDOException $e) {
        die("Gagal update: " . $e->getMessage());
    }
}
?>