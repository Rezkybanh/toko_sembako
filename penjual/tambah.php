<?php
include '../koneksi.php';
session_start();
if (!$_SESSION['role'] === 'penjual') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['tambah_produk'])) {
    $nama_produk = $_POST['nama_produk'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO produk (nama_produk, stok, harga) VALUES ('$nama_produk','$stok','$harga')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal menambah produk!" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAMBAH PRODUK</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-3 mb-3">Tambah data produk</h2>
        <a href="index.php" class="btn btn-warning mb-3"><-- Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" placeholder="Nama Produk" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="stok" id="stok" class="form-control" placeholder="Stok" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="harga" id="harga" class="form-control" placeholder="Harga" required>
                    </div>
                    <button type="submit" name="tambah_produk" class="btn btn-outline-success w-100">>--Tambah Produk--<</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>