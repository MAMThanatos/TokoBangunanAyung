<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pemasok Baru</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Pemasok Baru</h2>

        <form action="proses_tambah_pemasok.php" method="POST">
            
            <div class="form-group">
                <label>Nama Pemasok (PT / Toko)</label>
                <input type="text" name="nama_pemasok" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" required>
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_tlp" required>
            </div>

            <button type="submit" class="btn-submit">Simpan Pemasok</button>
            <a href="kelola_pemasok.php" class="btn-cancel">Batal</a>
        </form>
    </div>
</body>
</html>