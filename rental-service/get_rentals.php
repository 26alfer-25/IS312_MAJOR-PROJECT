<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

$customer_id = $_SESSION['customer_id'] ?? null;

if (!$customer_id && !isset($_GET['customer_id'])) {
    echo json_encode(["error" => "Customer ID required"]);
    exit();
}

$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : $customer_id;

$sql = "SELECT r.*, res.CustomerID, rt.StartLocation, rt.EndLocation, rt.Distance,
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

$rentals = [];

while ($row = $result->fetch_assoc()) {
    $rentals[] = $row;
}

echo json_encode($rentals);

$stmt->close();
$conn->close();
?>

