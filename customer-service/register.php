<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $license_no = trim($_POST['license_no']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validation
    $errors = [];
    
    if (empty($firstname) || empty($lastname)) {
        $errors[] = "First name and last name are required";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($license_no)) {
        $errors[] = "Driver's license number is required";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if email already exists
    $check_sql = "SELECT Email FROM Customer WHERE Email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $errors[] = "Email already registered";
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../frontend/register.php");
        exit();
    }
    
    // Hash password
    $hashed_password = md5($password); // Note: Use password_hash() for production
    
    // Insert customer
    $sql = "INSERT INTO Customer (FirstName, LastName, Phone, Email, Address, DriverLicenseNo, Password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $firstname, $lastname, $phone, $email, $address, $license_no, $hashed_password);
    
    if ($stmt->execute()) {
        $customer_id = $stmt->insert_id;
        $_SESSION['customer_id'] = $customer_id;
        $_SESSION['fullname'] = $firstname . " " . $lastname;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'customer';
        
        header("Location: ../frontend/index.php?msg=registered");
        exit();
    } else {
        $_SESSION['errors'] = ["Registration failed: " . $conn->error];
        header("Location: ../frontend/register.php");
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/register.php");
    exit();
}
?>

