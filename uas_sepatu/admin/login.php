<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <style>
        body { background:#f4f6f8; font-family:Arial; }
        .login-box {
            width:300px;
            margin:100px auto;
            background:#fff;
            padding:20px;
            border-radius:8px;
            text-align:center;
        }
        input, button {
            width:100%;
            padding:10px;
            margin-top:10px;
        }
        button {
            background:#111;
            color:#fff;
            border:none;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3>Login Admin</h3>

    <form action="login_proses.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button>Login</button>
    </form>
</div>

</body>
</html>
