<?php
require '../config/koneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM pelanggan WHERE pelanggan_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$pelanggan = $stmt->fetch();

if (!$pelanggan) {
    header("Location: kelola_pelanggan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h2>Edit Data Pelanggan</h2>
        <hr>

        <form action="proses_update_pelanggan.php" method="POST">
            <input type="hidden" name="pelanggan_id" value="<?php echo $pelanggan['pelanggan_id']; ?>">

            <div class="form-group">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama_pelangkan" value="<?php echo htmlspecialchars($pelanggan['nama_pelangkan']); ?>" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="<?php echo htmlspecialchars($pelanggan['alamat']); ?>" required>
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_tlp" value="<?php echo htmlspecialchars($pelanggan['no_tlp']); ?>" required>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
                <a href="kelola_pelanggan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>