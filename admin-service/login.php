<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    
    $sql = "SELECT admin_id, full_name, email FROM admin WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['admin_name'] = $row['full_name'];
        $_SESSION['admin_email'] = $row['email'];
        $_SESSION['role'] = 'admin';
        header("Location: ../frontend/admin_dashboard.php");
        exit();
    } else {
        $_SESSION['errors'] = ["Invalid admin credentials"];
        header("Location: ../frontend/admin_login.php");
        exit();
    }
}
?>
