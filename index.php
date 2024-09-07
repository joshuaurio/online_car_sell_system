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

// Handle feedback form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'])) {
    $feedback = $conn->real_escape_string($_POST['feedback']);

    $insert_feedback_sql = "INSERT INTO feedback (user_id, message) VALUES ('$user_id', '$feedback')";
    if ($conn->query($insert_feedback_sql) === TRUE) {
        $_SESSION['success'] = "Thank you for your feedback!";
    } else {
        $_SESSION['error'] = "Error submitting feedback: " . $conn->error;
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
    <style>
        .feedback-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feedback-form-container {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 300px;
            z-index: 1000;
        }

        .feedback-form-container textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .feedback-form-container .form-button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .feedback-form-container .form-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'header_customer.php'; ?>

    <div class="container">
        

        <!-- Slider for Featured Cars -->
        <div class="slider">
            <?php
            $sliderCars = $conn->query("SELECT brand, model, price, image FROM cars LIMIT 5");
            while ($row = $sliderCars->fetch_assoc()) {
                echo '<div class="slick-slide">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['brand'] . ' ' . $row['model'] . '">';
                echo '<div class="slide-details">';
                echo '<p><strong>' . $row['model'] . '</strong></p>';
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
                    echo '<form action="index.php" method="POST">';
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

    <!-- Floating Feedback Button -->
    <button class="feedback-button" onclick="toggleFeedbackForm()">&#9993;</button>

    <!-- Feedback Form Container -->
    <div class="feedback-form-container" id="feedbackForm">
        <h3>Feedback</h3>
        <form action="index.php" method="POST">
            <textarea name="feedback" placeholder="Write your feedback here..."></textarea>
            <button type="submit" class="form-button">Send</button>
        </form>
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

        function toggleFeedbackForm() {
            var form = document.getElementById('feedbackForm');
            if (form.style.display === 'block') {
                form.style.display = 'none';
            } else {
                form.style.display = 'block';
            }
        }
    </script>
</body>
</html>
