
<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id']) && $_SESSION['role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $rental_id = intval($_GET['id']);
    
    // Admin only
    if ($_SESSION['role'] != 'admin') {
        die("Only admins can delete rentals");
    }
    
    // Check for linked payment
    $check_sql = "SELECT PaymentID FROM Payment WHERE RentalID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $rental_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Delete payment first
        $delete_payment = "DELETE FROM Payment WHERE RentalID = ?";
        $payment_stmt = $conn->prepare($delete_payment);
        $payment_stmt->bind_param("i", $rental_id);
        $payment_stmt->execute();
        $payment_stmt->close();
    }
    
    // Delete rental
    $sql = "DELETE FROM Rental WHERE RentalID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rental_id);
    
    if ($stmt->execute()) {
        header("Location: ../frontend/admin_dashboard.php?msg=rental_deleted");
        exit();
    } else {
        echo "Error deleting rental: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/admin_dashboard.php");
    exit();
}
?>