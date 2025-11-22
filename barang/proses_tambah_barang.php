<?php
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    $pemasok_id = null; 

    try {
        $sql = "INSERT INTO barang (nama_barang, satuan, harga_beli, harga_jual, stok, pemasok_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $nama_barang, 
            $satuan, 
            $harga_beli, 
            $harga_jual, 
            $stok, 
            $pemasok_id
        ]);
        
        header("Location: kelola_barang.php?status=sukses_tambah");
        exit();

    } catch (PDOException $e) {
        die("Gagal menyimpan data barang: " . $e->getMessage());
    }

} else {
    header("Location: kelola_barang.php");
    exit();
}
?>