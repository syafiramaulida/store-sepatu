<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
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

        input, textarea, select {
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
            transition: transform 0.2s ease;
        }

        button:hover {
            transform: scale(1.02);
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
    <h3>➕ Tambah Produk Sepatu</h3>

    <form action="simpan.php" method="POST" enctype="multipart/form-data">
        <label>Nama Produk *</label>
        <input type="text" name="namaproduk" required placeholder="Contoh: Nike Air Max 270">

        <label>Harga *</label>
        <input type="number" name="hargaproduk" required placeholder="Contoh: 1500000">

        <label>Deskripsi</label>
        <textarea name="deskripsi" placeholder="Deskripsi detail produk..."></textarea>

        <label>Kategori</label>
        <input type="text" name="kategori" value="Sepatu" placeholder="Contoh: Sepatu">

        <label>Ukuran Sepatu</label>
        <input type="text" name="ukuran_sepatu" placeholder="Contoh: 39, 40, 41, 42">

        <label>Stok *</label>
        <input type="number" name="stok" required placeholder="Jumlah stok tersedia">

        <label>Gambar Produk</label>
        <input type="file" name="gambar" accept="image/*">

        <button type="submit">💾 Simpan Produk</button>
        <a href="produk.php" class="btn-back">← Kembali</a>
    </form>
</div>

</body>
</html>

<?php