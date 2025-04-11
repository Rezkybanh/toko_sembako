<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'pembeli';

    $cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "Username sudah digunakan";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO pengguna (username, password, role) VALUES ('$username', '$hash', '$role' )";
        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php");
        } else {
            echo "registrasi gagal!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRASI AKUN</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <h2 class="text-center mt-4 mb-3">DAFTAR AKUN</h2>
            <div class="mb-3">
                <input type="text" name="username" placeholder="Username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">DAFTAR</button>
        </form>
        <p class="form-label" style="text-decoration: none;">Sudah punya akun? <a href="index.php">Login Di sini</a></p>
    </div>
</body>

</html>