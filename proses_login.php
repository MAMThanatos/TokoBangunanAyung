<?php
session_start();
require 'config/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Cek password
    if ($user && $user['password'] == $password) {
        // Sukses! Simpan data diri
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role']; 
        $_SESSION['status'] = "login";

        header("Location: dashboard.php");
    } else {
        header("Location: login.php?error=true");
    }
}
?>