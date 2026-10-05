?>
<?php
session_start();
include '../koneksi.php';

// Ambil semua pesanan
$query = "SELECT * FROM orders ORDER BY id DESC";
$data = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan</title>
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

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
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
            white-space: nowrap;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-processing {
            background: #d4edda;
            color: #155724;
        }

        .status-shipped {
            background: #cce5ff;
            color: #004085;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-detail, .btn-status {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-detail {
            background: #667eea;
            color: white;
        }

        .btn-status {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .empty {
            text-align: center;
            padding: 50px;
            color: #999;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h2>📦 Kelola Pesanan</h2>
        <div>
            <a href="produk.php" class="btn btn-secondary">📋 Produk</a>
            <a href="../index.php" class="btn btn-secondary">← Ke Toko</a>
        </div>
    </header>

    <div class="table-container">
        <?php if (mysqli_num_rows($data) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($order = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><strong><?= $order['no_order']; ?></strong></td>
                    <td><?= date('d/m/Y H:i', strtotime($order['tgl_order'])); ?></td>
                    <td><?= htmlspecialchars($order['nama']); ?></td>
                    <td><?= htmlspecialchars($order['telepon']); ?></td>
                    <td><strong>Rp <?= number_format($order['total'], 0, ',', '.'); ?></strong></td>
                    <td><?= strtoupper($order['metode_bayar']); ?></td>
                    <td>
                        <span class="status-badge status-<?= $order['status']; ?>">
                            <?php
                            $status_text = [
                                'pending' => '⏳ Pending',
                                'confirmed' => '✅ Confirmed',
                                'processing' => '📦 Processing',
                                'shipped' => '🚚 Shipped',
                                'delivered' => '✅ Delivered',
                                'cancelled' => '❌ Cancelled'
                            ];
                            echo $status_text[$order['status']] ?? $order['status'];
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="order_detail.php?id=<?= $order['id']; ?>" class="btn-detail">Detail</a>
                        <?php if ($order['status'] == 'pending'): ?>
                        <button class="btn-status" onclick="updateStatus(<?= $order['id']; ?>, 'confirmed')">
                            Konfirmasi
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="empty">
                <h3>Belum ada pesanan</h3>
                <p>Pesanan akan muncul di sini ketika ada pelanggan yang checkout</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function updateStatus(orderId, status) {
    if (confirm('Update status pesanan?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'order_update_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            }
        };
        xhr.send('id=' + orderId + '&status=' + status);
    }
}
</script>

</body>
</html>
