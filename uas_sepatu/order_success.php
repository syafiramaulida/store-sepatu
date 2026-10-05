<?php
session_start();
$no = $_GET['order'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - BelanjaKu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            animation: gradientBackground 10s ease infinite;
            background-size: 200% 200%;
        }
        
        @keyframes gradientBackground {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .success-container {
            background: white;
            border-radius: 25px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            position: relative;
            z-index: 2;
            animation: pulse 2s infinite;
            box-shadow: 0 15px 30px rgba(72, 187, 120, 0.3);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .success-icon i {
            color: white;
            font-size: 60px;
        }
        
        .success-title {
            font-size: 36px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .success-message {
            color: #718096;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }
        
        .order-number-container {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            position: relative;
            z-index: 2;
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
        }
        
        .order-number-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        
        .order-label {
            display: block;
            color: #718096;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .order-number {
            font-size: 32px;
            font-weight: 800;
            color: #2d3748;
            letter-spacing: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding: 10px;
            border-radius: 10px;
            display: inline-block;
        }
        
        .order-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
            position: relative;
            z-index: 2;
            border-left: 5px solid #48bb78;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: #4a5568;
        }
        
        .info-item:last-child {
            margin-bottom: 0;
        }
        
        .info-item i {
            color: #48bb78;
            font-size: 18px;
            width: 24px;
        }
        
        .button-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 35px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            min-width: 180px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            background: #edf2f7;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .confetti {
            position: absolute;
            width: 15px;
            height: 15px;
            background: #48bb78;
            border-radius: 50%;
            opacity: 0;
            z-index: 1;
        }
        
        @keyframes confettiFall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(1000px) rotate(360deg);
                opacity: 0;
            }
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(150%);
            transition: transform 0.3s ease;
            z-index: 1000;
            border-left: 5px solid #48bb78;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        @media (max-width: 600px) {
            .success-container {
                padding: 40px 25px;
                margin: 20px;
            }
            
            .success-title {
                font-size: 28px;
            }
            
            .order-number {
                font-size: 24px;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        @media (max-width: 400px) {
            .success-container {
                padding: 30px 20px;
            }
            
            .success-icon {
                width: 100px;
                height: 100px;
            }
            
            .success-icon i {
                font-size: 50px;
            }
            
            .success-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    
    <!-- Notification -->
    <div class="notification" id="notification">
        <i class="fas fa-check-circle" style="color: #48bb78; font-size: 20px;"></i>
        <div>
            <strong>Pesanan berhasil dibuat!</strong>
            <div style="font-size: 14px; color: #718096;">Silakan tunggu konfirmasi dari kami</div>
        </div>
    </div>
    
    <div class="success-container">
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <!-- Title -->
        <h1 class="success-title">Pesanan Berhasil!</h1>
        
        <!-- Message -->
        <p class="success-message">
            Terima kasih telah berbelanja di toko kami. Pesanan Anda telah berhasil diproses dan akan segera kami kirim.
        </p>
        
        <!-- Order Number -->
        <div class="order-number-container">
            <span class="order-label">Nomor Pesanan</span>
            <div class="order-number"><?= htmlspecialchars($no) ?></div>
        </div>
        
        <!-- Order Info -->
        <div class="order-info">
            <div class="info-item">
                <i class="fas fa-info-circle"></i>
                <span>Simpan nomor pesanan untuk tracking</span>
            </div>
            <div class="info-item">
                <i class="fas fa-clock"></i>
                <span>Pesanan akan diproses dalam 1x24 jam</span>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <span>Konfirmasi akan dikirim via email</span>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="button-container">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Kembali ke Home
            </a>
        </div>
        
        <!-- Hidden confetti elements -->
        <div class="confetti" style="top:10%; left:10%; background:#667eea; animation-delay: 0.2s;"></div>
        <div class="confetti" style="top:15%; left:30%; background:#764ba2; animation-delay: 0.4s;"></div>
        <div class="confetti" style="top:5%; left:50%; background:#48bb78; animation-delay: 0.6s;"></div>
        <div class="confetti" style="top:20%; left:70%; background:#ed8936; animation-delay: 0.8s;"></div>
        <div class="confetti" style="top:10%; left:90%; background:#f56565; animation-delay: 1s;"></div>
    </div>
    
    <script>
        // Show notification
        setTimeout(() => {
            document.getElementById('notification').classList.add('show');
        }, 500);
        
        // Hide notification after 5 seconds
        setTimeout(() => {
            document.getElementById('notification').classList.remove('show');
        }, 5000);
        
        // Create confetti effect
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.success-container');
            const colors = ['#667eea', '#764ba2', '#48bb78', '#ed8936', '#f56565'];
            
            for (let i = 0; i < 20; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = Math.random() * 20 + '%';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 15 + 10 + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.animationDelay = Math.random() * 2 + 's';
                confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
                container.appendChild(confetti);
            }
            
            // Animate confetti
            const confettiElements = document.querySelectorAll('.confetti');
            confettiElements.forEach(confetti => {
                confetti.style.animation = `confettiFall ${Math.random() * 3 + 2}s linear infinite`;
                confetti.style.opacity = '1';
            });
        });
        
        // Add celebration sound effect (optional)
        function playSuccessSound() {
            // You can add a subtle success sound here
            console.log("🎉 Pesanan berhasil! 🎉");
        }
        
        // Play sound on page load
        window.addEventListener('load', playSuccessSound);
    </script>
</body>
</html>