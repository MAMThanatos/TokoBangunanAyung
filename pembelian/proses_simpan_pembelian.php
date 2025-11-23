<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pemasok_id = $_POST['pemasok_id'];
    $user_id = $_SESSION['user_id'];
    
    // Hitung total
    $total = 0;
    foreach ($_SESSION['keranjang_beli'] as $item) {
        $total += $item['subtotal'];
    }
    
    if (empty($_SESSION['keranjang_beli'])) {
        echo "<script>alert('Keranjang pembelian kosong!'); window.location='tambah_pembelian.php';</script>";
        exit();
    }
    
    try {
        $pdo->beginTransaction();
        
        // 1. Insert ke tabel pembelian
        $sql_pembelian = "INSERT INTO pembelian (pemasok_id, user_id, total) 
                          VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql_pembelian);
        $stmt->execute([$pemasok_id, $user_id, $total]);
        $pembelian_id = $pdo->lastInsertId();
        
        // 2. Insert detail pembelian dan tambah stok
        foreach ($_SESSION['keranjang_beli'] as $barang_id => $item) {
            // Insert detail
            $sql_detail = "INSERT INTO detail_pembelian (pembelian_id, barang_id, qty, harga) 
                          VALUES (?, ?, ?, ?)";
            $stmt_detail = $pdo->prepare($sql_detail);
            $stmt_detail->execute([$pembelian_id, $barang_id, $item['qty'], $item['harga']]);
            
            // Tambah stok
            $sql_stok = "UPDATE barang SET stok = stok + ?, harga_beli = ? WHERE barang_id = ?";
            $stmt_stok = $pdo->prepare($sql_stok);
            $stmt_stok->execute([$item['qty'], $item['harga'], $barang_id]);
        }
        
        // 3. Catat ke transaksi keuangan (pengeluaran)
        $sql_keuangan = "INSERT INTO transaksi_keuangan (jenis, keterangan, jumlah, user_id) 
                        VALUES ('pengeluaran', ?, ?, ?)";
        $stmt_keuangan = $pdo->prepare($sql_keuangan);
        $keterangan = "Pembelian #" . $pembelian_id;
        $stmt_keuangan->execute([$keterangan, $total, $user_id]);
        
        $pdo->commit();
        
        // Kosongkan keranjang
        $_SESSION['keranjang_beli'] = [];
        
        echo "<script>alert('Pembelian berhasil disimpan!'); window.location='detail_pembelian.php?id=" . $pembelian_id . "';</script>";
        exit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Gagal menyimpan pembelian: " . $e->getMessage() . "'); window.location='tambah_pembelian.php';</script>";
        exit();
    }
}
?>