<?php
session_start();
include 'db_connect.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete related orders
        $deleteOrdersSql = "DELETE FROM orders WHERE car_id = ?";
        $stmt = $conn->prepare($deleteOrdersSql);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $stmt->close();

        // Delete car from wishlist
        $deleteWishlistSql = "DELETE FROM wishlist WHERE car_id = ?";
        $stmt = $conn->prepare($deleteWishlistSql);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $stmt->close();

        // Delete the car
        $deleteCarSql = "DELETE FROM cars WHERE id = ?";
        $stmt = $conn->prepare($deleteCarSql);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['success'] = "Car deleted successfully.";
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        $_SESSION['error'] = "Error deleting car: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: modify_car.php");
exit;

$conn->close();
?>
