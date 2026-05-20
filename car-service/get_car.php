<?php
header('Content-Type: application/json');
include '../config/db.php';

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Car ID required"]);
    exit();
}

$car_id = intval($_GET['id']);
$sql = "SELECT * FROM car WHERE CarID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Car not found"]);
}

$stmt->close();
$conn->close();
?>
