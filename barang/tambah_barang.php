<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Baru</title>
    <link rel="stylesheet" href="../assets/style.css"> </head>
<body>

    <div class="container">
        <h2>Tambah Barang Baru</h2>

        <form action="proses_tambah.php" method="POST">
            
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" required>
            </div>

            <div class="form-group">
                <label for="satuan">Satuan (Contoh: zak, pcs, btg)</label>
                <input type="text" id="satuan" name="satuan" required>
            </div>

            <div class="form-group">
                <label for="harga_beli">Harga Beli (Modal)</label>
                <input type="decimal" id="harga_beli" name="harga_beli" required>
            </div>

            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="decimal" id="harga_jual" name="harga_jual" required>
            </div>

            <div class="form-group">
                <label for="stok">Stok Awal</label>
                <input type="number" id="stok" name="stok" value="0" required>
            </div>

            <button type="submit" class="btn-submit">Simpan Barang</button>
            <a href="kelola_barang.php" class="btn-cancel">Batal</a>
        </form>

    </div>

</body>
</html>