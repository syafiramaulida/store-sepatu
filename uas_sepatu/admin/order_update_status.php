?>
<?php
include '../koneksi.php';

$id = (int)$_POST['id'];
$status = mysqli_real_escape_string($koneksi, $_POST['status']);

$allowed_status = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

if (in_array($status, $allowed_status)) {
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo 'OK';
    } else {
        echo 'Error';
    }
}
?>