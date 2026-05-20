<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT r.*, res.CarID, rt.StartLocation, rt.EndLocation,
               c.Brand, c.CarModel
        FROM Rental r
        JOIN Reservation res ON r.ReservationID = res.ReservationID
        LEFT JOIN Route rt ON r.RouteID = rt.RouteID
        JOIN Car c ON res.CarID = c.CarID
        WHERE res.CustomerID = ?
        ORDER BY r.RentalStartDate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rentals - Madang Car Rental</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
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
        <h1>My Rental History</h1>

        <table>
            <thead>
                <tr>
                    <th>Rental ID</th>
                    <th>Car</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Route</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="ID"><?php echo $row['RentalID']; ?></td>
                    <td data-label="Car"><?php echo $row['Brand'] . ' ' . $row['CarModel']; ?></td>
                    <td data-label="Start"><?php echo $row['RentalStartDate']; ?></td>
                    <td data-label="End"><?php echo $row['RentalEndDate']; ?></td>
                    <td data-label="Route"><?php echo $row['StartLocation'] ? $row['StartLocation'] . ' → ' . $row['EndLocation'] : 'N/A'; ?></td>
                    <td data-label="Cost">K <?php echo number_format($row['TotalCost'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php if($result->num_rows == 0): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No rentals found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
