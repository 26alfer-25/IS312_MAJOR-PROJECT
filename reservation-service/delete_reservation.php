
<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $reservation_id = intval($_GET['id']);
    
    // Verify ownership
    $check_sql = "SELECT CustomerID, Status, CarID FROM Reservation WHERE ReservationID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $reservation_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $reservation = $check_result->fetch_assoc();
    
    if (!$reservation) {
        die("Reservation not found");
    }
    
    // Only admin or owner can delete
    if ($reservation['CustomerID'] != $_SESSION['customer_id'] && $_SESSION['role'] != 'admin') {
        die("You don't have permission to delete this reservation");
    }
    
    // Cannot delete completed reservations
    if ($reservation['Status'] == 'Completed') {
        die("Cannot delete completed reservations");
    }
    
    // Get car ID to update status
    $car_id = $reservation['CarID'];
    
    // Delete reservation
    $sql = "DELETE FROM Reservation WHERE ReservationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute()) {
        // Update car status back to Available
        $update_car = "UPDATE Car SET Status = 'Available' WHERE CarID = ?";
        $car_stmt = $conn->prepare($update_car);
        $car_stmt->bind_param("i", $car_id);
        $car_stmt->execute();
        
        header("Location: ../frontend/my_reservations.php?msg=reservation_deleted");
        exit();
    } else {
        echo "Error deleting reservation: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/my_reservations.php");
    exit();
}
?>
