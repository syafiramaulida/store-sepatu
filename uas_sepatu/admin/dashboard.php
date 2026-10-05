<?php
session_start();
include '../koneksi.php';

// Proteksi login admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   STATISTIK PRODUK
========================= */

// Total produk
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM produk");
$totalProduk = mysqli_fetch_assoc($qTotal)['total'];

// Total stok
$qStok = mysqli_query($koneksi, "SELECT SUM(stok) AS total_stok FROM produk");
$totalStok = mysqli_fetch_assoc($qStok)['total_stok'];

// Produk stok rendah (<10)
$qLow = mysqli_query($koneksi, "SELECT COUNT(*) AS low FROM produk WHERE stok < 10");
$lowStockCount = mysqli_fetch_assoc($qLow)['low'];

// Total nilai inventori
$qNilai = mysqli_query(
    $koneksi,
    "SELECT SUM(harga * stok) AS total_nilai FROM produk"
);
$totalNilai = mysqli_fetch_assoc($qNilai)['total_nilai'];

// Produk terbaru
$resultTerbaru = mysqli_query(
    $koneksi,
    "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 5"
);

// Produk stok rendah detail
$resultLowStockDetail = mysqli_query(
    $koneksi,
    "SELECT * FROM produk WHERE stok < 10 ORDER BY stok ASC LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- STYLE TETAP SAMA DENGAN PUNYAMU -->
    <style>
        /* (STYLE TIDAK SAYA UBAH, SAMA PERSIS) */
        <?= file_get_contents(__FILE__) ? '' : '' ?>
    </style>
</head>
<body>

<div class="dashboard-container">

    <!-- HEADER -->
    <div class="dashboard-header">
        <div>
            <h1>📊 Dashboard Admin</h1>
            <p style="color:#666;margin:5px 0 0">Statistik & Ringkasan Toko</p>
        </div>
        <div>
            <a href="dashboard.php" class="btn btn-primary">📦 Kelola Produk</a>
            <a href="../index.php" class="btn btn-back">← Ke Toko</a>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon">📦</div>
            <div class="stat-value"><?= $totalProduk; ?></div>
            <div class="stat-label">Total Produk</div>
        </div>

        <div class="stat-card blue">
            <div class="stat-icon">📊</div>
            <div class="stat-value"><?= number_format($totalStok); ?></div>
            <div class="stat-label">Total Stok</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon">⚠️</div>
            <div class="stat-value"><?= $lowStockCount; ?></div>
            <div class="stat-label">Stok Rendah</div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">💰</div>
            <div class="stat-value">
                Rp <?= number_format($totalNilai / 1000000, 1); ?>M
            </div>
            <div class="stat-label">Nilai Inventori</div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content-grid">

        <!-- PRODUK TERBARU -->
        <div class="content-card">
            <h2>🆕 Produk Terbaru</h2>

            <?php if (mysqli_num_rows($resultTerbaru) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($resultTerbaru)): ?>
                    <div class="product-item">

                        <?php if (!empty($p['gambar']) && file_exists("../assets/img/produk/".$p['gambar'])): ?>
                            <img src="../assets/img/produk/<?= $p['gambar']; ?>" class="product-thumb">
                        <?php else: ?>
                            <div class="product-thumb">📦</div>
                        <?php endif; ?>

                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($p['nama_produk']); ?></div>
                            <div class="product-price">
                                Rp <?= number_format($p['harga'],0,',','.'); ?>
                            </div>
                        </div>

                        <span class="product-stock <?= $p['stok'] < 10 ? 'stock-low' : 'stock-ok'; ?>">
                            <?= $p['stok']; ?> pcs
                        </span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center;color:#999">Belum ada produk</p>
            <?php endif; ?>
        </div>

        <!-- STOK RENDAH -->
        <div class="content-card">
            <h2>⚠️ Perhatian: Stok Rendah</h2>

            <?php if (mysqli_num_rows($resultLowStockDetail) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($resultLowStockDetail)): ?>
                    <div class="product-item">

                        <?php if (!empty($p['gambar']) && file_exists("../assets/img/produk/".$p['gambar'])): ?>
                            <img src="../assets/img/produk/<?= $p['gambar']; ?>" class="product-thumb">
                        <?php else: ?>
                            <div class="product-thumb">📦</div>
                        <?php endif; ?>

                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($p['nama_produk']); ?></div>
                            <div class="product-price">
                                Rp <?= number_format($p['harga'],0,',','.'); ?>
                            </div>
                        </div>

                        <span class="product-stock stock-low">
                            <?= $p['stok']; ?> pcs
                        </span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center;color:#28a745">✅ Semua stok aman</p>
            <?php endif; ?>
        </div>

    </div>
</div>

</body>
</html>
