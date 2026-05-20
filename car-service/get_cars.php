<?php
header('Content-Type: application/json');
include '../config/db.php';

$sql = "SELECT * FROM car ORDER BY CarID DESC";
$result = $conn->query($sql);

$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

echo json_encode($cars);
$conn->close();
?>

