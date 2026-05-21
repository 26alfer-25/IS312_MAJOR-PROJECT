<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id'];
    $car_id = intval($_POST['car_id']);
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];

    // Validate dates
    if (strtotime($return_date) < strtotime($pickup_date)) {
        die("Return date cannot be before pickup date.");
    }

    // Get car price
    $car_sql = "SELECT RentalPrice FROM car WHERE CarID = ?";
    $car_stmt = $conn->prepare($car_sql);
    $car_stmt->bind_param("i", $car_id);
    $car_stmt->execute();
    $car_result = $car_stmt->get_result();
    $car = $car_result->fetch_assoc();

    if (!$car) {
        die("Car not found.");
    }

    // Calculate days and total cost
    $days = (strtotime($return_date) - strtotime($pickup_date)) / 86400 + 1;
    $total_cost = $days * $car['RentalPrice'];

    // Insert into reservation table
    $sql = "INSERT INTO reservation (CustomerID, CarID, PickupDate, ReturnDate, ReservationDate, Status) 
            VALUES (?, ?, ?, ?, CURDATE(), 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $customer_id, $car_id, $pickup_date, $return_date);

    if ($stmt->execute()) {
        $reservation_id = $stmt->insert_id;

        // Insert into rental table
        $rental_sql = "INSERT INTO rental (ReservationID, RentalStartDate, RentalEndDate, TotalCost) 
                       VALUES (?, ?, ?, ?)";
        $rental_stmt = $conn->prepare($rental_sql);
        $rental_stmt->bind_param("issd", $reservation_id, $pickup_date, $return_date, $total_cost);
        
        if ($rental_stmt->execute()) {
            // Success
        } else {
            echo "Rental insert failed: " . $rental_stmt->error;
            exit();
        }
        $rental_stmt->close();

        // Update car status to Rented
        $update_car = "UPDATE car SET Status = 'Rented' WHERE CarID = ?";
        $car_update = $conn->prepare($update_car);
        $car_update->bind_param("i", $car_id);
        $car_update->execute();
        $car_update->close();

        header("Location: ../frontend/my_reservations.php?msg=reservation_created");
        exit();
    } else {
        echo "Error creating reservation: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/reservation_form.php");
    exit();
}
?>
