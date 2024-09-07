<?php
session_start();
include 'db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];

    // Sanitize and validate inputs
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

    // File upload handling (if image is provided)
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // Define your upload directory
        $temp_name = $_FILES['image']['tmp_name'];
        $original_name = $_FILES['image']['name'];
        $image = $upload_dir . $original_name;

        // Move uploaded file to specified directory
        if (move_uploaded_file($temp_name, $image)) {
            // File uploaded successfully
        } else {
            $_SESSION['error'] = "Failed to upload image.";
            header("Location: edit_car.php");
            exit;
        }
    }

    // Update car details in the database
    $sql = "UPDATE cars SET brand=?, model=?, seats_no=?, doors=?, fuel=?, transmission=?, wheel=?, color=?, mileage=?, year=?, image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssiissssiisi', $brand, $model, $seats, $doors, $fuel, $transmission, $wheel, $color, $mileage, $year, $image, $car_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Car details updated successfully.";
        header("Location: modify_car.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating car details: " . $stmt->error;
        header("Location: edit_car.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: modify_car.php");
    exit;
}

$conn->close();
?>
