<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];

    // Check if the car is already in the wishlist
    $check_sql = "SELECT * FROM wishlist WHERE car_id = '$car_id' AND user_id = '$user_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        // Add car to wishlist
        $insert_sql = "INSERT INTO wishlist (user_id, car_id) VALUES ('$user_id', '$car_id')";
        if ($conn->query($insert_sql) === TRUE) {
            $_SESSION['success'] = "Car added to wishlist successfully.";
        } else {
            $_SESSION['error'] = "Error adding car to wishlist: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Car is already in your wishlist.";
    }
}

// Fetch cars for display (your existing code for fetching and displaying cars)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jaki General Supply</title>
    <link rel="stylesheet" href="customer2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>
<body>
    <?php include 'header_admin.php'; ?>

    <div class="container">
     

        <!-- Slider for Featured Cars -->
        <div class="slider">
            <?php
            $sliderCars = $conn->query("SELECT brand, model, price, image FROM cars LIMIT 5");
            while ($row = $sliderCars->fetch_assoc()) {
                echo '<div class="slick-slide">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['brand'] . ' ' . $row['model'] . '">';
                echo '<div class="slide-details">';
                echo '<p><strong>' /*. $row['brand']*/ . ' ' . $row['model'] . '</strong></p>';
                echo '<p>Price: TZS ' . number_format($row['price']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- Search Form -->
        <form action="index.php" method="GET" class="search-form">
            <select name="brand">
                <option value="">Select Brand</option>
                <?php
                $brands = $conn->query("SELECT DISTINCT brand FROM cars ORDER BY brand");
                while ($row = $brands->fetch_assoc()) {
                    echo '<option value="' . $row['brand'] . '">' . $row['brand'] . '</option>';
                }
                ?>
            </select>

            <select name="model">
                <option value="">Select Model</option>
                <?php
                $models = $conn->query("SELECT DISTINCT model FROM cars ORDER BY model");
                while ($row = $models->fetch_assoc()) {
                    echo '<option value="' . $row['model'] . '">' . $row['model'] . '</option>';
                }
                ?>
            </select>

            <select name="fuel">
                <option value="">Select Fuel</option>
                <?php
                $fuels = $conn->query("SELECT DISTINCT fuel FROM cars ORDER BY fuel");
                while ($row = $fuels->fetch_assoc()) {
                    echo '<option value="' . $row['fuel'] . '">' . $row['fuel'] . '</option>';
                }
                ?>
            </select>

            <select name="transmission">
                <option value="">Select Transmission</option>
                <?php
                $transmissions = $conn->query("SELECT DISTINCT transmission FROM cars ORDER BY transmission");
                while ($row = $transmissions->fetch_assoc()) {
                    echo '<option value="' . $row['transmission'] . '">' . $row['transmission'] . '</option>';
                }
                ?>
            </select>

            <select name="year">
                <option value="">Select Year</option>
                <?php
                $years = $conn->query("SELECT DISTINCT year FROM cars ORDER BY year DESC");
                while ($row = $years->fetch_assoc()) {
                    echo '<option value="' . $row['year'] . '">' . $row['year'] . '</option>';
                }
                ?>
            </select>

            <input type="submit" value="Search">
        </form>

        <!-- Display All Cars -->
        <h3>All Cars</h3>
        <div class="car-grid">
            <?php
            // Handle search filters
            $filters = [];
            if (!empty($_GET['brand'])) {
                $filters[] = "brand = '" . $conn->real_escape_string($_GET['brand']) . "'";
            }
            if (!empty($_GET['model'])) {
                $filters[] = "model = '" . $conn->real_escape_string($_GET['model']) . "'";
            }
            if (!empty($_GET['fuel'])) {
                $filters[] = "fuel = '" . $conn->real_escape_string($_GET['fuel']) . "'";
            }
            if (!empty($_GET['transmission'])) {
                $filters[] = "transmission = '" . $conn->real_escape_string($_GET['transmission']) . "'";
            }
            if (!empty($_GET['year'])) {
                $filters[] = "year = '" . $conn->real_escape_string($_GET['year']) . "'";
            }

            $sql = "SELECT id, brand, model, price, image, fuel, mileage, year FROM cars";
            if (count($filters) > 0) {
                $sql .= " WHERE " . implode(' AND ', $filters);
            }
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="car-item">';
                    echo '<a href="car_information.php?id=' . $row['id'] . '" class="car-link">';
                    echo '<img class="car-image" src="' . $row['image'] . '" alt="' . $row['brand'] . ' ' . $row['model'] . '">';
                    echo '<div class="car-price">TZS ' . number_format($row['price']) . '</div>';
                    echo '<div class="car-details">';
                    echo '<p><strong>' /*. $row['brand'] */. ' ' . $row['model'] . '</strong></p>';
                    echo '<p>Fuel: ' . $row['fuel'] . '</p>';
                    echo '<p>Mileage: ' . $row['mileage'] . '</p>';
                    echo '<p>Year: ' . $row['year'] . '</p>';
                    echo '</div>';
                    echo '</a>';
                    echo '<form action="home.php" method="POST">';
                    echo '<input type="hidden" name="car_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="order-button">Order Now</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p>No cars found.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.slider').slick({
                autoplay: true,
                autoplaySpeed: 3000,
                dots: true,
                arrows: true,
                slidesToShow: 1,
                slidesToScroll: 1
            });
        });
    </script>
</body>
</html>
