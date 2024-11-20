<?php
session_start();
include('C:/xampp/htdocs/e-ticket/config.php'); // Ensure the correct path

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
// Fetch all bookings along with ferry schedule and cost details
$query = "
    SELECT 
        username,
        acc_type,
        email,
        phone_num,
        created_at,
        updated_at,
        deleted_at
    FROM 
        users
    WHERE
        acc_type = 'customer'
    ORDER BY 
        user_id ASC";
$result = $conn->query($query);
?>
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
    color: #fff;
    padding: 14px;
    font-size: 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease, transform 0.2s;
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
    <h2>Manage Users</h2>

    <?php if (isset($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Deleted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone_num']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['updated_at']; ?></td>
                        <td><?php echo $row['deleted_at']; ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                <button type="submit" name="action" value="confirm" class="btn-action btn-confirm">Confirm</button>
                                <button type="submit" name="action" value="cancel" class="btn-action btn-cancel">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
     </div>
</body>
</html>