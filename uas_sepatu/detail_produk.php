<?php
// detail_produk.php
include 'koneksi.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// Ambil data produk
$query = "SELECT * FROM produk WHERE idproduk = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

if (!$data || mysqli_num_rows($data) == 0) {
    echo "<!DOCTYPE html>
    <html><head><meta charset='UTF-8'><title>Produk Tidak Ditemukan</title></head>
    <body style='font-family: Arial; text-align: center; padding: 50px;'>
        <h2>❌ Produk tidak ditemukan</h2>
        <p><a href='index.php' style='color: #667eea;'>← Kembali ke Toko</a></p>
    </body></html>";
    exit;
}

$p = mysqli_fetch_assoc($data);
$stok = (int)$p['stok'];
$gambarPath = "assets/img/produk/" . $p['gambar'];

/* =======================
   TAMBAHAN LOGIKA UKURAN
======================= */
$ukuranList = [];
if (!empty($p['ukuran_sepatu'])) {
    $ukuranList = array_map('trim', explode(',', $p['ukuran_sepatu']));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - <?= htmlspecialchars($p['namaproduk']); ?></title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            text-align: center;
        }
        header h1 { color: #333; font-size: 2em; }
        .container { max-width: 1200px; margin: 0 auto; }
        .detail-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .detail-box img, .no-image {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 15px;
        }
        .no-image {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 5em;
        }
        .info h2 { margin-bottom: 20px; font-size: 2em; }
        .harga { font-size: 2.5em; color: #667eea; font-weight: bold; }
        .stok {
            display: inline-block;
            margin: 15px 0;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
        }
        .stok-habis { background: #fee; color: #c00; }
        .stok-terbatas { background: #fff3cd; color: #856404; }
        .stok-aman { background: #d4edda; color: #155724; }
        .deskripsi { margin: 25px 0; line-height: 1.8; color: #666; }
        .info-item {
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .info-item strong { color: #667eea; }
        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">
    <header>
        <h1>📋 Detail Produk</h1>
    </header>

    <div class="detail-box">
        <!-- GAMBAR -->
        <div>
            <?php if (!empty($p['gambar']) && file_exists($gambarPath)): ?>
                <img src="<?= htmlspecialchars($gambarPath); ?>">
            <?php else: ?>
                <div class="no-image">📦</div>
            <?php endif; ?>
        </div>

        <!-- INFO -->
        <div class="info">
            <h2><?= htmlspecialchars($p['namaproduk']); ?></h2>

            <div class="harga">
                Rp <?= number_format($p['hargaproduk'], 0, ',', '.'); ?>
            </div>

            <?php if ($stok == 0): ?>
                <span class="stok stok-habis">❌ STOK HABIS</span>
            <?php elseif ($stok < 5): ?>
                <span class="stok stok-terbatas">⚠️ STOK TERBATAS</span>
            <?php else: ?>
                <span class="stok stok-aman">✅ STOK TERSEDIA</span>
            <?php endif; ?>

            <div class="deskripsi">
                <?= !empty($p['deskripsi']) ? nl2br(htmlspecialchars($p['deskripsi'])) : '<em>Deskripsi tidak tersedia</em>'; ?>
            </div>

            <div class="info-item">
                <strong>📂 Kategori:</strong> <?= htmlspecialchars($p['kategori'] ?? '-'); ?>
            </div>

            <div class="info-item">
                <strong>👟 Ukuran Tersedia:</strong> <?= htmlspecialchars($p['ukuran_sepatu']); ?>
            </div>

            <!-- =======================
                 TAMBAHAN SIZE & CART
            ======================== -->
            <?php if ($stok > 0 && !empty($ukuranList)): ?>
                <div class="info-item">
                    <strong>🧩 Pilih Ukuran:</strong><br>
                    <?php foreach ($ukuranList as $u): ?>
                        <label style="margin-right:10px;">
                            <input type="radio" name="size" value="<?= htmlspecialchars($u); ?>"> <?= htmlspecialchars($u); ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button class="btn" onclick="addToCart(<?= $p['idproduk']; ?>)">
                    🛒 Tambah ke Keranjang
                </button>
            <?php endif; ?>

            <br>
            <a class="btn" href="index.php">← Kembali ke Toko</a>
        </div>
    </div>
</div>

<script>
function addToCart(idProduk) {
    const size = document.querySelector('input[name="size"]:checked');
    if (!size) {
        alert('Pilih ukuran terlebih dahulu!');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'cart_add.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.responseText.trim() === 'OK') {
            alert('Produk ditambahkan ke keranjang');
            window.location.href = 'cart.php';
        } else {
            alert('Gagal menambahkan produk');
        }
    };

    xhr.send(
        'id=' + idProduk +
        '&qty=1' +
        '&size=' + encodeURIComponent(size.value)
    );
}
</script>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
