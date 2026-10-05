<?php
// ========================================
// admin/simpan.php
// ========================================
?>
<?php
include '../koneksi.php';

// Validasi input
$namaproduk     = mysqli_real_escape_string($koneksi, $_POST['namaproduk']);
$hargaproduk    = mysqli_real_escape_string($koneksi, $_POST['hargaproduk']);
$deskripsi      = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$kategori       = mysqli_real_escape_string($koneksi, $_POST['kategori']);
$stok           = mysqli_real_escape_string($koneksi, $_POST['stok']);
$ukuran_sepatu  = mysqli_real_escape_string($koneksi, $_POST['ukuran_sepatu']);

// Upload gambar
$gambar = '';
if (!empty($_FILES['gambar']['name'])) {
    $targetDir = '../assets/img/produk/';
    
    // Buat folder jika belum ada
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileExt = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (in_array($fileExt, $allowedExt)) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $targetDir . $gambar);
    }
}

// Simpan ke database
$query = "INSERT INTO produk 
          (namaproduk, hargaproduk, deskripsi, kategori, stok, ukuran_sepatu, gambar)
          VALUES ('$namaproduk', '$hargaproduk', '$deskripsi', '$kategori', '$stok', '$ukuran_sepatu', '$gambar')";

if (mysqli_query($koneksi, $query)) {
    header("Location: produk.php");
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>

<?php