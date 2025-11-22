<?php
require '../config/koneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM barang WHERE barang_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$barang = $stmt->fetch();

if (!$barang) {
    header("Location: kelola_barang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>

    <div class="container">
        <h2>Edit Barang: <?php echo htmlspecialchars($barang['nama_barang']); ?></h2>

        <form action="proses_update.php" method="POST">
            
            <input type="hidden" name="barang_id" value="<?php echo $barang['barang_id']; ?>">

            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required>
            </div>

            <div class="form-group">
                <label for="satuan">Satuan</label>
                <input type="text" id="satuan" name="satuan" value="<?php echo htmlspecialchars($barang['satuan']); ?>" required>
            </div>

            <div class="form-group">
                <label for="harga_beli">Harga Beli (Modal)</label>
                <input type="decimal" id="harga_beli" name="harga_beli" value="<?php echo htmlspecialchars($barang['harga_beli']); ?>" required>
            </div>

            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="decimal" id="harga_jual" name="harga_jual" value="<?php echo htmlspecialchars($barang['harga_jual']); ?>" required>
            </div>

            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" id="stok" name="stok" value="<?php echo htmlspecialchars($barang['stok']); ?>" required>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="kelola_barang.php" class="btn-cancel">Batal</a>
        </form>

    </div>

</body>
</html>