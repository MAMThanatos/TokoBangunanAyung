<?php
require 'koneksi.php';

// Cek apakah form sudah disubmit menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Ambil data dari formulir (dari $_POST)
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    // (Opsional tapi disarankan) Ambil ID Pemasok, jika ada.
    // Untuk sekarang, kita buat NULL (kosong)
    $pemasok_id = null; 

    try {
        // 2. Siapkan perintah SQL (Query)
        // Kita pakai "prepared statements" (tanda ?) untuk keamanan
        $sql = "INSERT INTO barang (nama_barang, satuan, harga_beli, harga_jual, stok, pemasok_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // 3. Eksekusi perintah dengan data yang sudah diambil
        $stmt->execute([
            $nama_barang, 
            $satuan, 
            $harga_beli, 
            $harga_jual, 
            $stok, 
            $pemasok_id
        ]);
        
        // 4. Jika sukses, kembalikan pengguna ke halaman daftar barang
        header("Location: kelola_barang.php?status=sukses_tambah");
        exit(); // Pastikan script berhenti setelah redirect

    } catch (PDOException $e) {
        // 5. Jika gagal, tampilkan pesan error
        // (Di aplikasi nyata, ini harusnya halaman error yang lebih baik)
        die("Gagal menyimpan data barang: " . $e->getMessage());
    }

} else {
    // Jika file ini diakses langsung tanpa submit form, kembalikan ke awal
    header("Location: kelola_barang.php");
    exit();
}
?>