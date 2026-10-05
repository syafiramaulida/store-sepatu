<?php
include 'koneksi.php';

// Proses upload jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['gambar'])) {
    $idproduk = intval($_POST['idproduk']);
    $file = $_FILES['gambar'];
    
    // Validasi file
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
        // Buat folder images jika belum ada
        if (!file_exists('images')) {
            mkdir('images', 0777, true);
        }
        
        // Generate nama file unik
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'product_' . $idproduk . '_' . time() . '.' . $extension;
        $uploadPath = 'images/' . $newFileName;
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update database
            $query = "UPDATE produk SET gambar = '$newFileName' WHERE idproduk  = $idproduk";
            if (mysqli_query($conn, $query)) {
                $message = "✅ Gambar berhasil diupload!";
            } else {
                $message = "❌ Gagal update database.";
            }
        } else {
            $message = "❌ Gagal upload file.";
        }
    } else {
        $message = "❌ File tidak valid. Hanya JPG, PNG, GIF maksimal 2MB.";
    }
}

// Ambil semua produk
$query = "SELECT * FROM produk ORDER BY idproduk";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Upload Gambar Produk</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .admin-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-header h1 {
            color: #667eea;
        }
        .upload-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            display: grid;
            grid-template-columns: 2fr 2fr 1fr;
            gap: 15px;
            align-items: center;
        }
        .upload-form input[type="file"] {
            padding: 8px;
            border: 2px solid #667eea;
            border-radius: 5px;
        }
        .upload-form button {
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        .upload-form button:hover {
            opacity: 0.9;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
        .product-name {
            font-weight: 600;
            color: #333;
        }
        .current-image {
            font-size: 0.9em;
            color: #666;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>🖼️ Upload Gambar Produk</h1>
            <p>Upload gambar untuk setiap produk (JPG, PNG, GIF - Max 2MB)</p>
        </div>

        <?php if (isset($message)): ?>
            <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php while ($produk = mysqli_fetch_assoc($result)): ?>
            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <div>
                    <div class="product-name"><?php echo htmlspecialchars($produk['namaproduk']); ?></div>
                    <div class="current-image">
                        Current: <?php echo htmlspecialchars($produk['gambar']); ?>
                    </div>
                </div>
                
                <input type="hidden" name="idproduk" value="<?php echo $produk['idproduk']; ?>">
                <input type="file" name="gambar" accept="image/*" required>
                <button type="submit">Upload</button>
            </form>
        <?php endwhile; ?>

        <div style="text-align: center;">
            <a href="index.php" class="back-link">← Kembali ke Toko</a>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>