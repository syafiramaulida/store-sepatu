<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>API Produk - Klien</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background: linear-gradient(135deg, #4CAF50, #2E7D32);
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    .header-text {
      text-align: center;
      margin-bottom: 20px;
      font-size: 18px;
      font-weight: 500;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      max-width: 600px;
    }
    .card {
      width: 92%;
      max-width: 900px;
      background: rgba(255,255,255,0.98);
      color: #222;
      border-radius: 12px;
      padding: 28px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    h1 { 
      margin: 0 0 20px 0; 
      color: #1b5e20;
      font-size: 28px;
      border-bottom: 3px solid #4CAF50;
      padding-bottom: 12px;
    }
    .controls { 
      display: flex; 
      gap: 12px; 
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }
    button {
      padding: 12px 24px;
      font-size: 15px;
      background-color: #1b5e20;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: all .2s ease;
      font-weight: 600;
    }
    button:hover { 
      transform: translateY(-2px); 
      background-color: #2E7D32;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    button:active {
      transform: translateY(0);
    }
    .token-info {
      font-size: 13px;
      color: #666;
      background: #f5f5f5;
      padding: 8px 12px;
      border-radius: 4px;
      border-left: 3px solid #4CAF50;
    }
    .token-info code {
      background: #fff;
      padding: 2px 6px;
      border-radius: 3px;
      color: #1b5e20;
      font-family: 'Courier New', monospace;
    }
    .output-container { 
      background: #f9f9f9;
      border-radius: 8px;
      padding: 16px;
      border: 2px solid #e0e0e0;
    }
    .output-container strong { 
      color: #1b5e20;
      font-size: 16px;
      display: block;
      margin-bottom: 10px;
    }
    pre { 
      margin: 0;
      white-space: pre-wrap;
      word-wrap: break-word;
      color: #333;
      font-size: 13px;
      line-height: 1.6;
      background: #fff;
      padding: 12px;
      border-radius: 4px;
      border: 1px solid #e0e0e0;
      max-height: 500px;
      overflow-y: auto;
    }
    .loading { 
      color: #1976D2;
      font-style: italic;
    }
    .error { 
      color: #c62828;
      font-weight: bold;
      background: #ffebee;
      padding: 12px;
      border-radius: 4px;
      border-left: 4px solid #c62828;
    }
    .success {
      color: #2E7D32;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="header-text">
    Tekan tombol untuk memanggil API dan menampilkan hasil JSON
  </div>
  
  <div class="card">
    <h1>🛍️ API Produk - Klien</h1>
    
    <div class="controls">
      <button id="fetchBtn">📡 Ambil Data dari API</button>
      <div class="token-info">
        Token: <code>1234567890abcdef</code>
      </div>
    </div>

    <div class="output-container">
      <strong>📊 Output (JSON):</strong>
      <pre id="output">Klik tombol di atas untuk memulai request ke API...</pre>
    </div>
  </div>

  <script>
    (function(){
      var output = document.getElementById('output');
      var btn = document.getElementById('fetchBtn');
      var token = '1234567890abcdef';

      // Fungsi untuk menampilkan loading
      function showLoading(){ 
        output.className = 'loading';
        output.textContent = '⏳ Memuat data dari API...'; 
      }

      // Fungsi untuk menampilkan error
      function showError(msg){ 
        output.className = 'error';
        output.textContent = '❌ Error: ' + msg;
      }

      // Fungsi untuk menampilkan data
      function showData(obj){
        output.className = 'success';
        try{
          output.textContent = JSON.stringify(obj, null, 2);
        }catch(e){ 
          output.textContent = String(obj); 
        }
      }

      // Event listener untuk tombol
      btn.addEventListener('click', function(){
        showLoading();
        
        // PENTING: Ganti URL ini dengan endpoint API Anda yang sebenarnya
        // Contoh menggunakan API publik untuk demo
        var apiUrl = 'https://dummyjson.com/products?limit=5';
        
        // Jika Anda punya proxy.php, gunakan ini:
        // var apiUrl = 'proxy.php?token=' + encodeURIComponent(token);
        
        fetch(apiUrl, { 
          method: 'GET',
          headers: {
            'Accept': 'application/json'
          }
        })
          .then(function(resp){
            if (!resp.ok) {
              throw new Error('HTTP Error: ' + resp.status + ' ' + resp.statusText);
            }
            
            var contentType = resp.headers.get('content-type') || '';
            if (contentType.indexOf('application/json') !== -1) {
              return resp.json();
            } else {
              // Jika bukan JSON, tampilkan sebagai text
              return resp.text().then(function(text){ 
                throw new Error('Response bukan JSON. Content-Type: ' + contentType + '\n\nResponse:\n' + text.substring(0, 500)); 
              });
            }
          })
          .then(function(data){
            showData(data);
          })
          .catch(function(err){
            showError(err.message || String(err));
            console.error('Fetch error:', err);
          });
      });
    })();
  </script>
</body>
</html>