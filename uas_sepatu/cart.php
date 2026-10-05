<?php 
session_start(); 
include 'koneksi.php'; 
$items = []; $total = 0; 
if (!empty($_SESSION['cart'])) { 
    $ids = array_keys($_SESSION['cart']); 
    $in = implode(',', array_fill(0, count($ids), '?')); 
    $sql = "SELECT * FROM produk WHERE idproduk IN ($in)"; 
    $stmt = mysqli_prepare($koneksi, $sql); 
    mysqli_stmt_bind_param($stmt, str_repeat('i', count($ids)), ...$ids); 
    mysqli_stmt_execute($stmt); $res = mysqli_stmt_get_result($stmt); 
    
while ($p = mysqli_fetch_assoc($res)) {

    if (!isset($_SESSION['cart'][$p['idproduk']])) continue;

    foreach ($_SESSION['cart'][$p['idproduk']] as $size => $qty) {

        $qty = (int) $qty;
        $harga = (int) $p['hargaproduk'];

        $sub = $qty * $harga;
        $total += $sub;

        $items[] = [
            'id'    => $p['idproduk'],
            'nama'  => $p['namaproduk'],
            'harga'=> $harga,
            'size' => $size,
            'qty'  => $qty,
            'sub'  => $sub
        ];
    }
}

}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang Belanja</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

h2 {
    color: #333;
    margin-bottom: 30px;
    font-size: 32px;
    text-align: center;
    position: relative;
    padding-bottom: 15px;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
}

.empty-cart {
    text-align: center;
    padding: 60px 20px;
    color: #999;
    font-size: 18px;
}

.empty-cart::before {
    content: '🛒';
    display: block;
    font-size: 80px;
    margin-bottom: 20px;
    opacity: 0.3;
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 30px;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

th {
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

td {
    padding: 20px 12px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

tbody tr {
    background: white;
    transition: all 0.3s ease;
}

tbody tr:hover {
    background: #f8f9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

tbody tr:last-child td {
    border-bottom: none;
}

select {
    padding: 8px 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    background: white;
    transition: all 0.3s ease;
    min-width: 70px;
}

select:hover {
    border-color: #667eea;
}

select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.qty-control {
    display: inline-flex;
    align-items: center;
    gap: 12px;
}

button {
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.qty-control button {
    width: 32px;
    height: 32px;
    padding: 0;
    background: #667eea;
    color: white;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qty-control button:hover {
    background: #5568d3;
    transform: scale(1.1);
}

.qty-control button:active {
    transform: scale(0.95);
}

td form[action="cart_remove.php"] button {
    background: #ff4757;
    color: white;
    padding: 8px 16px;
}

td form[action="cart_remove.php"] button:hover {
    background: #ee5a6f;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 71, 87, 0.3);
}

.total-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8eaff 100%);
    padding: 25px 30px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.total-section h3 {
    color: #333;
    font-size: 24px;
    text-align: right;
}

.total-section h3 span {
    color: #667eea;
    font-weight: 700;
}

.checkout-form {
    text-align: center;
}

.checkout-actions {
    display: flex;
    justify-content: space-between; /* kiri & kanan */
    gap: 20px;
    margin-top: 20px;
}

.checkout-actions .checkout-form {
    flex: 1;
}

.checkout-form button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 60px;
    font-size: 16px;
    border-radius: 50px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.checkout-form button:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
}

.checkout-form button:active {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .container {
        padding: 20px;
        border-radius: 10px;
    }
    
    table {
        font-size: 14px;
    }
    
    th, td {
        padding: 10px 6px;
    }
    
    h2 {
        font-size: 24px;
    }
    
    .checkout-form button {
        padding: 14px 40px;
        width: 100%;
    }
}
</style>
</head>
<body>

<div class="container">
<h2>🛒 Keranjang Belanja</h2>

<?php if (empty($items)): ?>
<div class="empty-cart">
    <p>Keranjang kosong</p>
</div>
<?php else: ?>

<table>
<thead>
<tr>
<th>Produk</th>
<th>Size</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach ($items as $i): ?>
<tr>
<td><strong><?= htmlspecialchars($i['nama']) ?></strong></td>

<td>
<form method="post" action="cart_action.php">
<input type="hidden" name="id" value="<?= $i['id'] ?>">
<input type="hidden" name="size_lama" value="<?= $i['size'] ?>">
<input type="hidden" name="action" value="change_size">
<select name="size_baru" onchange="this.form.submit()">
<?php foreach(['38','39','40','41','42'] as $s): ?>
<option value="<?= $s ?>" <?= $s==$i['size']?'selected':'' ?>><?= $s ?></option>
<?php endforeach ?>
</select>
</form>
</td>

<td>
<div class="qty-control">
<form method="post" action="cart_action.php" style="display:inline">
<input type="hidden" name="id" value="<?= $i['id'] ?>">
<input type="hidden" name="size" value="<?= $i['size'] ?>">
<input type="hidden" name="action" value="minus">
<button>-</button>
</form>

<span><strong><?= $i['qty'] ?></strong></span>

<form method="post" action="cart_action.php" style="display:inline">
<input type="hidden" name="id" value="<?= $i['id'] ?>">
<input type="hidden" name="size" value="<?= $i['size'] ?>">
<input type="hidden" name="action" value="plus">
<button>+</button>
</form>
</div>
</td>

<td>Rp <?= number_format($i['harga'],0,',','.') ?></td>
<td><strong>Rp <?= number_format($i['sub'],0,',','.') ?></strong></td>

<td>
<form method="post" action="cart_remove.php">
<input type="hidden" name="id" value="<?= $i['id'] ?>">
<input type="hidden" name="size" value="<?= $i['size'] ?>">
<button>Hapus</button>
</form>
</td>
</tr>
<?php endforeach ?>
</tbody>
</table>

<div class="total-section">
<h3>Total: <span>Rp <?= number_format($total,0,',','.') ?></span></h3>
</div>
<div class="checkout-actions">

    <form method="post" action="index.php" class="checkout-form">
        <button type="submit">🛍️ Kembali ke Toko</button>
    </form>

    <form method="post" action="order_process.php" class="checkout-form">
        <button type="submit">🛍️ Checkout Sekarang</button>
    </form>

</div>

<?php endif; ?>


</body>
</html>