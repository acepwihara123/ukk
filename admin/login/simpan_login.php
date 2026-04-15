<?php
session_start();
include "../../koneksi.php"; // file koneksi yang kamu buat

$nama = $_POST['nama'];
$password = md5($_POST['password']);

$query = $conn->query("SELECT * FROM admin 
                       WHERE nama='$nama' 
                       AND password='$password'");

if ($query->num_rows > 0) {

    $data = $query->fetch_assoc();

    $_SESSION['login'] = true;
    $_SESSION['admin'] = $data['nama'];

    header("Location:../dashboard.php");

} else {

    $_SESSION['error'] = "Nama atau password salah!";
    header("Location: ../login/login.php");
}
?>
