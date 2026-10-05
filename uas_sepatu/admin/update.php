?>
<?php
include '../koneksi.php';

$id             = (int)$_POST['idproduk'];
$namaproduk     = mysqli_real_escape_string($koneksi, $_POST['namaproduk']);
$hargaproduk    = mysqli_real_escape_string($koneksi, $_POST['hargaproduk']);
$deskripsi      = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$kategori       = mysqli_real_escape_string($koneksi, $_POST['kategori']);
$stok           = mysqli_real_escape_string($koneksi, $_POST['stok']);
$ukuran_sepatu  = mysqli_real_escape_string($koneksi, $_POST['ukuran_sepatu']);
$gambar_lama    = $_POST['gambar_lama'];

$gambar = $gambar_lama;

// Jika upload gambar baru
if (!empty($_FILES['gambar']['name'])) {
    $targetDir = '../assets/img/produk/';
    $fileExt = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (in_array($fileExt, $allowedExt)) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetDir . $gambar)) {
            // Hapus gambar lama
            if ($gambar_lama && file_exists($targetDir . $gambar_lama)) {
                unlink($targetDir . $gambar_lama);
            }
        }
    }
}

$query = "UPDATE produk SET
          namaproduk='$namaproduk',
          hargaproduk='$hargaproduk',
          deskripsi='$deskripsi',
          kategori='$kategori',
          stok='$stok',
          ukuran_sepatu='$ukuran_sepatu',
          gambar='$gambar'
          WHERE idproduk=$id";

if (mysqli_query($koneksi, $query)) {
    header("Location: produk.php");
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>

<?php