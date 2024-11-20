<?php
session_start();
include('C:/xampp/htdocs/e-ticket/config.php'); // Ensure the correct path

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['customer_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $departure = $_POST['departure'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $departure_date = $_POST['departure_date'] ?? '';
    $passengers = intval($_POST['passengers'] ?? 0); // Ensure it's an integer

} else {
    die("No form data submitted.");
}

// Ensure required fields are present
if (empty($departure) || empty($destination) || empty($departure_date) || $passengers < 1) {
    die("Invalid form data submitted.");
}

// Fetch ferry details
$sql1 = "SELECT f.ferry_name 
         FROM ferries f 
         INNER JOIN ferry_schedule fs ON f.ferry_id = fs.ferry_id
         WHERE departure_port = ? AND arrival_port = ?";
$stmt2 = $conn->prepare($sql1);
if (!$stmt2) {
    die("SQL Error: " . $conn->error);
}
$stmt2->bind_param("ss", $departure, $destination);
$stmt2->execute();
$result = $stmt2->get_result(); // Retrieve the result here
$stmt2->close(); // Close the statement after fetching the result

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Details</title>
    <!-- Include Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Passenger Details</h2>
        <p>Departure: <strong><?= htmlspecialchars($departure) ?></strong></p>
        <p>Destination: <strong><?= htmlspecialchars($destination) ?></strong></p>
        <p>Departure Date: <strong><?= htmlspecialchars($departure_date) ?></strong></p>
        <div class="mb-3">
            <label for="ferry_name" class="form-label">Ferry Name: </label>
            <select name="ferry_name" id="ferry_name" class="form-select" required>
                <option value="" disabled selected>Select Ferry</option>
                <?php
                // Populate ferry options
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['ferry_name'] . "'>" . $row['ferry_name'] . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No Ferries Available</option>";
                }
                ?>
            </select>
        </div>
        <p>Number of Passengers: <strong><?= $passengers ?></strong></p>
        
        <!-- Dynamic Passenger Forms -->
        <form action="process_booking.php" method="POST" enctype="multipart/form-data">
            <?php for ($i = 1; $i <= $passengers; $i++): ?>
                <div class="border rounded p-3 mb-4">
                    <h5>Passenger <?= $i ?></h5>
                    <div class="mb-3">
                        <label for="first_name_<?= $i ?>" class="form-label">First Name</label>
                        <input type="text" name="first_name[]" id="first_name_<?= $i ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="middle_name_<?= $i ?>" class="form-label">Middle Name</label>
                        <input type="text" name="middle_name[]" id="middle_name_<?= $i ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="last_name_<?= $i ?>" class="form-label">Last Name</label>
                        <input type="text" name="last_name[]" id="last_name_<?= $i ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="address_<?= $i ?>" class="form-label">Address</label>
                        <input type="text" name="address[]" id="address_<?= $i ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="birthdate_<?= $i ?>" class="form-label">Birthdate</label>
                        <input type="date" name="birthdate[]" id="birthdate_<?= $i ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nationality_<?= $i ?>" class="form-label">Nationality</label>
                        <input type="text" name="nationality[]" id="nationality_<?= $i ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="passenger_type_<?= $i ?>" class="form-label">Passenger Type</label>
                        <select name="passenger_type[]" id="passenger_type_<?= $i ?>" class="form-select" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Adult">Adult</option>
                            <option value="Child">Child</option>
                            <option value="Senior">Senior</option>
                            <option value="PWD">PWD</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="valid_id_<?= $i ?>" class="form-label">Upload Valid ID</label>
                        <input type="file" name="valid_id[]" id="valid_id_<?= $i ?>" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                </div>
            <?php endfor; ?>
            
            <!-- Hidden Inputs to Pass Booking Data -->
            <input type="hidden" name="departure" value="<?= htmlspecialchars($departure) ?>">
            <input type="hidden" name="destination" value="<?= htmlspecialchars($destination) ?>">
            <input type="hidden" name="passengers" value="<?= $passengers ?>">
            
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Include Bootstrap JS for functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
