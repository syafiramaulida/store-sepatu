<?php
include '../koneksi.php';

$url_api = "https://api-teman.com/produk"; // GANTI URL TEMAN

$json = file_get_contents($url_api);
$data = json_decode($json, true);

if (!$data) {
    die("Gagal mengambil data API");
}

$berhasil = 0;

foreach ($data as $p) {

    // Cek duplikat
    $cek = mysqli_query($koneksi,
        "SELECT idproduk FROM produk WHERE namaproduk='".$p['namaproduk']."'"
    );

    if (mysqli_num_rows($cek) == 0) {

        mysqli_query($koneksi, "
        INSERT INTO produk
        (namaproduk, hargaproduk, gambar, deskripsi, kategori, stok, ukuran_sepatu)
        VALUES
        (
            '".$p['namaproduk']."',
            '".$p['hargaproduk']."',
            '".$p['gambar']."',
            '".$p['deskripsi']."',
            '".$p['kategori']."',
            '".$p['stok']."',
            '".$p['ukuran_sepatu']."'
        )
        ");

        $berhasil++;
    }
}

echo "Import berhasil: $berhasil produk";
