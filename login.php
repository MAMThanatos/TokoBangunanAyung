<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Toko Ayung</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body"> 
    
    <div class="login-box">
        <img src="gambar/Gemini_Generated_Image_wnd8p5wnd8p5wnd8.png" alt="Logo Toko Ayung" class="login-logo">

        <h2 class="login-title">Login Sistem</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert">Username atau Password Salah!</div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Masukan username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukan password">
            </div>
            <button type="submit" class="btn-submit">Masuk</button>
        </form>
    </div>

</body>
</html>