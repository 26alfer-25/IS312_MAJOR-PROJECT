<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$rental_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM rental WHERE RentalID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rental_id);
$stmt->execute();
$result = $stmt->get_result();
$rental = $result->fetch_assoc();

if (!$rental) {
    die("Rental not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Rental</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Rental #<?php echo $rental_id; ?></h1>
        <div class="card">
            <form action="../rental-service/update_rental.php" method="POST">
                <input type="hidden" name="rental_id" value="<?php echo $rental['RentalID']; ?>">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="rental_start" value="<?php echo $rental['RentalStartDate']; ?>" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="rental_end" value="<?php echo $rental['RentalEndDate']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Total Cost (K)</label>
                    <input type="number" step="0.01" name="total_cost" value="<?php echo $rental['TotalCost']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Route ID (optional)</label>
                    <input type="number" name="route_id" value="<?php echo $rental['RouteID']; ?>">
                </div>
                <button type="submit">Update Rental</button>
                <a href="admin_dashboard.php">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
