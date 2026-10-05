<?php
session_start();

$id   = (int)$_POST['id'];
$size = $_POST['size'];

unset($_SESSION['cart'][$id][$size]);

if (empty($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

header("Location: cart.php");
exit;
