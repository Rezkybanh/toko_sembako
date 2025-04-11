<?php
include 'koneksi.php';
session_start();

if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM  pengguna WHERE username = '$username' ";
    $result = mysqli_query($koneksi, $query);
    $pengguna = mysqli_fetch_assoc($result);

    if ($pengguna && password_verify($password, $pengguna['password'])) {
        //menyimpan sesi pengguna
        $_SESSION['pengguna_id'] = $pengguna['id_pengguna'];
        $_SESSION['role'] = $pengguna['role'];

        //redirect berdasarkan role
        if ($pengguna['role'] === 'penjual') {
            header("Location: penjual/index.php");
        } else {
            header("Location: pembeli/index.php");
        }
        exit();
    } else {
        echo "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOKO SEMBAKO</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <h2 class="text-center mt-4 mb-3">LOGIN</h2>
            <div class="mb-3">
                <input type="text" name="username" placeholder="Username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">MASUK</button>
        </form>
        <p class="form-label" style="text-decoration: none;">Belum Punya Akun? <a href="register.php">Daftar Di Sini</a></p>
    </div>
</body>

</html>