<?php
session_start();
include '../config/db.php';

$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM car WHERE CarID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    die("Car not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $car['Brand'] . ' ' . $car['CarModel']; ?> - Madang Car Rental</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $car['Brand'] . ' ' . $car['CarModel']; ?></h1>
        <div class="card">
            <p><strong>Type:</strong> <?php echo $car['CarType']; ?></p>
            <p><strong>Registration:</strong> <?php echo $car['RegistrationNumber']; ?></p>
            <p><strong>Price per day:</strong> K <?php echo number_format($car['RentalPrice'], 2); ?></p>
            <p><strong>Status:</strong> <?php echo $car['Status']; ?></p>
            
            <?php if (isset($_SESSION['customer_id']) && $car['Status'] == 'Available'): ?>
                <a href="reservation_form.php?car_id=<?php echo $car['CarID']; ?>" class="btn">Book Now</a>
            <?php elseif (!isset($_SESSION['customer_id'])): ?>
                <a href="login.php" class="btn">Login to Book</a>
            <?php elseif ($car['Status'] != 'Available'): ?>
                <p class="alert warning">This car is currently not available.</p>
            <?php endif; ?>
        </div>
        <a href="browse_cars.php">← Back to Browse Cars</a>
    </div>
</body>
</html>
