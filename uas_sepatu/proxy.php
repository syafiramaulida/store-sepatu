<?php
// proxy.php

// URL API tujuan
$apiUrl = 'https://syafiramaulida.infinityfreeapp.com/uts_store_sepatu/api_produk.php';

// Tambahkan header Content-Type untuk memastikan respons JSON
header('Content-Type: application/json');

// Periksa apakah token dikirimkan
if (!isset($_GET['token'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Token is required']);
    exit;
}

// Ambil token dari query parameter
$token = $_GET['token'];

// Siapkan file sementara untuk menyimpan cookie
$cookieFile = tempnam(sys_get_temp_dir(), 'cookie');
// Kumpulkan log langkah untuk debug (jika diminta)
$logs = [];
$debugMode = isset($_GET['debug']) && $_GET['debug'] == '1';

// Langkah pertama: Dapatkan cookie keamanan
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
$response = curl_exec($ch);
$logs[] = "Step1 raw response length: " . ($response === false ? 'false' : strlen($response));

// Periksa apakah terjadi kesalahan
if (curl_errno($ch)) {
    $err = curl_error($ch);
    curl_close($ch);
    if ($debugMode) {
        echo json_encode(['error' => 'Failed to fetch security cookie', 'details' => $err, 'logs' => $logs]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch security cookie', 'details' => $err]);
    }
    exit;
}
curl_close($ch);

// Langkah kedua: Kirim permintaan dengan cookie
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '?token=' . urlencode($token));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$response = curl_exec($ch);
$logs[] = "Step2 raw response length: " . ($response === false ? 'false' : strlen($response));

// Periksa apakah terjadi kesalahan
if (curl_errno($ch)) {
    $err = curl_error($ch);
    curl_close($ch);
    if ($debugMode) {
        echo json_encode(['error' => 'Failed to fetch API with cookie', 'details' => $err, 'logs' => $logs]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch API with cookie', 'details' => $err]);
    }
    // Hapus file cookie sementara
    @unlink($cookieFile);
    exit;
}

// Ambil kode status HTTP dari respons
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Hapus file cookie sementara
@unlink($cookieFile);

// Jika mode debug, kembalikan JSON berisi logs dan respons mentah (atau coba parse JSON)
if ($debugMode) {
    // Coba decode JSON respons jika ada
    $decoded = json_decode($response, true);
    $out = ['http_code' => $httpCode, 'logs' => $logs];
    if ($decoded !== null) {
        $out['response_json'] = $decoded;
    } else {
        $out['response_text'] = $response;
    }
    echo json_encode($out, JSON_PRETTY_PRINT);
    exit;
}

// Teruskan kode status dan respons ke klien
http_response_code($httpCode);
echo $response;
