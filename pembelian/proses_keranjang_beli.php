<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['keranjang_beli'])) {
    $_SESSION['keranjang_beli'] = [];
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

// TAMBAH KE KERANJANG
if ($aksi == 'tambah') {
    $barang_id = $_POST['barang_id'];
    $qty = (int)$_POST['qty'];
    $harga_beli = (float)$_POST['harga_beli'];
    
    // Ambil data barang
    $sql = "SELECT * FROM barang WHERE barang_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$barang_id]);
    $barang = $stmt->fetch();
    
    if ($barang) {
        // Jika barang sudah ada di keranjang, tambahkan qty
        if (isset($_SESSION['keranjang_beli'][$barang_id])) {
            $_SESSION['keranjang_beli'][$barang_id]['qty'] += $qty;
            $_SESSION['keranjang_beli'][$barang_id]['subtotal'] = 
                $_SESSION['keranjang_beli'][$barang_id]['qty'] * $harga_beli;
        } else {
            // Tambah barang baru ke keranjang
            $_SESSION['keranjang_beli'][$barang_id] = [
                'nama' => $barang['nama_barang'],
                'harga' => $harga_beli,
                'qty' => $qty,
                'satuan' => $barang['satuan'],
                'subtotal' => $qty * $harga_beli
            ];
        }
    }
    
    header("Location: tambah_pembelian.php");
    exit();
}

// HAPUS DARI KERANJANG
if ($aksi == 'hapus') {
    $id = $_GET['id'];
    if (isset($_SESSION['keranjang_beli'][$id])) {
        unset($_SESSION['keranjang_beli'][$id]);
    }
    header("Location: tambah_pembelian.php");
    exit();
}
?>