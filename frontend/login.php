<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Madang Car Rental</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo"><a href="index.php">🏝️ Madang Car Rental</a></div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="browse_cars.php">Browse Cars</a>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            </div>
        </nav>
    </header>

    <!-- Modern login container -->
    <div class="login-container">
        <div class="login-card">
            <h2>🔐 Welcome Back</h2>
            <div class="login-subtitle">Sign in to continue to Madang Car Rental</div>

            <?php if(isset($_SESSION['errors'])): ?>
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <?php foreach($_SESSION['errors'] as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form action="../customer-service/login.php" method="POST">
                <div class="form-group">
                    <label for="email">📧 Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="your@email.com">
                </div>

                <div class="form-group">
                    <label for="password">🔒 Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" class="login-btn">Login →</button>
            </form>

            <div class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>