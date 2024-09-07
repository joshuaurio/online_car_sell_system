<?php
session_start();
include 'db_connect.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get form data
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $seats = $_POST['seats'];
    $doors = $_POST['doors'];
    $fuel = $_POST['fuel'];
    $transmission = $_POST['transmission'];
    $wheel = $_POST['wheel'];
    $color = $_POST['color'];
    $mileage = $_POST['mileage'];
    $year = $_POST['year'];
    $price = $_POST['price'];

    // Handle file upload
    $target_dir = "uploads/";
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate image file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        $_SESSION['error'] = "Only JPG, JPEG, PNG, & GIF files are allowed.";
        header("Location: add_car.php");
        exit;
    }

    // Move uploaded file to the target directory
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $_SESSION['error'] = "Error uploading image file.";
        header("Location: add_car.php");
        exit;
    }

    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO cars (brand, model, seats_no, doors, fuel, transmission, wheel, color, mileage, year, price, image) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiissssiiss", $brand, $model, $seats, $doors, $fuel, $transmission, $wheel, $color, $mileage, $year, $price, $target_file);

    // Execute the prepared statement
    if ($stmt->execute()) {
        $_SESSION['success'] = "Car added successfully.";
    } else {
        $_SESSION['error'] = "Error adding car: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    header("Location: add_car.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: add_car.php");
    exit;
}
?>
