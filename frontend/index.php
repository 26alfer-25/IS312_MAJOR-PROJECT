<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madang Car Rental Services</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="exclude-white">
        <header>
        <nav>
            <div class="logo"><a href="index.php">🏝️ Madang Car Rental</a></div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="browse_cars.php">Browse Cars</a>
                <?php if(isset($_SESSION['customer_id'])): ?>
                    <a href="my_reservations.php">My Reservations</a>
                    <a href="my_rentals.php">My Rentals</a>
                    <a href="../customer-service/logout.php">Logout (<?php echo $_SESSION['fullname']; ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="container">
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?php 
                    if($_GET['msg'] == 'registered') echo "Registration successful! Please login.";
                    if($_GET['msg'] == 'loggedin') echo "Welcome back!";
                    if($_GET['msg'] == 'profile_updated') echo "Profile updated successfully!";
                ?>
            </div>
        <?php endif; ?>

        <div class="hero">
            <h1>Welcome to Madang Car Rental Services</h1>
            <p style="margin: 20px 0;">Your trusted car rental partner in Madang Province</p>
            <?php if(!isset($_SESSION['customer_id'])): ?>
                <a href="register.php" class="btn" style="background: white; color: #1e3c72;">Get Started</a>
            <?php else: ?>
                <a href="browse_cars.php" class="btn" style="background: white; color: #1e3c72;">Rent a Car Now</a>
            <?php endif; ?>
        </div>

        <div class="grid-3">
            <div class="card">
                <h2>🚗 Wide Selection</h2>
                <p>Choose from our fleet of well-maintained vehicles including sedans, SUVs, and vans.</p>
            </div>
            <div class="card">
                <h2>💰 Best Prices</h2>
                <p>Competitive rates with no hidden fees. Transparent pricing for all rentals.</p>
            </div>
            <div class="card">
                <h2>🛡️ Fully Insured</h2>
                <p>All our vehicles come with comprehensive insurance coverage for your peace of mind.</p>
            </div>
        </div>
    </div>
</body>
</html>
