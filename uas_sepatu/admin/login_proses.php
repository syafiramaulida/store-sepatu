<?php
session_start();
include '../koneksi.php';

$username = $_POST['username'];
$password = md5($_POST['password']);

$query = mysqli_query($koneksi,
    "SELECT * FROM admin 
     WHERE username='$username' AND password='$password'"
);

$data = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['admin'] = $data['username'];
    header("Location: dashboard.php");
} else {
    echo "<script>alert('Login gagal');location='login.php';</script>";
}
