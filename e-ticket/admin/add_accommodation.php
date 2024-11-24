
<?php
//Admin Log
function logAddAccommodation($admin_id, $action, $target_id) {
    global $conn;
    $action = 'add accomodation type';
    // Prepare and execute the log insert query
    $stmt = $conn->prepare("INSERT INTO Admin_Actions_Log (admin_id, action, target_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $admin_id, $action, $target_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
//Admin Log
function logAddAccommodationPrice($admin_id, $action, $target_id) {
    global $conn;
    $action = 'add new Accommodation Price';
    // Prepare and execute the log insert query
    $stmt = $conn->prepare("INSERT INTO Admin_Actions_Log (admin_id, action, target_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $admin_id, $action, $target_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
// Handle form submission for adding accommodation and price
if (isset($_POST['add_accommodation'])) {
    $ferry_name = $_POST['ferry_name'];
    $accom_type = $_POST['accom_type'];
    $price = $_POST['price'];

    // 1. Retrieve ferry_id
    $stmt = $conn->prepare("SELECT ferry_id FROM ferries WHERE ferry_name = ?");
    $stmt->bind_param("s", $ferry_name);
    $stmt->execute();
    $stmt->bind_result($ferry_id);
    $stmt->fetch();
    $stmt->close();

    if (!$ferry_id) {
        $error_message = "Ferry not found.";
    } else {
        // Retrieve accom_id
        $stmt = $conn->prepare("SELECT accom_price_id FROM accommodation WHERE accom_type = ?");
        $stmt->bind_param("s", $accom_type);
        $stmt->execute();
        $stmt->bind_result($accom_id);
        $stmt->fetch();
        $stmt->close();

        if (!$accom_id) {
            $error_message = "Accommodation type retrieval failed.";
        } else {
            // 3. Insert price for the accommodation linked to the specific ferry
            $stmt = $conn->prepare("INSERT INTO accommodation_prices (ferry_id, accom_id, price) VALUES (?, ?, ?)");
            $stmt->bind_param("iid", $ferry_id, $accom_id, $price);
            
            if ($stmt->execute()) {
                $success_message = "Accommodation and price added successfully.";
                logAddAccommodationPrice($_SESSION['admin_id'], 'add new Accomodation Price', $ferry_id);
            } else {
                $error_message = "Error adding accommodation: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<!-- Form to Add Accommodation and Price -->
<form method="POST" action="">
    <h3>Add Accommodation Type and Price</h3>
    <div class="input-group">
        <label for="ferry_name">Ferry Name</label>
        <select name="ferry_name" required>
            <option value="">Select Ferry</option>
            <?php 
            // Fetching ferries for the accommodation form
            while ($row = $ferries->fetch_assoc()): ?>
                <option value="<?php echo $row['ferry_name']; ?>"><?php echo $row['ferry_name']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="input-group">
        <label for="accom_type">Accommodation Type</label>
        <select name="accom_type" required>
            <option value="">Select Accommodation Type</option>
            <?php 
            // Fetching accommodation types for the dropdown
            
            while ($row = $accommodation_types->fetch_assoc()): ?>
                <option value="<?php echo $row['accom_type']; ?>"><?php echo $row['accom_type']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="input-group">
        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" placeholder="Enter Price" required>
    </div>

    <div class="input-group">
        <button type="submit" name="add_accommodation" class="btn">Add Accommodation</button>
        <button type="submit" name = "update_accommodation" class = "btn" style="display: inline-block; margin-right: 10px;">Update Accommodation</button>
    </div>
</form>