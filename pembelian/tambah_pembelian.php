<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit();
}

// SECURITY CHECK: Hanya Admin dan Owner yang boleh akses
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'owner') {
    echo "<script>alert('Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.'); window.location.href='../dashboard.php';</script>";
    exit();
}

if (!isset($_SESSION['keranjang_beli'])) {
    $_SESSION['keranjang_beli'] = [];
}

$sql_barang = "SELECT * FROM barang ORDER BY nama_barang ASC";
$stmt_barang = $pdo->query($sql_barang);
$barang_list = $stmt_barang->fetchAll();

$sql_pemasok = "SELECT * FROM pemasok ORDER BY nama_pemasok ASC";
$stmt_pemasok = $pdo->query($sql_pemasok);
$pemasok_list = $stmt_pemasok->fetchAll();

$total_pembelian = 0;
foreach ($_SESSION['keranjang_beli'] as $item) {
    $total_pembelian += $item['subtotal'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pembelian</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <div class="container" style="margin-top: 10px; padding: 15px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0;">📦 Input Pembelian Barang</h3>
            <a href="kelola_pembelian.php" class="btn-cancel">Kembali</a>
        </div>
    </div>

    <div style="display: flex; gap: 20px; max-width: 1200px; margin: 20px auto;">
        
        <!-- PANEL KIRI: INPUT BARANG -->
        <div class="container" style="flex: 1; margin:0; padding: 20px;">
            <h4>Input Item Pembelian</h4>
            <hr>
            
            <form action="proses_keranjang_beli.php" method="POST">
                <input type="hidden" name="aksi" value="tambah">
                
                <div class="form-group">
                    <label>Pilih Barang</label>
                    <select name="barang_id" class="form-control" required style="width:100%; padding:10px;">
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($barang_list as $b): ?>
                            <option value="<?php echo $b['barang_id']; ?>">
                                <?php echo $b['nama_barang']; ?> (Stok: <?php echo $b['stok']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah (Qty)</label>
                    <input type="number" name="qty" value="1" min="1" required>
                </div>

                <div class="form-group">
                    <label>Harga Beli per Unit (Rp)</label>
                    <input type="number" name="harga_beli" required placeholder="Tanpa titik">
                </div>

                <button type="submit" class="btn-submit" style="width:100%;">+ Tambah ke Daftar</button>
            </form>
        </div>

        <!-- PANEL KANAN: DAFTAR PEMBELIAN -->
        <div class="container" style="flex: 2; margin:0; padding: 20px;">
            
            <div class="total-box">
                Total Pembelian
                <div class="total-angka">Rp <?php echo number_format($total_pembelian, 0, ',', '.'); ?></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (empty($_SESSION['keranjang_beli'])) {
                        echo "<tr><td colspan='6' style='text-align:center;'>Belum ada item</td></tr>";
                    } else {
                        foreach ($_SESSION['keranjang_beli'] as $id => $isi): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $isi['nama']; ?></td>
                        <td>Rp <?php echo number_format($isi['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $isi['qty']; ?> <?php echo $isi['satuan']; ?></td>
                        <td>Rp <?php echo number_format($isi['subtotal'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="proses_keranjang_beli.php?aksi=hapus&id=<?php echo $id; ?>" 
                               class="btn-delete" style="padding: 5px 10px;">X</a>
                        </td>
                    </tr>
                    <?php endforeach; } ?>
                </tbody>
            </table>

            <hr>

            <form action="proses_simpan_pembelian.php" method="POST" onsubmit="return confirm('Simpan pembelian ini?');">
                
                <div style="display:flex; gap: 15px;">
                    <div class="form-group" style="flex:1;">
                        <label>Pilih Pemasok</label>
                        <select name="pemasok_id" style="width:100%; padding:10px;" required>
                            <option value="">-- Pilih Pemasok --</option>
                            <?php foreach ($pemasok_list as $p): ?>
                                <option value="<?php echo $p['pemasok_id']; ?>">
                                    <?php echo $p['nama_pemasok']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group" style="flex:1;">
                        <label>Tanggal Pembelian</label>
                        <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required style="width:100%; padding:8px;">
                    </div>
                </div>

                <br>
                <button type="submit" class="btn-submit" style="width:100%; font-size:18px; padding:15px;">
                    💾 SIMPAN PEMBELIAN
                </button>
            </form>

        </div>
    </div>

</body>
</html>