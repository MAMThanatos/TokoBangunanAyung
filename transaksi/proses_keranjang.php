<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

// TAMBAH KE KERANJANG
if ($aksi == 'tambah') {
    $barang_id = $_POST['barang_id'];
    $qty = (int)$_POST['qty'];
    
    // Ambil data barang
    $sql = "SELECT * FROM barang WHERE barang_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$barang_id]);
    $barang = $stmt->fetch();
    
    if ($barang) {
        // Cek stok
        if ($barang['stok'] < $qty) {
            echo "<script>alert('Stok tidak mencukupi! Stok tersedia: {$barang['stok']}'); window.location='kasir.php';</script>";
            exit();
        }
        
        // Jika barang sudah ada di keranjang, tambahkan qty
        if (isset($_SESSION['keranjang'][$barang_id])) {
            $qty_baru = $_SESSION['keranjang'][$barang_id]['qty'] + $qty;
            
            if ($barang['stok'] < $qty_baru) {
                echo "<script>alert('Stok tidak mencukupi! Stok tersedia: {$barang['stok']}'); window.location='kasir.php';</script>";
                exit();
            }
            
            $_SESSION['keranjang'][$barang_id]['qty'] = $qty_baru;
            $_SESSION['keranjang'][$barang_id]['subtotal'] = $qty_baru * $barang['harga_jual'];
        } else {
            // Tambah barang baru ke keranjang
            $_SESSION['keranjang'][$barang_id] = [
                'nama' => $barang['nama_barang'],
                'harga' => $barang['harga_jual'],
                'qty' => $qty,
                'satuan' => $barang['satuan'],
                'subtotal' => $qty * $barang['harga_jual']
            ];
        }
    }
    
    header("Location: kasir.php");
    exit();
}

// HAPUS DARI KERANJANG
if ($aksi == 'hapus') {
    $id = $_GET['id'];
    if (isset($_SESSION['keranjang'][$id])) {
        unset($_SESSION['keranjang'][$id]);
    }
    header("Location: kasir.php");
    exit();
}

// KOSONGKAN KERANJANG
if ($aksi == 'reset') {
    $_SESSION['keranjang'] = [];
    header("Location: kasir.php");
    exit();
}
?>