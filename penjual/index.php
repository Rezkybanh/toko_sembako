<?php
include '../koneksi.php';
session_start();
if (!$_SESSION['role'] === 'penjual') {
    header("Location: ../index.php");
    exit();
}



$query = "SELECT * FROM produk";
$result = mysqli_query($koneksi, $query);

if (isset($_GET['hapus_produk'])) {
    $id_prduk = $_GET['hapus_produk'];
    $query = "DELETE FROM produk where id_produk = '$id_prduk'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: index.php");
        exit();
    }
}

if (isset($_GET['hapusTransaksi'])) {
    $id_transaksi = $_GET['hapusTransaksi'];
    $query = "DELETE FROM transaksi where id_transaksi = '$id_transaksi' ";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: index.php");
        exit();
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$queryTransaksi = "SELECT transaksi.*,produk.nama_produk, pengguna.username FROM transaksi JOIN produk on transaksi.id_produk = produk.id_produk JOIN  pengguna on transaksi.id_pengguna = pengguna.id_pengguna ORDER BY transaksi.id_transaksi DESC";
$resultTransaksi = mysqli_query($koneksi, $queryTransaksi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PENJUAL</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="mb-3">
            <h1 class="text-center mt-4  mb-4">Halo Penjual</h1>
            <form action="" method="post">
                <button type="submit" class="btn btn-danger ms-3" name="logout"><--Keluar</button>
            </form>
        </div>
        <div class="mb-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-3">Kelola Produk</h2>
                    <a href="tambah.php" class="btn btn-outline-primary mb-3">>--Tambah Produk--<</a>
                            <table class=" table table-striped text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row['nama_produk'] ?? '-' ?></td>
                                            <td><?= $row['stok'] ?? '-' ?></td>
                                            <td><?= $row['harga'] ?? '-' ?></td>
                                            <td>
                                                <a href="editProduk.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning ">Ubah Data</a>
                                                <a href="index.php?hapus_produk=<?= $row['id_produk'] ?>" class="btn btn-danger ">Hapus Data</a>
                                            </td>
                                </tr>
                            <?php
                                        }
                                    } else {
                            ?>
                            <tr>
                                <td colspan="5" class="text-center text-danger">
                                    Tidak ada produk tersedia, silahkan tambah produk!
                                </td>
                            </tr>
                        <?php } ?>
                            </table>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="text-center mb-3">Riwayat Transaksi Pembelian</h2>
                    <div class="col text-end mb-3">
                        <a href="laporanProduk.php" class="btn btn-primary">>--Unduh Laporan Produk--<</a>
                        <a href="laporanTransaksi.php" class="btn btn-success">>--Unduh Laporan Transaksi--<</a>
                    </div>
                    <table class="table table-striped">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Nama Pembeli</th>
                            <th>Jumlah Beli</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($resultTransaksi)) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['nama_produk'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['jumlah'] ?></td>
                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                                <td><a href="index.php?hapusTransaksi=<?= $row['id_transaksi'] ?>" class="btn btn-danger ">Hapus transaksi</a></td>
                            </tr>
                        <?php } ?>
                    </table>

                </div>
            </div>
        </div>
    </div>
</body>

</html>