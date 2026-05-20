<?php
session_start();
include '../config/db.php';

$sql = "SELECT * FROM Car WHERE Status = 'Available'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Cars - Madang Car Rental</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo"><a href="index.php">🏝️ Madang Car Rental</a></div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="browse_cars.php">Browse Cars</a>
                <?php if(isset($_SESSION['customer_id'])): ?>
                    <a href="my_reservations.php">My Reservations</a>
                    <a href="my_rentals.php">My Rentals</a>
                    <a href="../customer-service/logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Available Cars for Rent</h1>
        
        <div class="grid-3">
            <?php while($car = $result->fetch_assoc()): ?>
                <div class="car-card">
                    <div class="car-card-content">
                        <h3><?php echo $car['Brand'] . ' ' . $car['CarModel']; ?></h3>
                        <p><strong>Type:</strong> <?php echo $car['CarType']; ?></p>
                        <p><strong>Year:</strong> <?php echo $car['Year']; ?></p>
                        <p><strong>Seats:</strong> <?php echo $car['Seats']; ?></p>
                        <p><strong>Transmission:</strong> <?php echo $car['Transmission']; ?></p>
                        <div class="car-price">K <?php echo number_format($car['RentalPrice'], 2); ?> / day</div>
                        
                        <?php if(isset($_SESSION['customer_id'])): ?>
                            <a href="reservation_form.php?car_id=<?php echo $car['CarID']; ?>" class="btn">Book Now</a>
                        <?php else: ?>
                            <a href="login.php" class="btn">Login to Book</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if($result->num_rows == 0): ?>
                <p>No cars available at the moment. Please check back later.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
