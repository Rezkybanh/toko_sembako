<?php
include '../koneksi.php';
session_start();
if (!$_SESSION['role'] === 'pembeli') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$id_pengguna = $_SESSION['pengguna_id'];

$query = "SELECT * FROM produk";
$result = mysqli_query($koneksi, $query);

$no = 1;

$total_harga = null;
$jumlah_beli = null;
$id_produk_terpilih = null;

if (isset($_POST['lihat_total'])) {
    $jumlah_beli = intval($_POST['jumlah']);
    $id_produk_terpilih = intval($_POST['id_produk']);
    $q_produk = "SELECT * FROM produk WHERE id_produk = $id_produk_terpilih";
    $res_produk = mysqli_query($koneksi, $q_produk);
    $produk = mysqli_fetch_assoc($res_produk);
    $total_harga = $produk['harga'] * $jumlah_beli;
}

if (isset($_POST['beli'])) {
    $jumlah_beli = intval($_POST['jumlah']);
    $id_produk_terpilih = intval($_POST['id_produk']);
    $total_harga = intval($_POST['total_harga']);

    $cekStok = mysqli_query($koneksi, "SELECT stok FROM produk where id_produk = '$id_produk_terpilih' ");
    $dataStok = mysqli_fetch_assoc($cekStok);

    if ($dataStok['stok'] >= $jumlah_beli) {
        mysqli_query($koneksi, "INSERT INTO transaksi (id_pengguna, id_produk, total_harga, jumlah) VALUES ($id_pengguna, $id_produk_terpilih, $total_harga, $jumlah_beli)");
        mysqli_query($koneksi, "UPDATE produk SET stok  = stok - $jumlah_beli WHERE id_produk = $id_produk_terpilih");

        $_SESSION['pesan'] = "Pembelian Berhasil!";
        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TOKO SEMBAKO</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-primary text-center"><?= $_SESSION['pesan'] ?></div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <div class="container">
        <h2 class="mb-3 mt-3 text-center">Halo pembeli</h2>
        <form action="" method="post">
            <button type="submit" class="btn btn-danger mb-3" name="logout">Keluar</button>
        </form>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Jumlah Beli</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>

                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id_produk = $row['id_produk'];
                    ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['nama_produk'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <form action="" method="post" class="d-flex">
                                        <input type="hidden" name="id_produk" value="<?= $id_produk ?>">
                                        <input type="number" name="jumlah" class="form-control" value="<?= ($id_produk == $id_produk_terpilih) ? $jumlah_beli : '' ?>">
                                </td>
                                <td>
                                    <?php
                                    if ($id_produk == $id_produk_terpilih && $total_harga !== null) {
                                        echo "Rp " . number_format($total_harga, 0, ',', '.');
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button type="submit" name="lihat_total" class="btn btn-primary btn-sm">Lihat Total</button>
                                    <?php if ($id_produk == $id_produk_terpilih && $total_harga !== null) { ?>
                                        <input type="hidden" name="total_harga" value="<?= $total_harga ?>">
                                        <button type="submit" name="beli" class="btn btn-success btn-sm">Beli</button>
                                    <?php } ?>
                                    </form>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-danger text-center">Tidak Ada Produk Yang Tersedia</td></tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>