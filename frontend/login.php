<?php
include '../config/db.php';

header('Content-Type: application/json');

if (isset($_GET['student_number'])) {
    $student_number = trim($_GET['student_number']);

    $sql = "SELECT * FROM students WHERE student_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["message" => "Profile not found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["message" => "student_number is required"]);
}
?>
