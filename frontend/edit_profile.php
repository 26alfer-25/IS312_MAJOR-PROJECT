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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <div class="card">
            <form action="../customer-service/update_customer.php" method="POST">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="firstname" value="<?php echo $customer['FirstName']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="lastname" value="<?php echo $customer['LastName']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo $customer['Phone']; ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address"><?php echo $customer['Address']; ?></textarea>
                </div>
                <button type="submit">Update Profile</button>
                <a href="profile.php">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
