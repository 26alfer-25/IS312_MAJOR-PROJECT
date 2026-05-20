<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT CustomerID, FirstName, LastName, Phone, Email, Address, DriverLicenseNo, CreatedAt 
        FROM Customer WHERE CustomerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Customer not found"]);
}

$stmt->close();
$conn->close();
?>
