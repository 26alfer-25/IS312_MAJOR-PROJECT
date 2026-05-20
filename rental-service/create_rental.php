<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id']) && $_SESSION['role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = intval($_POST['reservation_id']);
    $route_id = !empty($_POST['route_id']) ? intval($_POST['route_id']) : null;
    $rental_start = trim($_POST['rental_start']);
    $rental_end = trim($_POST['rental_end']);
    $total_cost = floatval($_POST['total_cost']);
    
    if (empty($reservation_id) || empty($rental_start) || empty($rental_end)) {
        die("Reservation ID, rental start, and rental end are required");
    }
    
    $sql = "INSERT INTO Rental (ReservationID, RouteID, RentalStartDate, RentalEndDate, TotalCost) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissd", $reservation_id, $route_id, $rental_start, $rental_end, $total_cost);
    
    if ($stmt->execute()) {
        header("Location: ../frontend/payment_form.php?rental_id=" . $stmt->insert_id);
        exit();
    } else {
        echo "Error creating rental: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/my_reservations.php");
    exit();
}
?>

