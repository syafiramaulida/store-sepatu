<?php
// =======================================
// HEADER JSON + CORS
// =======================================
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// =======================================
// KONEKSI DATABASE (FIX PATH)
// =======================================
include __DIR__ . '/../koneksi.php';

if (!isset($koneksi)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal"
    ]);
    exit();
}

// =======================================
// TOKEN
// =======================================
$valid_token = "1234567890abcdef";

// =======================================
// AMBIL BEARER TOKEN
// =======================================
function getBearerToken() {
    $headers = null;

    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        foreach ($requestHeaders as $key => $value) {
            if (strtolower($key) === 'authorization') {
                $headers = trim($value);
                break;
            }
        }
    }

    if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
        return $matches[1];
    }
    return null;
}

// =======================================
// VALIDASI TOKEN
// =======================================
$token = getBearerToken();

if ($token !== $valid_token) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized: Invalid or missing token"
    ]);
    exit();
}

// =======================================
// API PRODUK
// =======================================
$response = [];

if (isset($_GET['idproduk'])) {

    $id = intval($_GET['idproduk']);
    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE idproduk = $id");

    if (mysqli_num_rows($query) > 0) {
        $response['status'] = 'success';
        $response['data']   = mysqli_fetch_assoc($query);
    } else {
        $response['status']  = 'error';
        $response['message'] = 'Produk tidak ditemukan';
    }

} else {

    $query = mysqli_query($koneksi, "SELECT * FROM produk");
    $data  = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    $response['status'] = 'success';
    $response['data']   = $data;
}

// =======================================
// OUTPUT
// =======================================
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
