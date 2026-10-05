?>
<?php
session_start();
include '../koneksi.php';

$id = (int)$_GET['id'];

// Ambil data order
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Ambil detail produk
$query_detail = "SELECT * FROM order_detail WHERE order_id = ?";
$stmt_detail = mysqli_prepare($koneksi, $query_detail);
mysqli_stmt_bind_param($stmt_detail, 'i', $id);
mysqli_stmt_execute($stmt_detail);
$items = mysqli_stmt_get_result($stmt_detail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $order['no_order']; ?></title>
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
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            text-align: center;
        }

        .order-info {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .info-label {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .info-value {
            color: #333;
            font-weight: 600;
            font-size: 1.1em;
        }

        .items-table {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }

        .total-row {
            font-size: 1.3em;
            font-weight: bold;
            color: #667eea;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .status-actions {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .status-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 10px;
            margin-top: 10px;
        }

        .btn-confirm { background: #28a745; color: white; }
        .btn-process { background: #17a2b8; color: white; }
        .btn-ship { background: #007bff; color: white; }
        .btn-deliver { background: #28a745; color: white; }
        .btn-cancel { background: #dc3545; color: white; }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>📋 Detail Pesanan</h1>
        <p style="color: #666; margin-top: 10px;">Order #<?= $order['no_order']; ?></p>
    </header>

    <div class="order-info">
        <h2 style="margin-bottom: 20px;">👤 Informasi Pelanggan</h2>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value"><?= htmlspecialchars($order['nama']); ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value"><?= htmlspecialchars($order['email']); ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">No. Telepon</div>
                <div class="info-value"><?= htmlspecialchars($order['telepon']); ?></div>
            </div>

            <div class="info-item">
                <div class="info-label">Tanggal Order</div>
                <div class="info-value"><?= date('d M Y, H:i', strtotime($order['tgl_order'])); ?></div>
            </div>
        </div>

        <div class="info-item" style="margin-top: 20px;">
            <div class="info-label">Alamat Pengiriman</div>
            <div class="info-value">
                <?= nl2br(htmlspecialchars($order['alamat'])); ?><br>
                <?= htmlspecialchars($order['kota']); ?>, <?= htmlspecialchars($order['kodepos']); ?>
            </div>
        </div>

        <div class="info-item" style="margin-top: 20px;">
            <div class="info-label">Metode Pembayaran</div>
            <div class="info-value"><?= strtoupper($order['metode_bayar']); ?></div>
        </div>

        <?php if (!empty($order['catatan'])): ?>
        <div class="info-item" style="margin-top: 20px;">
            <div class="info-label">Catatan</div>
            <div class="info-value"><?= nl2br(htmlspecialchars($order['catatan'])); ?></div>
        </div>
        <?php endif; ?>

        <div class="status-actions">
            <h3 style="margin-bottom: 15px;">Status: 
                <span style="color: #667eea;"><?= strtoupper($order['status']); ?></span>
            </h3>
            
            <?php if ($order['status'] == 'pending'): ?>
                <button class="status-btn btn-confirm" onclick="updateStatus('confirmed')">✅ Konfirmasi</button>
                <button class="status-btn btn-cancel" onclick="updateStatus('cancelled')">❌ Batalkan</button>
            <?php elseif ($order['status'] == 'confirmed'): ?>
                <button class="status-btn btn-process" onclick="updateStatus('processing')">📦 Proses</button>
            <?php elseif ($order['status'] == 'processing'): ?>
                <button class="status-btn btn-ship" onclick="updateStatus('shipped')">🚚 Kirim</button>
            <?php elseif ($order['status'] == 'shipped'): ?>
                <button class="status-btn btn-deliver" onclick="updateStatus('delivered')">✅ Selesai</button>
            <?php endif; ?>
        </div>
    </div>

    <div class="items-table">
        <h2 style="margin-bottom: 20px;">📦 Item Pesanan</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($items)): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama_produk']); ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td><?= $item['qty']; ?></td>
                    <td>Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL</td>
                    <td>Rp <?= number_format($order['total'], 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <a href="orders.php" class="btn btn-back">← Kembali</a>
    </div>
</div>

<script>
function updateStatus(newStatus) {
    if (confirm('Update status pesanan ke ' + newStatus + '?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'order_update_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            }
        };
        xhr.send('id=<?= $id; ?>&status=' + newStatus);
    }
}
</script>

</body>
</html>