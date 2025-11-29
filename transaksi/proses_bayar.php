<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $pelanggan_id = !empty($_POST['pelanggan_id']) ? $_POST['pelanggan_id'] : NULL;
    $tanggal = $_POST['tanggal'];
    $bayar = str_replace('.', '', $_POST['bayar']); // Hapus titik jika ada format ribuan
    $user_id = $_SESSION['user_id'];

    // Hitung Total Belanja dari Session
    $total_belanja = 0;
    if (isset($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $item) {
            $total_belanja += $item['subtotal'];
        }
    }

    // Validasi Keranjang Kosong
    if ($total_belanja == 0) {
        echo "<script>alert('Keranjang belanja kosong!'); window.location.href='kasir.php';</script>";
        exit();
    }

    // Validasi Pembayaran
    if ($bayar < $total_belanja) {
        echo "<script>alert('Uang bayar kurang!'); window.history.back();</script>";
        exit();
    }

    $kembalian = $bayar - $total_belanja;

    try {
        // Mulai Transaksi Database
        $pdo->beginTransaction();

        // 1. Simpan ke Tabel Penjualan
        $sql_penjualan = "INSERT INTO penjualan (tanggal, pelanggan_id, user_id, total, bayar, kembalian) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_penjualan = $pdo->prepare($sql_penjualan);
        $stmt_penjualan->execute([$tanggal . ' ' . date('H:i:s'), $pelanggan_id, $user_id, $total_belanja, $bayar, $kembalian]);
        
        $penjualan_id = $pdo->lastInsertId();

        // 2. Simpan Detail Penjualan & Kurangi Stok
        $sql_detail = "INSERT INTO detail_penjualan (penjualan_id, barang_id, qty, harga) VALUES (?, ?, ?, ?)";
        $stmt_detail = $pdo->prepare($sql_detail);

        $sql_update_stok = "UPDATE barang SET stok = stok - ? WHERE barang_id = ?";
        $stmt_update_stok = $pdo->prepare($sql_update_stok);

        foreach ($_SESSION['keranjang'] as $id_barang => $item) {
            // Simpan Detail
            $stmt_detail->execute([$penjualan_id, $id_barang, $item['qty'], $item['harga']]);

            // Kurangi Stok
            $stmt_update_stok->execute([$item['qty'], $id_barang]);
        }

        // 3. Catat ke Transaksi Keuangan (Pemasukan)
        // Keterangan harus diawali "Penjualan" agar tidak muncul di filter "Transaksi Lain"
        $keterangan = "Penjualan #" . str_pad($penjualan_id, 5, '0', STR_PAD_LEFT);
        $sql_keuangan = "INSERT INTO transaksi_keuangan (tanggal, jenis, keterangan, jumlah, user_id) VALUES (?, 'pemasukan', ?, ?, ?)";
        $stmt_keuangan = $pdo->prepare($sql_keuangan);
        $stmt_keuangan->execute([$tanggal . ' ' . date('H:i:s'), $keterangan, $total_belanja, $user_id]);

        // Commit Transaksi
        $pdo->commit();

        // Kosongkan Keranjang
        unset($_SESSION['keranjang']);

        // Redirect ke Struk
        echo "<script>
            alert('Transaksi Berhasil! Kembalian: Rp " . number_format($kembalian, 0, ',', '.') . "');
            window.location.href='struk.php?id=" . $penjualan_id . "';
        </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Terjadi Kesalahan: " . $e->getMessage();
    }

} else {
    header("Location: kasir.php");
}
?>