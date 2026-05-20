<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$sql = "SELECT * FROM customer WHERE CustomerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>My Profile</h1>
        <div class="card">
            <p><strong>Name:</strong> <?php echo $customer['FirstName'] . ' ' . $customer['LastName']; ?></p>
            <p><strong>Email:</strong> <?php echo $customer['Email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $customer['Phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $customer['Address']; ?></p>
            <p><strong>Driver's License:</strong> <?php echo $customer['DriverLicenseNo']; ?></p>
            <p><strong>Role:</strong> <?php echo $customer['Role']; ?></p>
            <a href="edit_profile.php" class="btn">Edit Profile</a>
            <a href="index.php" class="btn">Back to Home</a>
        </div>
    </div>
</body>
</html>
