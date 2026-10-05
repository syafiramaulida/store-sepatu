<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/* ==========================
   VALIDASI KONEKSI
   ========================== */
if (!isset($koneksi) || !$koneksi) {
    die("❌ Koneksi database tidak ditemukan");
}

/* ==========================
   HAPUS PRODUK
   ========================== */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $stmt = mysqli_prepare($koneksi, "SELECT gambar FROM produk WHERE idproduk = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $produk = mysqli_fetch_assoc($res);

    if ($produk && !filter_var($produk['gambar'], FILTER_VALIDATE_URL)) {
        $file = "../assets/img/produk/" . $produk['gambar'];
        if (file_exists($file)) unlink($file);
    }

    $stmt = mysqli_prepare($koneksi, "DELETE FROM produk WHERE idproduk = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    $_SESSION['message'] = mysqli_stmt_execute($stmt)
        ? "✅ Produk berhasil dihapus"
        : "❌ Gagal menghapus produk";

    header("Location: index.php");
    exit;
}

/* ==========================
   AMBIL DATA PRODUK
   ========================== */
$result = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY idproduk DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Produk</title>

<style>
body{
    font-family:Arial, Helvetica, sans-serif;
    background:#f4f6f9;
}

.container{
    max-width:1400px;
    margin:auto;
    padding:20px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

.btn{
    padding:10px 15px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-weight:bold;
    border:none;
    cursor:pointer;
}

.btn.small{
    padding:6px 10px;
    font-size:13px;
}

.green{background:#28a745}
.black{background:#000}
.blue{background:#0d6efd}
.red{background:#dc3545}
.yellow{background:#ffc107;color:#000}

table{
    width:100%;
    background:#fff;
    border-collapse:collapse;
    border-radius:10px;
    overflow:hidden;
}

th,td{
    padding:14px;
    text-align:center;
}

th{
    background:#000;
    color:#fff;
}

tr:nth-child(even){
    background:#f9f9f9;
}

img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:8px;
}

.stock-high{color:green;font-weight:bold}
.stock-medium{color:orange;font-weight:bold}
.stock-low{color:red;font-weight:bold}

.msg{
    background:#fff;
    padding:15px;
    border-radius:8px;
    margin-bottom:15px;
    font-weight:bold;
}

/* Import Box */
#importBox{
    display:none;
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

#importBox input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
}

/* Aksi */
.aksi{
    display:flex;
    gap:8px;
    justify-content:center;
    align-items:center;
}
</style>

<script>
function toggleImport(){
    const box = document.getElementById('importBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}
</script>

</head>
<body>

<div class="container">

<!-- HEADER -->
<div class="header">
    <div>
        <h2>⚙️ Admin Produk</h2>
        <small>Kelola Produk Sepatu</small>
    </div>
    <div>
        <a href="tambah.php" class="btn green">+ Tambah</a>
        <button onclick="toggleImport()" class="btn blue">🔗 Ambil Produk Teman</button>
        <a href="../index.php" class="btn black">Toko</a>
    </div>
</div>

<!-- FORM IMPORT API -->
<div id="importBox">
    <h3>📥 Import Produk dari Toko Teman</h3>
    <form method="post" action="../import_api.php">
        <input type="url" name="api_url" placeholder="URL API Teman" required>
        <input type="text" name="api_token" placeholder="Token API (Opsional)">
        <button name="import_produk" class="btn blue">Import Produk</button>
    </form>
</div>

<!-- MESSAGE -->
<?php if(isset($_SESSION['message'])): ?>
<div class="msg">
    <?= $_SESSION['message']; unset($_SESSION['message']); ?>
</div>
<?php endif; ?>

<!-- TABLE -->
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Gambar</th>
    <th>Nama</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Ukuran</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php if(mysqli_num_rows($result) > 0): ?>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<?php
$stokClass = "stock-high";
if ($row['stok'] < 10) $stokClass = "stock-low";
elseif ($row['stok'] < 20) $stokClass = "stock-medium";
?>
<tr>
    <td>#<?= $row['idproduk'] ?></td>
    <td>
        <?php if(filter_var($row['gambar'], FILTER_VALIDATE_URL)): ?>
            <img src="<?= $row['gambar'] ?>">
        <?php else: ?>
            <img src="../assets/img/produk/<?= $row['gambar'] ?>">
        <?php endif; ?>
    </td>
    <td style="text-align:left">
        <strong><?= htmlspecialchars($row['namaproduk']) ?></strong><br>
        <small><?= substr(htmlspecialchars($row['deskripsi']),0,50) ?>...</small>
    </td>
    <td>Rp <?= number_format($row['hargaproduk'],0,',','.') ?></td>
    <td class="<?= $stokClass ?>"><?= $row['stok'] ?> pcs</td>
    <td><?= htmlspecialchars($row['ukuran_sepatu']) ?></td>
    <td class="aksi">
        <a href="edit.php?id=<?= $row['idproduk'] ?>" class="btn yellow small">✏️ Edit</a>
        <a href="?delete=<?= $row['idproduk'] ?>" 
           onclick="return confirm('Yakin ingin menghapus produk ini?')" 
           class="btn red small">🗑️ Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="7">Belum ada produk</td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</body>
</html>
