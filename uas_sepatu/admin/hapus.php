?>
<?php
include '../koneksi.php';

$id = (int)$_GET['id'];

// Ambil data produk untuk menghapus gambar
$data = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE idproduk=$id");
$produk = mysqli_fetch_assoc($data);

if ($produk) {
    // Hapus gambar jika ada
    if (!empty($produk['gambar'])) {
        $path = "../assets/img/produk/" . $produk['gambar'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    // Hapus data produk
    mysqli_query($koneksi, "DELETE FROM produk WHERE idproduk=$id");
}

header("Location: produk.php");
exit;
?>