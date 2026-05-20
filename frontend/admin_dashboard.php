<?php
session_start();
include '../config/db.php';

// Only admin can access
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

// ========== HANDLE ACTIONS ==========

// Add new car
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_car'])) {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $type = $_POST['car_type'];
    $reg = $_POST['registration'];
    $price = $_POST['rental_price'];
    $status = 'Available';
    $insert = $conn->prepare("INSERT INTO car (Brand, CarModel, CarType, RegistrationNumber, RentalPrice, Status) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssds", $brand, $model, $type, $reg, $price, $status);
    $insert->execute();
    header("Location: admin_dashboard.php?msg=Car added&tab=cars");
    exit();
}

// Update car
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_car'])) {
    $car_id = $_POST['car_id'];
    $status = $_POST['status'];
    $price = $_POST['rental_price'];
    $update = $conn->prepare("UPDATE car SET Status = ?, RentalPrice = ? WHERE CarID = ?");
    $update->bind_param("sdi", $status, $price, $car_id);
    $update->execute();
    header("Location: admin_dashboard.php?msg=Car updated&tab=cars");
    exit();
}

// Delete car
if (isset($_GET['delete_car'])) {
    $car_id = $_GET['delete_car'];
    $conn->query("DELETE FROM car WHERE CarID = $car_id");
    header("Location: admin_dashboard.php?msg=Car deleted&tab=cars");
    exit();
}

// ✅ UPDATE RESERVATION STATUS
$update_msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_reservation_status'])) {
    $res_id = intval($_POST['reservation_id']);
    $new_status = $_POST['new_status'];
    $allowed = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
    if (in_array($new_status, $allowed)) {
        $update = $conn->prepare("UPDATE reservation SET Status = ? WHERE ReservationID = ?");
        $update->bind_param("si", $new_status, $res_id);
        if ($update->execute()) {
            $update_msg = "✅ Reservation #$res_id status updated to <strong>$new_status</strong>";
        } else {
            $update_msg = "❌ Database error: " . $conn->error;
        }
    } else {
        $update_msg = "❌ Invalid status value.";
    }
}

// Delete reservation
if (isset($_GET['delete_reservation'])) {
    $res_id = $_GET['delete_reservation'];
    $car = $conn->query("SELECT CarID FROM reservation WHERE ReservationID = $res_id")->fetch_assoc();
    if ($car) {
        $conn->query("UPDATE car SET Status = 'Available' WHERE CarID = " . $car['CarID']);
    }
    $conn->query("DELETE FROM reservation WHERE ReservationID = $res_id");
    header("Location: admin_dashboard.php?msg=Reservation deleted&tab=reservations");
    exit();
}

// Delete rental
if (isset($_GET['delete_rental'])) {
    $rental_id = $_GET['delete_rental'];
    $conn->query("DELETE FROM payment WHERE RentalID = $rental_id");
    $conn->query("DELETE FROM rental WHERE RentalID = $rental_id");
    header("Location: admin_dashboard.php?msg=Rental deleted&tab=rentals");
    exit();
}

// ========== FETCH DATA ==========
$cars = $conn->query("SELECT * FROM car ORDER BY CarID DESC");
$reservations = $conn->query("
    SELECT 
        r.ReservationID, 
        r.PickupDate, 
        r.ReturnDate, 
        r.Status,
        c.Brand, 
        c.CarModel, 
        cust.FirstName, 
        cust.LastName
    FROM reservation r
    JOIN car c ON r.CarID = c.CarID
    JOIN customer cust ON r.CustomerID = cust.CustomerID
    ORDER BY r.ReservationDate DESC
");
$rentals = $conn->query("
    SELECT rl.*, res.CustomerID, cust.FirstName, cust.LastName, c.Brand, c.CarModel
    FROM rental rl
    JOIN reservation res ON rl.ReservationID = res.ReservationID
    JOIN car c ON res.CarID = c.CarID
    JOIN customer cust ON res.CustomerID = cust.CustomerID
    ORDER BY rl.RentalStartDate DESC
");
$customers = $conn->query("SELECT CustomerID, FirstName, LastName, Email, Phone, Role FROM customer ORDER BY CustomerID DESC");

// Statistics
$total_cars = $conn->query("SELECT COUNT(*) as count FROM car")->fetch_assoc()['count'];
$available_cars = $conn->query("SELECT COUNT(*) as count FROM car WHERE Status='Available'")->fetch_assoc()['count'];
$total_reservations = $conn->query("SELECT COUNT(*) as count FROM reservation")->fetch_assoc()['count'];
$pending_reservations = $conn->query("SELECT COUNT(*) as count FROM reservation WHERE Status='Pending'")->fetch_assoc()['count'];
$total_customers = $conn->query("SELECT COUNT(*) as count FROM customer")->fetch_assoc()['count'];
$total_rentals = $conn->query("SELECT COUNT(*) as count FROM rental")->fetch_assoc()['count'];

// Get active tab from URL, default 'cars'
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'cars';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Madang Car Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: url('assets/images/car_image_4.webp') center/cover no-repeat; background-color: #f0f4f8; color: #333; line-height: 1.6; }
        .dashboard-container { max-width: 1400px; margin: 0 auto; padding: 24px; }
        .dashboard-header { background: white; border-radius: 20px; padding: 20px 30px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .logo-area h1 { font-size: 24px; color: #1e3c72; }
        .logo-area p { font-size: 14px; color: #6c757d; }
        .nav-links a { color: #4a5568; text-decoration: none; margin-left: 25px; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: #e67e22; }
        .logout-btn { background: #e74c3c; color: white !important; padding: 8px 18px; border-radius: 8px; }
        .logout-btn:hover { background: #c0392b; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .stat-title { font-size: 14px; font-weight: 500; color: #6c757d; margin-bottom: 8px; }
        .stat-number { font-size: 32px; font-weight: 700; color: #1e3c72; }
        .tabs { display: flex; gap: 12px; margin-bottom: 25px; flex-wrap: wrap; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .tab-btn { background: none; border: none; padding: 10px 24px; font-size: 15px; font-weight: 600; cursor: pointer; border-radius: 30px; color: #4a5568; transition: all 0.2s; }
        .tab-btn.active { background: #1e3c72; color: white; box-shadow: 0 2px 8px rgba(30,60,114,0.3); }
        .tab-btn:hover:not(.active) { background: #e2e8f0; }
        .tab-content { display: none; background: white; border-radius: 20px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .tab-content.active { display: block; }
        .data-table { width: 100%; border-collapse: collapse; overflow-x: auto; display: block; }
        .data-table th, .data-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .data-table th { background: #f8fafc; font-weight: 600; color: #1e3c72; }
        .data-table tr:hover { background: #f8fafc; }
        .inline-form { display: inline-flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        select, input[type="number"] { padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; }
        .btn-sm { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #1e3c72; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .add-form { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
        .add-form input { padding: 10px; border: 1px solid #cbd5e1; border-radius: 10px; }
        .msg { background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 500; }
        @media (max-width: 768px) { .dashboard-header { flex-direction: column; gap: 15px; text-align: center; } .data-table { font-size: 12px; } .data-table th, .data-table td { padding: 8px; } }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            let tab = urlParams.get('tab');
            if (!tab) tab = 'cars';
            showTab(tab);
        });
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            const buttons = document.querySelectorAll('.tab-btn');
            for (let btn of buttons) {
                if ((tabName === 'cars' && btn.textContent.includes('Manage Cars')) ||
                    (tabName === 'reservations' && btn.textContent.includes('Reservations')) ||
                    (tabName === 'rentals' && btn.textContent.includes('Rentals')) ||
                    (tabName === 'customers' && btn.textContent.includes('Customers'))) {
                    btn.classList.add('active');
                    break;
                }
            }
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        }
    </script>
</head>
<body>
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="logo-area">
            <h1>🚗 Madang Car Rental</h1>
            <p>Admin Control Panel</p>
        </div>
        <div class="nav-links">
            <a href="../admin-service/logout.php" class="logout-btn">🔓 Logout</a>
        </div>
    </div>

    <?php if ($update_msg): ?>
        <div class="msg"><?php echo $update_msg; ?></div>
    <?php elseif(isset($_GET['msg'])): ?>
        <div class="msg">✓ <?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card"><div class="stat-title">Total Cars</div><div class="stat-number"><?php echo $total_cars; ?></div></div>
        <div class="stat-card"><div class="stat-title">Available Cars</div><div class="stat-number"><?php echo $available_cars; ?></div></div>
        <div class="stat-card"><div class="stat-title">Total Reservations</div><div class="stat-number"><?php echo $total_reservations; ?></div></div>
        <div class="stat-card"><div class="stat-title">Pending Reservations</div><div class="stat-number"><?php echo $pending_reservations; ?></div></div>
        <div class="stat-card"><div class="stat-title">Active Rentals</div><div class="stat-number"><?php echo $total_rentals; ?></div></div>
        <div class="stat-card"><div class="stat-title">Customers</div><div class="stat-number"><?php echo $total_customers; ?></div></div>
    </div>

    <div class="tabs">
        <button class="tab-btn" onclick="showTab('cars')">🚙 Manage Cars</button>
        <button class="tab-btn" onclick="showTab('reservations')">📅 Reservations</button>
        <button class="tab-btn" onclick="showTab('rentals')">📋 Rentals</button>
        <button class="tab-btn" onclick="showTab('customers')">👥 Customers</button>
    </div>

    <!-- Tab 1: Cars -->
    <div id="cars" class="tab-content">
        <h2 style="margin-bottom: 20px;">🚗 All Cars</h2>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Brand</th><th>Model</th><th>Type</th><th>Reg No.</th><th>Price (K)</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php while ($car = $cars->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $car['CarID']; ?></td>
                        <td><?php echo htmlspecialchars($car['Brand']); ?></td>
                        <td><?php echo htmlspecialchars($car['CarModel']); ?></td>
                        <td><?php echo htmlspecialchars($car['CarType']); ?></td>
                        <td><?php echo htmlspecialchars($car['RegistrationNumber']); ?></td>
                        <td>K <?php echo number_format($car['RentalPrice'], 2); ?></td>
                        <td>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="car_id" value="<?php echo $car['CarID']; ?>">
                                <select name="status">
                                    <option <?php echo $car['Status']=='Available'?'selected':''; ?>>Available</option>
                                    <option <?php echo $car['Status']=='Rented'?'selected':''; ?>>Rented</option>
                                    <option <?php echo $car['Status']=='Maintenance'?'selected':''; ?>>Maintenance</option>
                                </select>
                                <input type="number" name="rental_price" value="<?php echo $car['RentalPrice']; ?>" step="10" style="width:90px;">
                                <button type="submit" name="update_car" class="btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                        <td><a href="?delete_car=<?php echo $car['CarID']; ?>&tab=cars" class="btn-sm btn-danger" onclick="return confirm('Delete this car?')">Delete</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="add-form">
            <input type="text" name="brand" placeholder="Brand" form="addCarForm" required>
            <input type="text" name="model" placeholder="Model" form="addCarForm" required>
            <input type="text" name="car_type" placeholder="Type (Sedan/SUV)" form="addCarForm">
            <input type="text" name="registration" placeholder="Reg No." form="addCarForm" required>
            <input type="number" name="rental_price" placeholder="Price/day" step="10" form="addCarForm" required>
            <form id="addCarForm" method="POST" style="display:inline;">
                <button type="submit" name="add_car" class="btn-sm btn-primary">+ Add Car</button>
            </form>
        </div>
    </div>

    <!-- Tab 2: Reservations - WORKING UPDATE -->
    <div id="reservations" class="tab-content">
        <h2 style="margin-bottom: 20px;">📅 All Customer Reservations</h2>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>Customer</th><th>Car</th><th>Pickup</th><th>Return</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $reservations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ReservationID']; ?></td>
                        <td><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Brand'] . ' ' . $row['CarModel']); ?></td>
                        <td><?php echo $row['PickupDate']; ?></td>
                        <td><?php echo $row['ReturnDate']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="reservation_id" value="<?php echo $row['ReservationID']; ?>">
                                <select name="new_status" style="padding: 6px; border-radius: 6px;">
                                    <option value="Pending" <?php echo ($row['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Confirmed" <?php echo ($row['Status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="Completed" <?php echo ($row['Status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="Cancelled" <?php echo ($row['Status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_reservation_status" class="btn-sm btn-success">Update</button>
                            </form>
                        </td>
                        <td><a href="?delete_reservation=<?php echo $row['ReservationID']; ?>&tab=reservations" class="btn-sm btn-danger" onclick="return confirm('Delete this reservation?')">Delete</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 3: Rentals -->
    <div id="rentals" class="tab-content">
        <h2 style="margin-bottom: 20px;">📋 All Rentals</h2>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Customer</th><th>Car</th><th>Start Date</th><th>End Date</th><th>Total Cost</th><th>Action</th></tr></thead>
                <tbody>
                    <?php while ($row = $rentals->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['RentalID']; ?></td>
                        <td><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Brand'] . ' ' . $row['CarModel']); ?></td>
                        <td><?php echo $row['RentalStartDate']; ?></td>
                        <td><?php echo $row['RentalEndDate']; ?></td>
                        <td>K <?php echo number_format($row['TotalCost'], 2); ?></td>
                        <td><a href="?delete_rental=<?php echo $row['RentalID']; ?>&tab=rentals" class="btn-sm btn-danger" onclick="return confirm('Delete this rental?')">Delete</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 4: Customers -->
    <div id="customers" class="tab-content">
        <h2 style="margin-bottom: 20px;">👥 Registered Customers</h2>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th></tr></thead>
                <tbody>
                    <?php while ($row = $customers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['CustomerID']; ?></td>
                        <td><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['Phone']); ?></td>
                        <td><?php echo $row['Role']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
