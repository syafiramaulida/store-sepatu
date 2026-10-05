<?php
session_start();

$id     = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($id <= 0) exit('ERROR');

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ===========================
   PLUS / MINUS
   =========================== */
if ($action === 'plus' || $action === 'minus') {

    $size = $_POST['size'] ?? '';

    if (!isset($_SESSION['cart'][$id]) || !is_array($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = [];
    }

    if (
        !isset($_SESSION['cart'][$id][$size]) ||
        !is_numeric($_SESSION['cart'][$id][$size])
    ) {
        $_SESSION['cart'][$id][$size] = 0;
    }

    if ($action === 'plus') {
        $_SESSION['cart'][$id][$size] = (int)$_SESSION['cart'][$id][$size] + 1;
    } else {
        $_SESSION['cart'][$id][$size] = (int)$_SESSION['cart'][$id][$size] - 1;

        if ($_SESSION['cart'][$id][$size] <= 0) {
            unset($_SESSION['cart'][$id][$size]);
        }
    }

    if (empty($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

/* ===========================
   GANTI SIZE
   =========================== */
if ($action === 'change_size') {

    $lama = $_POST['size_lama'] ?? '';
    $baru = $_POST['size_baru'] ?? '';

    if (
        !isset($_SESSION['cart'][$id][$lama]) ||
        !is_numeric($_SESSION['cart'][$id][$lama])
    ) {
        exit('ERROR');
    }

    $qty = (int) $_SESSION['cart'][$id][$lama];

    if (
        !isset($_SESSION['cart'][$id][$baru]) ||
        !is_numeric($_SESSION['cart'][$id][$baru])
    ) {
        $_SESSION['cart'][$id][$baru] = 0;
    }

    $_SESSION['cart'][$id][$baru] =
        (int)$_SESSION['cart'][$id][$baru] + $qty;

    unset($_SESSION['cart'][$id][$lama]);
}

header("Location: cart.php");
exit;
