<?php
session_start();
include "../koneksi.php";

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

/* ===============================
   AMBIL DATA STATISTIK
================================ */

// TOTAL USER
$total_users = 0;
$qUser = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");

if ($qUser) {
    $data = mysqli_fetch_assoc($qUser);
    $total_users = $data['total'] ?? 0;
}

// TOTAL TRANSAKSI
$total_transactions = 0;
$qTrans = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan");

if ($qTrans) {
    $data = mysqli_fetch_assoc($qTrans);
    $total_transactions = $data['total'] ?? 0;
}

// TOTAL PENDAPATAN (STATUS SELESAI)
$total_pendapatan = 0;
$qUang = mysqli_query($conn,
    "SELECT SUM(total) as total 
     FROM pemesanan
     WHERE status='selesai'"
);

if ($qUang) {
    $data = mysqli_fetch_assoc($qUang);
    $total_pendapatan = $data['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Statistik</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{
    font-family:'Inter',sans-serif;
    overflow:hidden;
}
.content-main{
    height:calc(100vh - 80px);
    overflow-y:auto;
}
</style>
</head>

<body class="bg-gray-50">

<div class="min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white shadow-sm">
<div class="px-6 py-4 flex justify-between items-center">

<h1 class="text-2xl font-bold text-gray-800">
<i class="fas fa-chart-line text-blue-500 mr-2"></i>
Dashboard Statistik
</h1>

<a href="login/logout.php"
class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
<i class="fas fa-sign-out-alt"></i> Logout
</a>

</div>
</header>

<div class="flex flex-1 overflow-hidden">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-sm p-6 hidden md:block">

<ul class="space-y-2">

<li>
<a href="dashboard.php"
class="flex items-center space-x-3 p-3 rounded-lg bg-blue-500 text-white">
<i class="fas fa-home"></i>
<span>Dashboard</span>
</a>
</li>

<li>
<a href="../admin/list/kamera.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-camera"></i>
<span>Kamera</span>
</a>
</li>



<!-- MENU TRANSAKSI -->
<li>
<a href="../admin/verifikasi/pemesanan.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-shopping-cart"></i>
<span>Transaksi</span>
</a>
</li>

<!-- ✅ MENU LAPORAN (BARU) -->
<li>
<a href="../admin/laporan/laporan.php"
class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100">
<i class="fas fa-chart-line"></i>
<span>Laporan</span>
</a>
</li>

</ul>

</aside>

<!-- MAIN -->
<main class="flex-1 p-6 content-main">

<h2 class="text-xl font-bold mb-6">
Selamat Datang, Admin 👋
</h2>

<!-- CARD STATISTIK -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

<!-- TOTAL USER -->
<div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
<p class="text-gray-500 text-sm">Total Pengguna</p>

<h3 class="text-3xl font-bold mt-2">
<?= number_format($total_users) ?>
</h3>
</div>

<!-- TOTAL PENDAPATAN -->
<div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
<p class="text-gray-500 text-sm">Total Pendapatan</p>

<h3 class="text-3xl font-bold mt-2">
Rp <?= number_format($total_pendapatan,0,',','.') ?>
</h3>
</div>

<!-- TOTAL TRANSAKSI -->
<div class="bg-white rounded-xl  shadow p-6 border-l-4 border-yellow-500">
<p class="text-gray-500 text-sm">Total Transaksi</p>

<h3 class="text-3xl font-bold mt-2">
<?= number_format($total_transactions) ?>
</h3>
</div>

</div>

<!-- FOOTER -->
<footer class="mt-10 text-center text-gray-500 text-sm">
© <?= date('Y') ?> Dashboard Sistem Rental Kamera
</footer>

</main>
</div>
</div>

</body>
</html>