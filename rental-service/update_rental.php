
<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id']) && $_SESSION['role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rental_id = intval($_POST['rental_id']);
    $rental_start = trim($_POST['rental_start']);
    $rental_end = trim($_POST['rental_end']);
    $total_cost = floatval($_POST['total_cost']);
    $route_id = !empty($_POST['route_id']) ? intval($_POST['route_id']) : null;
    
    // Admin only for rental updates (or you can allow customers)
    if ($_SESSION['role'] != 'admin') {
        die("Only admins can update rentals");
    }
    
    $sql = "UPDATE Rental 
            SET RentalStartDate = ?, RentalEndDate = ?, TotalCost = ?, RouteID = ? 
            WHERE RentalID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $rental_start, $rental_end, $total_cost, $route_id, $rental_id);
    
    if ($stmt->execute()) {
        header("Location: ../frontend/admin_dashboard.php?msg=rental_updated");
        exit();
    } else {
        echo "Error updating rental: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/admin_dashboard.php");
    exit();
}
?>
