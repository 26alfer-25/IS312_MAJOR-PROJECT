
<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $errors = [];
    
    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required";
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../frontend/login.php");
        exit();
    }
    
    $hashed_password = md5($password);
    
    $sql = "SELECT CustomerID, FirstName, LastName, Email, Role FROM Customer 
            WHERE Email = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $customer = $result->fetch_assoc();
        $_SESSION['customer_id'] = $customer['CustomerID'];
        $_SESSION['fullname'] = $customer['FirstName'] . " " . $customer['LastName'];
        $_SESSION['email'] = $customer['Email'];
        $_SESSION['role'] = $customer['Role'];
        
        if ($customer['Role'] == 'admin') {
            header("Location: ../frontend/admin_dashboard.php");
        } else {
            header("Location: ../frontend/index.php?msg=loggedin");
        }
        exit();
    } else {
        $_SESSION['errors'] = ["Invalid email or password"];
        header("Location: ../frontend/login.php");
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../frontend/login.php");
    exit();
}
?>
