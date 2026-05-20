<<<<<<< HEAD
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Madang Car Rental</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Additional styling specific to admin login */
        body {
            background: url('assets/images/car_image_2.webp')center/cover no-repeat;;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .admin-login-container {
            max-width: 450px;
            width: 100%;
            margin: 20px;
        }
        .admin-login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        .admin-login-card:hover {
            transform: translateY(-5px);
        }
        .admin-login-header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .admin-login-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .admin-login-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .admin-login-body {
            padding: 40px;
        }
        .input-group {
            margin-bottom: 25px;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        .input-group input:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42,82,152,0.1);
        }
        .btn-admin-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }
        .btn-admin-login:hover {
            opacity: 0.9;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #2a5298;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .error-message {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            color: #991b1b;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .admin-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="admin-login-container">
    <div class="admin-login-card">
        <div class="admin-login-header">
            <div class="admin-icon">🔐</div>
            <h1>Admin Login</h1>
            <p>Secure access to management dashboard</p>
        </div>
        <div class="admin-login-body">
            <?php if(isset($_SESSION['errors'])): ?>
                <div class="error-message">
                    <?php 
                        if(is_array($_SESSION['errors'])) {
                            echo implode('<br>', $_SESSION['errors']);
                        } else {
                            echo $_SESSION['errors'];
                        }
                    ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
            
            <form action="../admin-service/login.php" method="POST">
                <div class="input-group">
                    <label>📧 Admin Email</label>
                    <input type="email" name="email" placeholder="admin@madangcar.com" required autofocus>
                </div>
                <div class="input-group">
                    <label>🔒 Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-admin-login">Login as Admin →</button>
            </form>
            <div class="back-link">
                <a href="index.php">← Back to Customer Site</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
=======

>>>>>>> 1dd43c7ee41b6f5d0969426f24fee60d1b04326c
