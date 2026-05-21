<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id'];
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if (empty($firstname) || empty($lastname)) {
        die("First name and last name are required");
    }
    
    $sql = "UPDATE Customer SET FirstName = ?, LastName = ?, Phone = ?, Address = ? 
            WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $firstname, $lastname, $phone, $address, $customer_id);
    
    if ($stmt->execute()) {
        $_SESSION['fullname'] = $firstname . " " . $lastname;
        header("Location: ../frontend/index.php?msg=profile_updated");
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/profile_form.php");
    exit();
