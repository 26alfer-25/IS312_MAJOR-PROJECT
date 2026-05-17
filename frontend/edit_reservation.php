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

    $sql = "INSERT INTO students (student_number, full_name, email, bio, skills, github_username)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $student_number, $full_name, $email, $bio, $skills, $github_username);

    if ($stmt->execute()) {
        header("Location: ../frontend/index.php?message=Profile created successfully");
        exit();
    } else {
        echo "Error creating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
