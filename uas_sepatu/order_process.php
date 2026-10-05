<?php
session_start();
include 'koneksi.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$no_order = "ORD" . date("YmdHis");
$total = 0;

foreach ($_SESSION['cart'] as $id => $sizes) {

    $id = (int) $id;
    $q = mysqli_query($koneksi, "SELECT hargaproduk FROM produk WHERE idproduk = $id");
    $p = mysqli_fetch_assoc($q);

    if (!$p) continue;

    $harga = (int) $p['hargaproduk'];

    foreach ($sizes as $size => $qty) {
        $qty = (int) $qty;
        $total += $harga * $qty;
    }
}

mysqli_query($koneksi, "
    INSERT INTO orders (no_order, total, status)
    VALUES ('$no_order', '$total', 'pending')
");

unset($_SESSION['cart']);

header("Location: order_success.php?order=$no_order");
exit;
