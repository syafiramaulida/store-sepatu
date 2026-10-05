<?php
session_start();
include 'koneksi.php';

$id   = (int)($_POST['id'] ?? 0);
$qty  = (int)($_POST['qty'] ?? 1);
$size = $_POST['size'] ?? '';

if ($id <= 0) {
    echo "ID produk tidak valid";
    exit;
}

if ($size === '') {
    echo "Ukuran belum dipilih";
    exit;
}

// Ambil produk dari database
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE idproduk = $id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    echo "Produk tidak ditemukan";
    exit;
}

// Inisialisasi cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Struktur cart: [id][ukuran]
if (isset($_SESSION['cart'][$id][$size])) {
    $_SESSION['cart'][$id][$size]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id][$size] = [
        'id'    => $id,
        'nama'  => $produk['namaproduk'],
        'harga' => $produk['hargaproduk'],
        'size'  => $size,
        'qty'   => $qty,
        'gambar'=> $produk['gambar']
    ];
}

echo "OK";
