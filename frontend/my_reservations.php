<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT r.*, c.Brand, c.CarModel, c.RegistrationNumber, c.RentalPrice
        FROM Reservation r
        JOIN Car c ON r.CarID = c.CarID
        WHERE r.CustomerID = ?
        ORDER BY r.ReservationDate DESC";
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
    <title>My Reservations - Madang Car Rental</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-confirmed { background: #d1fae5; color: #059669; }
        .status-completed { background: #dbeafe; color: #2563eb; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
    </style>
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
        <h1>My Reservations</h1>
        
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?php 
                    if($_GET['msg'] == 'reservation_created') echo "Reservation created successfully!";
                    if($_GET['msg'] == 'reservation_updated') echo "Reservation updated successfully!";
                    if($_GET['msg'] == 'reservation_deleted') echo "Reservation cancelled successfully!";
                ?>
            </div>
        <?php endif; ?>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): 
                        $status = $row['Status'];
                        $statusClass = '';
                        if ($status == 'Pending') $statusClass = 'status-pending';
                        elseif ($status == 'Confirmed') $statusClass = 'status-confirmed';
                        elseif ($status == 'Completed') $statusClass = 'status-completed';
                        elseif ($status == 'Cancelled') $statusClass = 'status-cancelled';
                    ?>
                    <tr>
                        <td data-label="ID"><?php echo $row['ReservationID']; ?></td>
                        <td data-label="Car"><?php echo htmlspecialchars($row['Brand'] . ' ' . $row['CarModel']); ?></td>
                        <td data-label="Pickup"><?php echo $row['PickupDate']; ?></td>
                        <td data-label="Return"><?php echo $row['ReturnDate']; ?></td>
                        <td data-label="Status">
                            <span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if($result->num_rows == 0): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">You have no reservations yet. <a href="browse_cars.php">Browse cars</a> to make one.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>