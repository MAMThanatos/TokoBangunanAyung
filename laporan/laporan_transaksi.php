<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis = $_POST['jenis'];
    $keterangan = $_POST['keterangan'];
    $jumlah = (float)$_POST['jumlah'];
    $user_id = $_SESSION['user_id'];
    
    try {
        $sql = "INSERT INTO transaksi_keuangan (jenis, keterangan, jumlah, user_id) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$jenis, $keterangan, $jumlah, $user_id]);
        
        echo "<script>alert('Transaksi berhasil ditambahkan!'); window.location='laporan_keuangan.php';</script>";
        exit();
    } catch (Exception $e) {
        $error = "Gagal menambahkan transaksi: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi Keuangan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container" style="max-width: 600px;">
        <h2>💰 Tambah Transaksi Keuangan</h2>
        <p style="color:#666;">Untuk mencatat pemasukan/pengeluaran di luar penjualan dan pembelian barang</p>
        <hr>

        <?php if (isset($error)): ?>
        <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            
            <div class="form-group">
                <label>Jenis Transaksi <span style="color:red;">*</span></label>
                <select name="jenis" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="pemasukan">💰 Pemasukan</option>
                    <option value="pengeluaran">💸 Pengeluaran</option>
                </select>
            </div>

            <div class="form-group">
                <label>Keterangan <span style="color:red;">*</span></label>
                <textarea name="keterangan" rows="3" required placeholder="Contoh: Biaya listrik bulan Januari, Penjualan aset, dll"></textarea>
            </div>

            <div class="form-group">
                <label>Jumlah (Rp) <span style="color:red;">*</span></label>
                <input type="number" name="jumlah" required placeholder="Tanpa titik">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Simpan</button>
                <a href="laporan_keuangan.php" class="btn-cancel">Batal</a>
            </div>

        </form>

    </div>

</body>
</html>