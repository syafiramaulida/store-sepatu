<?php
// Include file koneksi database
include 'koneksi.php';

// Ambil ID produk dari parameter GET
$idproduk = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil detail produk berdasarkan ID
$query = "SELECT * FROM produk WHERE idproduk = $idproduk";
$result = mysqli_query($conn, $query);

// Cek apakah produk ditemukan
if (mysqli_num_rows($result) > 0) {
    $produk = mysqli_fetch_assoc($result);
    
    // Tentukan status stok
    $stokStatus = '';
    $stokClass = 'stok-badge';
    
    if ($produk['stok'] > 20) {
        $stokStatus = 'Tersedia Banyak';
    } else if ($produk['stok'] > 10) {
        $stokStatus = 'Tersedia';
    } else if ($produk['stok'] > 0) {
        $stokStatus = 'Stok Terbatas';
        $stokClass .= ' low';
    } else {
        $stokStatus = 'Habis';
        $stokClass .= ' out';
    }
    
    // Format harga
    $harga = number_format($produk['hargaproduk'], 0, ',', '.');
?>
    <div class="modal-image">
        <?php 
        $imagePath = 'images/' . $produk['gambar'];
        if (file_exists($imagePath)): 
        ?>
            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($produk['namaproduk']); ?>">
        <?php else: ?>
            <span>📦 <?php echo htmlspecialchars($produk['namaproduk']); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="modal-info">
        <h3><?php echo htmlspecialchars($produk['namaproduk']); ?></h3>
        <p class="modal-price">Rp <?php echo $harga; ?></p>
        <p class="modal-description"><?php echo nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
        
        <div class="modal-details-grid">
            <div class="modal-detail-item">
                <span class="modal-detail-label">ID Produk</span>
                <span class="modal-detail-value">#<?php echo str_pad($produk['idproduk'], 4, '0', STR_PAD_LEFT); ?></span>
            </div>
            
            <div class="modal-detail-item">
                <span class="modal-detail-label">Status Stok</span>
                <span class="modal-detail-value">
                    <span class="<?php echo $stokClass; ?>"><?php echo $stokStatus; ?></span>
                </span>
            </div>
            
            <div class="modal-detail-item">
                <span class="modal-detail-label">Jumlah Stok</span>
                <span class="modal-detail-value"><?php echo $produk['stok']; ?> pcs</span>
            </div>
            
            <div class="modal-detail-item">
                <span class="modal-detail-label">Ukuran Tersedia</span>
                <span class="modal-detail-value"><?php echo htmlspecialchars($produk['ukuran_sepatu']); ?></span>
            </div>
        </div>
        
        <?php if ($produk['stok'] > 0): ?>
            <button class="btn-detail" style="margin-top: 20px;" onclick="alert('Fitur pemesanan akan segera hadir!')">
                🛒 Tambah ke Keranjang
            </button>
        <?php else: ?>
            <button class="btn-detail" style="margin-top: 20px; background: #dc3545; opacity: 0.6;" disabled>
                ❌ Stok Habis
            </button>
        <?php endif; ?>
    </div>
<?php
} else {
    echo '<div class="loading">Produk tidak ditemukan.</div>';
}

// Tutup koneksi database
mysqli_close($conn);
?>