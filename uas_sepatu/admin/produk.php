<?php
// ========================================
// admin/produk.php
// ========================================
?>
<?php
include '../koneksi.php';
$data = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY idproduk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Admin</title>
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
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h2 {
            color: #333;
            font-size: 2em;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-edit {
            background: #ffc107;
            color: #333;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
        }

        .empty {
            text-align: center;
            padding: 50px;
            color: #999;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h2>📦 Data Produk</h2>
        <div>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Produk</a>
            <a href="../index.php" class="btn" style="background: #6c757d; color: white;">← Ke Toko</a>
        </div>
    </header>

    <div class="table-container">
        <?php if (mysqli_num_rows($data) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while($p = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <?php if (!empty($p['gambar']) && file_exists("../assets/img/produk/".$p['gambar'])): ?>
                            <img src="../assets/img/produk/<?= $p['gambar']; ?>" class="product-img">
                        <?php else: ?>
                            <div style="width:60px;height:60px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;">📦</div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['namaproduk']); ?></td>
                    <td>Rp <?= number_format($p['hargaproduk'], 0, ',', '.'); ?></td>
                    <td><?= $p['stok']; ?> pcs</td>
                    <td>
                        <a href="edit.php?id=<?= $p['idproduk']; ?>" class="btn-edit">✏️ Edit</a>
                        <a href="hapus.php?id=<?= $p['idproduk']; ?>" class="btn-delete" onclick="return confirm('Hapus produk ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="empty">
                <h3>Belum ada produk</h3>
                <p>Silakan <a href="tambah.php">tambah produk</a> terlebih dahulu</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php