
<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = intval($_POST['reservation_id']);
    $pickup_date = trim($_POST['pickup_date']);
    $return_date = trim($_POST['return_date']);
    $status = trim($_POST['status']);
    
    // Verify ownership (customer can only edit their own)
    $check_sql = "SELECT CustomerID FROM Reservation WHERE ReservationID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $reservation_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $reservation = $check_result->fetch_assoc();
    
    if (!$reservation) {
        die("Reservation not found");
    }
    
    // Admin or owner can edit
    if ($reservation['CustomerID'] != $_SESSION['customer_id'] && $_SESSION['role'] != 'admin') {
        die("You don't have permission to edit this reservation");
    }
    
    // Validate dates
    if (strtotime($pickup_date) < strtotime(date('Y-m-d'))) {
        die("Pickup date cannot be in the past");
    }
    
    if (strtotime($return_date) < strtotime($pickup_date)) {
        die("Return date must be after pickup date");
    }
    
    $sql = "UPDATE Reservation 
            SET PickupDate = ?, ReturnDate = ?, Status = ? 
            WHERE ReservationID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $pickup_date, $return_date, $status, $reservation_id);
    
    if ($stmt->execute()) {
        header("Location: ../frontend/my_reservations.php?msg=reservation_updated");
        exit();
    } else {
        echo "Error updating reservation: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/my_reservations.php");
    exit();
}
?>
