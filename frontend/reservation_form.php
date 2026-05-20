<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

$car_sql = "SELECT * FROM Car WHERE CarID = ?";
$car_stmt = $conn->prepare($car_sql);
$car_stmt->bind_param("i", $car_id);
$car_stmt->execute();
$car_result = $car_stmt->get_result();
$car = $car_result->fetch_assoc();

if (!$car) {
    die("Car not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation - Madang Car Rental</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="exclude-white">
    <header>
        <nav>
            <div class="logo"><a href="index.php">🏝️ Madang Car Rental</a></div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="browse_cars.php">Browse Cars</a>
                <a href="my_reservations.php">My Reservations</a>
                <a href="my_rentals.php">My Rentals</a>
                <a href="../customer-service/logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h1>Reserve a Car</h1>
            
            <div style="background: #f0f4f8; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h3><?php echo $car['Brand'] . ' ' . $car['CarModel']; ?></h3>
                <p>Rate: K <?php echo number_format($car['RentalPrice'], 2); ?> per day</p>
            </div>

            <form action="../reservation-service/create_reservation.php" method="POST">
                <input type="hidden" name="car_id" value="<?php echo $car['CarID']; ?>">
                
                <div class="form-group">
                    <label>Pickup Date</label>
                    <input type="date" name="pickup_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Return Date</label>
                    <input type="date" name="return_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>
                
                <button type="submit">Confirm Reservation</button>
            </form>
        </div>
    </div>
</body>
</html>

