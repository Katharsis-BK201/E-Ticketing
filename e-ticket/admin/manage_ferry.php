<?php
session_start();
include('C:/xampp/htdocs/e-ticket/config.php'); // Ensure the correct path

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
// Function to log admin actions
function logAddFerry($admin_id, $action, $target_id) {
    global $conn;
    $action = 'add new ferry';
    // Prepare and execute the log insert query
    $stmt = $conn->prepare("INSERT INTO Admin_Actions_Log (admin_id, action, target_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $admin_id, $action, $target_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Handle form submission for adding a ferry
if (isset($_POST['add_ferry'])) {
    $ferry_name = $_POST['ferry_name'];
    $departure_port = $_POST['departure_port'];
    $arrival_port = $_POST['arrival_port'];
    $status = $_POST['status'];

    // Directly get time in 24-hour format
    $departure_time = $_POST['departure_time']; // e.g., "15:30"
    $arrival_time = $_POST['arrival_time']; // e.g., "03:45"

    // Insert ferry details into the database
    $stmt = $conn->prepare("INSERT INTO ferries (ferry_name) VALUES (?)");
    $stmt->bind_param("s", $ferry_name);
    if ($stmt->execute()) {
        // Get the ferry_id of the newly inserted ferry
        $ferry_id = $conn->insert_id;

        // Insert ferry schedule details, using the retrieved ferry_id
        $stmt = $conn->prepare("INSERT INTO ferry_schedule (ferry_id, departure_port, arrival_port, departure_time, arrival_time, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $ferry_id, $departure_port, $arrival_port, $departure_time, $arrival_time, $status);
        if ($stmt->execute()) {
            // Log the admin action
            logAddFerry($_SESSION['admin_id'], 'add new ferry', $ferry_id);
            $success_message = "Ferry and schedule added successfully.";
        } else {
            $error_message = "Error adding ferry schedule: " . $stmt->error;
        }
    } else {
        $error_message = "Error adding ferry: " . $stmt->error;
    }
    $stmt->close();
}





// Fetch list of ferries for displaying in dropdown
$ferries = $conn->query("SELECT ferry_id, ferry_name FROM ferries ORDER BY ferry_name");
$accommodation_types = $conn->query("SELECT * FROM accommodation");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Ferries</title>
<link rel="stylesheet" href="C:/xampp/htdocs/e-ticket/style.css">
<style>
/* General Body Styling */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
    color: #333;
}

/* Container Styling */
.container {
    width: 85%;
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 12px;
}

/* Header Styling */
h2 {
    text-align: center;
    color: #4a4a4a;
    margin-bottom: 30px;
    font-size: 28px;
    letter-spacing: 1px;
}

h3 {
    color: #5c636a;
    margin-bottom: 20px;
    font-size: 22px;
}

/* Success and Error Messages */
.success-message {
    color: #155724;
    background-color: #d4edda;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #c3e6cb;
    text-align: center;
    margin-bottom: 20px;
}

.error-message {
    color: #721c24;
    background-color: #f8d7da;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #f5c6cb;
    text-align: center;
    margin-bottom: 20px;
}

/* Input Groups */
.input-group {
    margin-bottom: 20px;
}

.input-group input,
.input-group select {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    box-sizing: border-box;
    transition: border-color 0.2s;
}

.input-group input:focus,
.input-group select:focus {
    border-color: #007bff;
    outline: none;
}

.input-group select {
    cursor: pointer;
    background-color: #fff;
}

/* Button Styling */
.btn {
    background-color: #007bff; 
        color: white; 
        padding: 14px; 
        font-size: 18px; 
        border: none; 
        border-radius: 8px; 
        cursor: pointer; 
        transition: background-color 0.3s ease, transform 0.2s; 
        display: inline-block; 
        width: 48%; 
        margin-right: 5%;
}

.btn:hover {
    background-color: #0056b3;
    transform: scale(1.02);
}

/* Form Styling */
form {
    background-color: #f7f9fc;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 15px;
    border: 1px solid #dee2e6;
    text-align: left;
    font-size: 16px;
}

table th {
    background-color: #e9ecef;
    color: #343a40;
    font-weight: bold;
}

table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

table tbody tr:hover {
    background-color: #e2e6ea;
}

/* Responsive Styling */
@media (max-width: 768px) {
    .container {
        width: 95%;
        padding: 15px;
    }

    .input-group input,
    .input-group select {
        font-size: 14px;
        padding: 10px;
    }

    .btn {
        font-size: 16px;
        padding: 12px;
    }
}

/* Navigation Menu Styling */
.menu {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    gap: 15px;
}

.menu a,
.btn-logout {
    padding: 12px 20px;
    background: #17a2b8;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.menu a:hover,
.btn-logout:hover {
    background: #138496;
}

/* Logout Button Specific */
.btn-logout {
    background: #dc3545;
}

.btn-logout:hover {
    background: #c82333;
}


</style>
</head>
<body>
    <br><br>
<div class="menu">
        <a href="manage_users.php">Manage Users</a>
        <a href="view_reservation.php">View Bookings</a>
        <a href="manage_ferry.php">Manage Ferry Schedule</a>
        <a href="reports.php">Reports</a>
        <button onclick="logout()" class="btn-logout">Logout</button>
    </div>
</div>

<script>
    function logout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = 'admin_login.php';
        }
    }
</script>
<div class="container">
    <h2>Manage Ferries</h2>

    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

<!-- Form to Add a Ferry -->
<form method="POST" action="">
    <h3>Add Ferry</h3>
    <div class="input-group">
        <input type="text" name="ferry_name" placeholder="Ferry Name" required>
    </div>
    <div class="input-group">
        <input type="text" name="departure_port" placeholder="Departure Port" required>
    </div>
    <div class="input-group">
        <input type="text" name="arrival_port" placeholder="Arrival Port" required>
    </div>
    <div class="input-group">
        <label for="departure_time">Departure Time</label>
        <input type="time" id="departure_time" name="departure_time" required>
    </div>

    <div class="input-group">
        <label for="arrival_time">Arrival Time</label>
        <input type="time" id="arrival_time" name="arrival_time" required>
    </div>

    <div class="input-group">
        <select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <button type="submit" name="add_ferry" class="btn" style="display: inline-block; margin-right: 10px;">Add Ferry</button>
    <button type="submit" name = "update_ferry" class = "btn" style="display: inline-block; margin-right: 10px;">Update Ferry</button>
</form>
</body>
</html>
