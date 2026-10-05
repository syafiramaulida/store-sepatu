<?php
session_start();

/* ======================
   SANITASI INPUT
   ====================== */
$id         = (int) ($_POST['id'] ?? 0);
$action     = $_POST['action'] ?? '';
$size       = $_POST['size'] ?? '';
$size_lama  = $_POST['size_lama'] ?? '';
$size_baru  = $_POST['size_baru'] ?? '';

/* ======================
   VALIDASI DASAR
   ====================== */
$allowed_actions = ['plus', 'minus', 'change_size'];

if ($id <= 0 || !in_array($action, $allowed_actions)) {
    echo 'ERROR: Invalid input';
    exit;
}

/* ======================
   ACTION: PLUS / MINUS
   ====================== */
if (in_array($action, ['plus', 'minus'])) {

    if (!isset($_SESSION['cart'][$id][$size])) {
        echo 'ERROR: Item not found in cart';
        exit;
    }

    if ($action === 'plus') {
        $_SESSION['cart'][$id][$size]++;
    } else {
        $_SESSION['cart'][$id][$size]--;
        if ($_SESSION['cart'][$id][$size] <= 0) {
            unset($_SESSION['cart'][$id][$size]);
            if (empty($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    echo 'OK';
    exit;
}

/* ======================
   ACTION: CHANGE SIZE
   ====================== */
if ($action === 'change_size') {

    if (
        empty($size_lama) ||
        empty($size_baru) ||
        !isset($_SESSION['cart'][$id][$size_lama])
    ) {
        echo 'ERROR: Size not found';
        exit;
    }

    // Jika size sama, tidak perlu diproses
    if ($size_lama === $size_baru) {
        echo 'OK';
        exit;
    }

    $qty = $_SESSION['cart'][$id][$size_lama];

    // Gabungkan qty jika size baru sudah ada
    if (isset($_SESSION['cart'][$id][$size_baru])) {
        $_SESSION['cart'][$id][$size_baru] += $qty;
    } else {
        $_SESSION['cart'][$id][$size_baru] = $qty;
    }

    // Hapus size lama
    unset($_SESSION['cart'][$id][$size_lama]);

    // Jika produk kosong, hapus
    if (empty($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    echo 'OK';
    exit;
}
