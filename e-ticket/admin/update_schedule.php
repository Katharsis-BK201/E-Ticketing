<?php
session_start();
include('C:/xampp/htdocs/e-ticket/config.php'); // Ensure the correct path to your config.php

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the schedule_id is provided
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Query to fetch the current ferry schedule data
    $sql = "SELECT fs.ferry_id, fs.schedule_id, 
    f.ferry_name, fs.departure_port, fs.arrival_port, 
    fs.departure_time, fs.arrival_time, fs.status 
            FROM ferry_schedule fs 
            INNER JOIN ferries f ON fs.ferry_id = f.ferry_id 
            WHERE fs.schedule_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $schedule_id); // Bind the schedule_id to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Schedule not found.";
        exit();
    }
} else {
    echo "No schedule_id provided.";
    exit();
}

// Handle form submission for updating schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ferry_name = $_POST['ferry_name'];
    $departure_port = $_POST['departure_port'];
    $arrival_port = $_POST['arrival_port'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $status = $_POST['status'];

    // Update query to save the new values
    $update_sql = "UPDATE ferry_schedule SET ferry_id = (SELECT ferry_id FROM ferries WHERE ferry_name = ?), 
                  departure_port = ?, arrival_port = ?, departure_time = ?, arrival_time = ?, status = ? 
                  WHERE schedule_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $ferry_name, $departure_port, $arrival_port, $departure_time, $arrival_time, $status, $schedule_id);

    if ($update_stmt->execute()) {
        echo "Schedule updated successfully!";
        header("Location: view_ferries.php"); // Redirect to the ferry schedule view page
        exit();
    } else {
        echo "Error updating schedule: " . $conn->error;
    }
}
$ferry_sql = "SELECT ferry_name FROM ferries";
$ferry_result = $conn->query($ferry_sql);
// Close the database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ferry Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        form {
            width: 100%;
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .submit-btn {
            padding: 10px 20px;
            background: #28a745;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Ferry Schedule</h2>
    <form action="update_schedule.php?schedule_id=<?php echo $schedule_id; ?>" method="POST">
        <label for="ferry_name">Ferry Name:</label>
        <select name="ferry_name" id="ferry_name" required>
            <option value="<?php echo htmlspecialchars($row['ferry_name']); ?>"><?php echo htmlspecialchars($row['ferry_name']); ?></option>
            <?php
                // Fetch all ferry names to allow updates
               
                
                while ($ferry_row = $ferry_result->fetch_assoc()) {
                    if ($ferry_row['ferry_name'] != $row['ferry_name']) {
                        echo "<option value='" . htmlspecialchars($ferry_row['ferry_name']) . "'>" . htmlspecialchars($ferry_row['ferry_name']) . "</option>";
                    }
                }
            ?>
        </select>

        <label for="departure_port">Departure Port:</label>
        <input type="text" name="departure_port" id="departure_port" value="<?php echo htmlspecialchars($row['departure_port']); ?>" required />

        <label for="arrival_port">Arrival Port:</label>
        <input type="text" name="arrival_port" id="arrival_port" value="<?php echo htmlspecialchars($row['arrival_port']); ?>" required />

        <label for="departure_time">Departure Time:</label>
        <input type="time" name="departure_time" id="departure_time" value="<?php echo htmlspecialchars($row['departure_time']); ?>" required />

        <label for="arrival_time">Arrival Time:</label>
        <input type="time" name="arrival_time" id="arrival_time" value="<?php echo htmlspecialchars($row['arrival_time']); ?>" required />

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="active" <?php echo ($row['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo ($row['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
        </select>

        <button type="submit" class="submit-btn">Update Schedule</button>
    </form>
</div>

</body>
</html>
<?php $conn->close(); ?>
