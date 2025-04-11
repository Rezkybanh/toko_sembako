<?php
include '../koneksi.php';
include '../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$query = "SELECT transaksi.*,produk.nama_produk, pengguna.username FROM transaksi JOIN produk on transaksi.id_produk = produk.id_produk JOIN  pengguna on transaksi.id_pengguna = pengguna.id_pengguna ORDER BY transaksi.id_transaksi DESC";
$result = mysqli_query($koneksi, $query);
if (!$result) {
    echo "tidak berhasil mengambil data";
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Produk</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: sans-serif;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 8px 12px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2 class="text-center">Data Pengguna</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Nama Pembeli</th>
                <th>Jumlah Beli</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_produk'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['jumlah'] ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                    </tr>
                <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td class="text-center text-danger" colspan="3">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>

<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnable', true);
$dompdf = new Dompdf($options);


$dompdf->load_html($html);
$dompdf->setPaper('A4', 'potrait');
$dompdf->render();

$dompdf->stream("Laporan Data Produk.pdf", ["Attachment" => true]);
exit;
?>