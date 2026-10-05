<?php
session_start();
include 'koneksi.php';

/* =====================
   CART COUNT
   ===================== */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_count = 0;
foreach ($_SESSION['cart'] as $items) {
    $cart_count += count($items);
}

/* =====================
   FILTER INPUT
   ===================== */
$search   = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$min      = $_GET['min'] ?? '';
$max      = $_GET['max'] ?? '';

$where = [];

if ($search != '') {
    $s = mysqli_real_escape_string($koneksi, $search);
    $where[] = "namaproduk LIKE '%$s%'";
}

if ($kategori != '') {
    $k = mysqli_real_escape_string($koneksi, $kategori);
    $where[] = "kategori='$k'";
}

if ($min != '' && $max != '') {
    $where[] = "hargaproduk BETWEEN ".(int)$min." AND ".(int)$max;
}

$whereSQL = $where ? "WHERE ".implode(" AND ", $where) : "";

/* =====================
   DATA PRODUK
   ===================== */
$data = mysqli_query($koneksi, "SELECT * FROM produk $whereSQL ORDER BY idproduk DESC");

/* =====================
   DATA KATEGORI
   ===================== */
$kategoriData = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM produk");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UTS Store Sepatu</title>

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:'Segoe UI',Tahoma,Verdana,sans-serif;
    background:linear-gradient(135deg,#667eea,#764ba2);
    min-height:100vh;
    padding:20px;
}
.container{max-width:1400px;margin:auto}

/* HEADER */
header{
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,.2);
    margin-bottom:25px;
    text-align:center;
    position:relative;
}
header h1{font-size:2.4em}
header p{color:#666}

/* CART */
.cart-button{
    position:absolute;
    top:25px;
    right:25px;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    padding:12px 22px;
    border-radius:50px;
    text-decoration:none;
    display:flex;
    gap:10px;
    font-weight:600;
}
.cart-badge{
    background:#ff4757;
    padding:3px 8px;
    border-radius:50%;
    font-size:.8em;
}

/* FILTER */
.filter-box{
    background:#fff;
    padding:20px;
    border-radius:14px;
    margin-bottom:25px;
    box-shadow:0 6px 20px rgba(0,0,0,.15);
}
.filter-form{
    display:grid;
    grid-template-columns:2fr 1fr 1fr 1fr auto;
    gap:12px;
}
.filter-form input,
.filter-form select,
.filter-form button{
    padding:12px;
    border-radius:8px;
    border:1.5px solid #ddd;
}
.filter-form button{
    background:#667eea;
    color:#fff;
    border:none;
    cursor:pointer;
    font-weight:600;
}

/* GRID */
.produk-wrapper{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:24px;
}

/* CARD */
.produk-card{
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,.15);
    transition:.3s;
    cursor:pointer;
}
.produk-card:hover{
    transform:translateY(-8px);
}
.produk-card img{
    width:100%;
    height:220px;
    object-fit:cover;
}
.produk-info{padding:18px}
.produk-info h4{
    min-height:48px;
    margin-bottom:6px;
}
.harga{
    font-size:1.35em;
    font-weight:700;
    color:#667eea;
}

/* STOK */
.stok{
    display:inline-block;
    padding:5px 14px;
    border-radius:20px;
    font-size:.85em;
    margin:8px 0;
}
.stok-habis{background:#fee;color:#c00}
.stok-terbatas{background:#fff3cd;color:#856404}
.stok-aman{background:#d4edda;color:#155724}

/* SIZE */
.size-select{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1.5px solid #ddd;
    margin:10px 0;
}

/* BUTTON */
.button-group{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}
.btn-detail,
.btn-cart{
    padding:11px;
    border-radius:8px;
    font-weight:600;
    text-align:center;
    cursor:pointer;
}
.btn-detail{background:#6c757d;color:#fff;text-decoration:none}
.btn-cart{background:#28a745;color:#fff;border:none}
.btn-disable{
    background:#aaa;
    padding:11px;
    border-radius:8px;
    text-align:center;
    margin-top:10px;
}

/* RESPONSIVE */
@media(max-width:1024px){.produk-wrapper{grid-template-columns:repeat(3,1fr)}}
@media(max-width:768px){
    .filter-form{grid-template-columns:1fr}
    .produk-wrapper{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:480px){
    .produk-wrapper{grid-template-columns:1fr}
}
</style>
</head>

<body>
<div class="container">

<header>
<a href="cart.php" class="cart-button">
🛒 Keranjang
<?php if($cart_count>0): ?><span class="cart-badge"><?= $cart_count ?></span><?php endif; ?>
</a>
<h1>👟 UAS Store Sepatu</h1>
<p>Koleksi Sepatu Berkualitas dengan Harga Terbaik</p>
</header>

<!-- FILTER -->
<div class="filter-box">
<form method="GET" class="filter-form">
<input type="text" name="search" placeholder="Cari sepatu..." value="<?= htmlspecialchars($search) ?>">
<select name="kategori">
<option value="">Semua Kategori</option>
<?php while($k=mysqli_fetch_assoc($kategoriData)): ?>
<option value="<?= $k['kategori'] ?>" <?= $kategori==$k['kategori']?'selected':'' ?>>
<?= $k['kategori'] ?>
</option>
<?php endwhile; ?>
</select>
<input type="number" name="min" placeholder="Harga Min" value="<?= $min ?>">
<input type="number" name="max" placeholder="Harga Max" value="<?= $max ?>">
<button type="submit">Filter</button>
</form>
</div>

<!-- PRODUK -->
<div class="produk-wrapper">
<?php while($p=mysqli_fetch_assoc($data)):
$stok=(int)$p['stok'];
$ukuran=!empty($p['ukuran_sepatu'])?explode(',',$p['ukuran_sepatu']):[];
?>
<div class="produk-card" onclick="goDetail(<?= $p['idproduk'] ?>)">
<img src="assets/img/produk/<?= htmlspecialchars($p['gambar']) ?>">

<div class="produk-info">
<h4><?= htmlspecialchars($p['namaproduk']) ?></h4>
<div class="harga">Rp <?= number_format($p['hargaproduk'],0,',','.') ?></div>

<?php if($stok==0): ?>
<span class="stok stok-habis">HABIS</span>
<?php elseif($stok<5): ?>
<span class="stok stok-terbatas">STOK TERBATAS</span>
<?php else: ?>
<span class="stok stok-aman">TERSEDIA</span>
<?php endif; ?>

<?php if($stok>0 && $ukuran): ?>
<select class="size-select" id="size-<?= $p['idproduk'] ?>" onclick="event.stopPropagation();">
<option value="">-- Pilih Ukuran --</option>
<?php foreach($ukuran as $u): ?>
<option value="<?= trim($u) ?>"><?= trim($u) ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>

<?php if($stok>0): ?>
<div class="button-group">
<a class="btn-detail" href="detail_produk.php?id=<?= $p['idproduk'] ?>" onclick="event.stopPropagation();">Detail</a>
<button class="btn-cart" onclick="event.stopPropagation(); addToCart(<?= $p['idproduk'] ?>)">Keranjang</button>
</div>
<?php else: ?>
<span class="btn-disable">Tidak Tersedia</span>
<?php endif; ?>
</div>
</div>
<?php endwhile; ?>
</div>

</div>

<script>
function goDetail(id){
    window.location.href="detail_produk.php?id="+id;
}
function addToCart(id){
    const size=document.getElementById('size-'+id);
    if(size && !size.value){alert('Pilih ukuran terlebih dahulu');return;}
    const xhr=new XMLHttpRequest();
    xhr.open("POST","cart_add.php",true);
    xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhr.send("id="+id+"&qty=1&size="+(size?size.value:""));
    xhr.onload=()=>location.reload();
}
</script>
</body>
</html>
