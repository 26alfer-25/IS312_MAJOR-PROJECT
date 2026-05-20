<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id']) && !isset($_GET['customer_id'])) {
    echo json_encode(["error" => "Customer ID required"]);
    exit();
}

$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : $_SESSION['customer_id'];

$sql = "SELECT r.*, c.Brand, c.CarModel, c.RegistrationNumber, c.RentalPrice
        FROM Reservation r
        JOIN Car c ON r.CarID = c.CarID
        WHERE r.CustomerID = ?
        ORDER BY r.ReservationDate DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];

while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

echo json_encode($reservations);

$stmt->close();
$conn->close();
?>

