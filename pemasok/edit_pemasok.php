<?php
require '../config/koneksi.php';
$id = $_GET['id'];

$sql = "SELECT * FROM pemasok WHERE pemasok_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$pemasok = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pemasok</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Pemasok</h2>

        <form action="proses_update_pemasok.php" method="POST">
            <input type="hidden" name="pemasok_id" value="<?php echo $pemasok['pemasok_id']; ?>">

            <div class="form-group">
                <label>Nama Pemasok</label>
                <input type="text" name="nama_pemasok" value="<?php echo htmlspecialchars($pemasok['nama_pemasok']); ?>" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="<?php echo htmlspecialchars($pemasok['alamat']); ?>" required>
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_tlp" value="<?php echo htmlspecialchars($pemasok['no_tlp']); ?>" required>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="kelola_pemasok.php" class="btn-cancel">Batal</a>
        </form>
    </div>
</body>
</html>