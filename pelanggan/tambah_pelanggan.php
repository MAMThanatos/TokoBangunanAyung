<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan Baru</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h2>Tambah Pelanggan Baru</h2>
        <hr>

        <form action="proses_tambah_pelanggan.php" method="POST">
            
            <div class="form-group">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama_pelangkan" required placeholder="Nama Lengkap">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" required placeholder="Alamat Lengkap">
            </div>

            <div class="form-group">
                <label>No. Telepon / WA</label>
                <input type="text" name="no_tlp" required placeholder="Contoh: 08123456789">
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-submit">Simpan Data</button>
                <a href="kelola_pelanggan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>