<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$reservation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM Reservation WHERE ReservationID = ? AND CustomerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $reservation_id, $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();

if (!$reservation) {
    die("Reservation not found or you don't have permission to edit it");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation - Madang Car Rental</title>
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
                <a href="../customer-service/logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h1>Edit Reservation #<?php echo $reservation_id; ?></h1>

            <form action="../reservation-service/update_reservation.php" method="POST">
                <input type="hidden" name="reservation_id" value="<?php echo $reservation['ReservationID']; ?>">
                
                <div class="form-group">
                    <label>Pickup Date</label>
                    <input type="date" name="pickup_date" value="<?php echo $reservation['PickupDate']; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Return Date</label>
                    <input type="date" name="return_date" value="<?php echo $reservation['ReturnDate']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Pending" <?php echo $reservation['Status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Confirmed" <?php echo $reservation['Status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="Cancelled" <?php echo $reservation['Status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <button type="submit">Update Reservation</button>
                <a href="my_reservations.php" style="margin-left: 10px;">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
