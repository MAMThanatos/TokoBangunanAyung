<?php
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST['pemasok_id'];
    $nama = $_POST['nama_pemasok'];
    $alamat = $_POST['alamat'];
    $tlp = $_POST['no_tlp'];

    try {
        $sql = "UPDATE pemasok SET nama_pemasok=?, alamat=?, no_tlp=? WHERE pemasok_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $alamat, $tlp, $id]);
        
        header("Location: kelola_pemasok.php?status=update_sukses");
    } catch (PDOException $e) {
        die("Gagal: " . $e->getMessage());
    }
}
?>