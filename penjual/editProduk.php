<?php
include '../koneksi.php';
session_start();
if (!$_SESSION['role'] === 'penjual') {
    header("Location: ../index.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
$query = "SELECT * FROM produk WHERE id_produk = $id";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (isset($_POST['ubah_data'])) {
    $nama_produk = $_POST['nama_produk'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = "UPDATE produk SET nama_produk = '$nama_produk' , stok = '$stok' , harga = '$harga' WHERE id_produk = '$id' ";
    $result = mysqli_query($koneksi, $query);
    
    if ($result) {
        header("Location: index.php");
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT PRODUK</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-3 mb-3">Edit data produk</h2>
        <a href="index.php" class="btn btn-warning mb-3"><-- Kembali</a>
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="<?= $data['nama_produk'] ?? '-' ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="stok" id="stok" class="form-control" value="<?= $data['stok'] ?? '-' ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="harga" id="harga" class="form-control" value="<?= $data['harga'] ?? '-' ?>" required>
                    </div>
                    <button type="submit" name="ubah_data" class="btn btn-outline-success w-100">>--Ubah Data Produk--<</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>