<?php
session_start();
include 'db_connect.php'; // Include your database connection file

// Validate request method and retrieve car details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];

    // Fetch the car details from the database
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Car not found.";
        header("Location: modify_car.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: modify_car.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link rel="stylesheet" href="your_custom_styles.css"> <!-- Replace with your CSS file -->
    <style>
        /* Additional styles specific to edit_car.php */
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="number"], select, input[type="file"] {
            padding: 10px;
            margin: 5px 0;
            flex: 1;
            min-width: 150px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'header_admin.php'; // Include your header template ?>

    <div class="container">
        <h2>Edit Car</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <!-- Edit Car Form -->
        <form action="process_edit_car.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
            <input type="text" name="brand" placeholder="Brand" value="<?php echo htmlspecialchars($car['brand']); ?>" required>
            <input type="text" name="model" placeholder="Model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            <input type="number" name="seats" placeholder="Seats No" value="<?php echo $car['seats_no']; ?>" required>
            <input type="number" name="doors" placeholder="Doors" value="<?php echo $car['doors']; ?>" required>
            <select name="fuel" required>
                <option value="">Select Fuel Type</option>
                <option value="Petrol" <?php if ($car['fuel'] === 'Petrol') echo 'selected'; ?>>Petrol</option>
                <option value="Diesel" <?php if ($car['fuel'] === 'Diesel') echo 'selected'; ?>>Diesel</option>
                <option value="Gas" <?php if ($car['fuel'] === 'Gas') echo 'selected'; ?>>Gas</option>
                <option value="Electric" <?php if ($car['fuel'] === 'Electric') echo 'selected'; ?>>Electric</option>
                <option value="Hybrid" <?php if ($car['fuel'] === 'Hybrid') echo 'selected'; ?>>Hybrid</option>
            </select>
            <select name="transmission" required>
                <option value="">Select Transmission</option>
                <option value="Automatic" <?php if ($car['transmission'] === 'Automatic') echo 'selected'; ?>>Automatic</option>
                <option value="Manual" <?php if ($car['transmission'] === 'Manual') echo 'selected'; ?>>Manual</option>
                <option value="CVT" <?php if ($car['transmission'] === 'CVT') echo 'selected'; ?>>CVT</option>
            </select>
            <select name="wheel" required>
                <option value="">Select Wheel Drive</option>
                <option value="2WD" <?php if ($car['wheel'] === '2WD') echo 'selected'; ?>>2 Wheel Drive (2WD)</option>
                <option value="4WD" <?php if ($car['wheel'] === '4WD') echo 'selected'; ?>>4 Wheel Drive (4WD)</option>
            </select>
            <select name="color" required>
                <option value="">Select Color</option>
                <option value="Pearl white" <?php if ($car['color'] === 'Pearl white') echo 'selected'; ?>>Pearl White</option>
                <option value="Metallic maroon" <?php if ($car['color'] === 'Metallic maroon') echo 'selected'; ?>>Metallic Maroon</option>
                <option value="Gray" <?php if ($car['color'] === 'Gray') echo 'selected'; ?>>Gray</option>
                <option value="Matte black" <?php if ($car['color'] === 'Matte black') echo 'selected'; ?>>Matte Black</option>
                <option value="Blue" <?php if ($car['color'] === 'Blue') echo 'selected'; ?>>Blue</option>
                <option value="Silver" <?php if ($car['color'] === 'Silver') echo 'selected'; ?>>Silver</option>
                <option value="Black" <?php if ($car['color'] === 'Black') echo 'selected'; ?>>Black</option>
            </select>
            <input type="number" name="mileage" placeholder="Mileage" value="<?php echo $car['mileage']; ?>" required>
            <input type="number" name="year" placeholder="Year" value="<?php echo $car['year']; ?>" required>
            <input type="file" name="image">
            <button type="submit" name="submit">Update Car</button>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>
