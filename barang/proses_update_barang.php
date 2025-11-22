<?php
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $barang_id = $_POST['barang_id'];
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    try {
        $sql = "UPDATE barang 
                SET nama_barang = ?, 
                    satuan = ?, 
                    harga_beli = ?, 
                    harga_jual = ?, 
                    stok = ? 
                WHERE barang_id = ?";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $nama_barang, 
            $satuan, 
            $harga_beli, 
            $harga_jual, 
            $stok, 
            $barang_id
        ]);
        
        header("Location: kelola_barang.php?status=sukses_update");
        exit();

    } catch (PDOException $e) {
        die("Gagal mengupdate data barang: " . $e->getMessage());
    }

} else {
    header("Location: kelola_barang.php");
    exit();
}
?>