<?php
include "../../koneksi.php";

$tgl_awal  = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';

$where = "";

if ($tgl_awal && $tgl_akhir) {
    $where = "WHERE tanggal_mulai BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

$query = $conn->query("
SELECT * FROM pemesanan
$where
ORDER BY id DESC
");

$total_pesanan = $query->num_rows;

// total pendapatan
$result = $conn->query("
SELECT SUM(total) as total
FROM pemesanan $where
");
$rowTotal = $result->fetch_assoc();
$total_pendapatan = $rowTotal['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pemesanan</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ================= PRINT STYLE ================= */
@media print {

body{
    background:white !important;
    font-size:12px;
}

/* sembunyikan sidebar & tombol */
aside,
.no-print{
    display:none !important;
}

/* konten full */
main{
    padding:0 !important;
}

/* hilangkan shadow & card */
.shadow{
    box-shadow:none !important;
}

.bg-white{
    background:white !important;
}

/* table laporan */
table{
    border-collapse:collapse;
    width:100%;
}

table th,
table td{
    border:1px solid black;
    padding:6px;
}

/* header print tampil */
.print\\:block{
    display:block !important;
}

/* judul tengah */
h1,h2,p{
    text-align:center;
}

/* hilangkan warna tailwind */
.bg-gray-200,
.bg-blue-100,
.bg-green-100{
    background:white !important;
}

}
</style>

</head>

<body class="bg-gray-100">

<!-- LAYOUT -->
<div class="flex min-h-screen">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-sm p-6 hidden md:block">

<ul class="space-y-2">

<li>
<a href="../dashboard.php"
class="flex items-center space-x-3 p-3 rounded-lg bg-blue-500 text-white">
<i class="fas fa-home"></i>
<span>Dashboard</span>
</a>
</li>

<li>
<a href="../list/kamera.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-camera"></i>
<span>Kamera</span>
</a>
</li>



<!-- MENU TRANSAKSI -->
<li>
<a href="../verifikasi/pemesanan.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-shopping-cart"></i>
<span>Transaksi</span>
</a>
</li>

<!-- ✅ MENU LAPORAN (BARU) -->
<li>
<a href="../laporan/laporan.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-chart-line"></i>
<span>Laporan</span>
</a>
</li>

</ul>

</aside>
<!-- ================= END SIDEBAR ================= -->


<!-- ================= CONTENT ================= -->
<main class="flex-1 p-8">

<div class="bg-white p-6 rounded-xl shadow print:shadow-none">

<h2 class="text-2xl font-bold mb-6">
Laporan Pemesanan
</h2>

<!-- FILTER -->
<form method="GET" class="flex flex-wrap gap-3 mb-6 no-print">

<input type="date" name="tgl_awal"
value="<?= $tgl_awal ?>"
class="border p-2 rounded">

<input type="date" name="tgl_akhir"
value="<?= $tgl_akhir ?>"
class="border p-2 rounded">




<button type="button"
onclick="window.print()"
class="bg-green-600 text-white px-4 py-2 rounded">
Print
</button>

</form>

<!-- STATISTIK -->


    <p class="mt-2">
        Periode :
        <?= $tgl_awal ? $tgl_awal : 'Semua Tanggal' ?>
        s/d
        <?= $tgl_akhir ? $tgl_akhir : 'Sekarang' ?>
    </p>
</div>

<!-- TABLE -->
<div class="overflow-x-auto">

<table class="w-full border text-sm">

<thead class="bg-gray-200">
<tr class="text-center">
<th class="p-2">ID</th>
<th>Nama</th>
<th>Tanggal</th>
<th>Durasi</th>
<th>Pembayaran</th>
<th>Total</th>
<th>Status</th>
</tr>
</thead>

<tbody>

<?php while($row = $query->fetch_assoc()): ?>
<tr class="border-b text-center hover:bg-gray-50">

<td class="p-2"><?= $row['id'] ?></td>
<td><?= $row['nama'] ?></td>
<td><?= $row['tanggal_mulai'] ?></td>
<td><?= $row['durasi'] ?> Hari</td>
<td><?= $row['pembayaran'] ?></td>
<td class="font-semibold">
Rp <?= number_format($row['total']) ?>
</td>
<td class="capitalize"><?= $row['status'] ?></td>

</tr>
<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</main>
<!-- ================= END CONTENT ================= -->

</div>

</body>
</html>