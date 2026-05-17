<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = trim($_POST['student_number']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    $skills = trim($_POST['skills']);
    $github_username = trim($_POST['github_username']);

    if (empty($student_number) || empty($full_name)) {
        die("Student number and full name are required.");
    }

    $sql = "UPDATE students
            SET full_name = ?, email = ?, bio = ?, skills = ?, github_username = ?
            WHERE student_number = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $full_name, $email, $bio, $skills, $github_username, $student_number);

    if ($stmt->execute()) {
        header("Location: ../frontend/index.php?message=Profile updated successfully");
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
