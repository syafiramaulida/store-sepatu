?>
<?php
include '../koneksi.php';

$id = (int)$_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM produk WHERE idproduk=$id");
$produk = mysqli_fetch_assoc($data);

if (!$produk) {
    die("Produk tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .box {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h3 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        img {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            margin: 10px 0;
            border-radius: 10px;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="box">
    <h3>✏️ Edit Produk</h3>

    <form action="update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idproduk" value="<?= $produk['idproduk']; ?>">
        <input type="hidden" name="gambar_lama" value="<?= $produk['gambar']; ?>">

        <label>Nama Produk</label>
        <input type="text" name="namaproduk" value="<?= htmlspecialchars($produk['namaproduk']); ?>" required>

        <label>Harga</label>
        <input type="number" name="hargaproduk" value="<?= $produk['hargaproduk']; ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi"><?= htmlspecialchars($produk['deskripsi']); ?></textarea>

        <label>Kategori</label>
        <input type="text" name="kategori" value="<?= htmlspecialchars($produk['kategori']); ?>">

        <label>Ukuran Sepatu</label>
        <input type="text" name="ukuran_sepatu" value="<?= htmlspecialchars($produk['ukuran_sepatu']); ?>">

        <label>Stok</label>
        <input type="number" name="stok" value="<?= $produk['stok']; ?>" required>

        <label>Gambar Saat Ini</label>
        <?php if (!empty($produk['gambar']) && file_exists("../assets/img/produk/".$produk['gambar'])): ?>
            <img src="../assets/img/produk/<?= $produk['gambar']; ?>">
        <?php endif; ?>

        <label>Upload Gambar Baru (opsional)</label>
        <input type="file" name="gambar" accept="image/*">

        <button type="submit">💾 Update Produk</button>
        <a href="produk.php" class="btn-back">← Kembali</a>
    </form>
</div>

</body>
</html>

<?php