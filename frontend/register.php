
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Madang Car Rental</title>
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

    <div class="register-container">
        <div class="register-card">
            <h2>📝 Create Account</h2>
            <div class="register-subtitle">Join Madang Car Rental and hit the road</div>

            <?php if(isset($_SESSION['errors'])): ?>
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <?php foreach($_SESSION['errors'] as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form action="../customer-service/register.php" method="POST">
                <div class="form-group">
                    <label for="firstname">👤 First Name *</label>
                    <input type="text" id="firstname" name="firstname" required placeholder="John">
                </div>

                <div class="form-group">
                    <label for="lastname">👤 Last Name *</label>
                    <input type="text" id="lastname" name="lastname" required placeholder="Doe">
                </div>

                <div class="form-group">
                    <label for="phone">📞 Phone</label>
                    <input type="tel" id="phone" name="phone" placeholder="+675 1234 5678">
                </div>

                <div class="form-group">
                    <label for="email">📧 Email *</label>
                    <input type="email" id="email" name="email" required placeholder="your@email.com">
                </div>

                <div class="form-group">
                    <label for="address">📍 Address</label>
                    <textarea id="address" name="address" rows="3" placeholder="Your full address"></textarea>
                </div>

                <div class="form-group">
                    <label for="license_no">🪪 Driver's License Number *</label>
                    <input type="text" id="license_no" name="license_no" required placeholder="DL123456">
                </div>

                <div class="form-group">
                    <label for="password">🔒 Password *</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label for="confirm_password">🔒 Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••">
                </div>

                <button type="submit" class="register-btn">Register →</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>