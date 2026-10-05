<?php
session_start();
require_once __DIR__ . '/koneksi.php';

if (!isset($_POST['import_produk'])) {
    header("Location: admin/index.php");
    exit;
}

$api_url   = trim($_POST['api_url']);
$api_token = trim($_POST['api_token']);

if (!$api_url) {
    $_SESSION['message'] = "❌ URL API tidak valid";
    header("Location: admin/index.php");
    exit;
}

/* ==========================
   AMBIL DATA API (TOKEN / TANPA TOKEN)
   ========================== */
$ch = curl_init($api_url);

$headers = ["Accept: application/json"];
if (!empty($api_token)) {
    $headers[] = "Authorization: Bearer $api_token";
}

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_TIMEOUT        => 20,
    CURLOPT_SSL_VERIFYPEER => false
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $_SESSION['message'] = "❌ Gagal koneksi API";
    curl_close($ch);
    header("Location: admin/index.php");
    exit;
}
curl_close($ch);

/* ==========================
   DECODE JSON
   ========================== */
$data = json_decode($response, true);

/*
   SUPPORT:
   - API langsung array  [ {...}, {...} ]
   - API object { "data": [ {...} ] }
*/
if (isset($data['data']) && is_array($data['data'])) {
    $data = $data['data'];
}

if (!is_array($data)) {
    $_SESSION['message'] = "❌ Format API tidak dikenali";
    header("Location: admin/index.php");
    exit;
}

/* ==========================
   IMPORT KE DATABASE
   ========================== */
$berhasil = 0;
$gagal    = 0;

foreach ($data as $item) {

    // Mapping fleksibel (cocok untuk banyak API)
    $nama  = $item['namaproduk'] ?? $item['nama'] ?? $item['title'] ?? '';
    $harga = $item['hargaproduk'] ?? $item['harga'] ?? $item['price'] ?? 0;

    if (!$nama || !$harga) {
        $gagal++;
        continue;
    }

    // Bersihkan harga (jika format "1.500.000")
    $harga = preg_replace('/[^0-9]/', '', $harga);

    $stok   = isset($item['stok']) ? (int)$item['stok'] : 0;
    $ukuran = $item['ukuran_sepatu'] ?? $item['ukuran'] ?? '-';
    $desk   = $item['deskripsi'] ?? $item['description'] ?? '';
    $gambar = $item['gambar'] ?? $item['image'] ?? 'default.jpg';

    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO produk
        (namaproduk, hargaproduk, stok, ukuran_sepatu, deskripsi, gambar)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "siisss",
        $nama,
        $harga,
        $stok,
        $ukuran,
        $desk,
        $gambar
    );

    if (mysqli_stmt_execute($stmt)) {
        $berhasil++;
    } else {
        $gagal++;
    }
}

/* ==========================
   REDIRECT LANGSUNG KE INDEX
   ========================== */
$_SESSION['message'] = "✅ Import selesai | Berhasil: $berhasil | Gagal: $gagal";
header("Location: admin/index.php");
exit;
