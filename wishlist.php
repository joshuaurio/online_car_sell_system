<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_id'])) {
        $remove_id = $_POST['remove_id'];
        $delete_sql = "DELETE FROM wishlist WHERE id = '$remove_id' AND user_id = '$user_id'";
        if ($conn->query($delete_sql) === TRUE) {
            $_SESSION['success'] = "Car removed from wishlist successfully.";
        } else {
            $_SESSION['error'] = "Error removing car from wishlist: " . $conn->error;
        }
    }

    if (isset($_POST['process_id'])) {
        $process_id = $_POST['process_id'];
        $select_sql = "SELECT car_id FROM wishlist WHERE id = '$process_id' AND user_id = '$user_id'";
        $result = $conn->query($select_sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $car_id = $row['car_id'];
            $insert_sql = "INSERT INTO orders (user_id, car_id, order_date) VALUES ('$user_id', '$car_id', NOW())";
            if ($conn->query($insert_sql) === TRUE) {
                $delete_sql = "DELETE FROM wishlist WHERE id = '$process_id' AND user_id = '$user_id'";
                if ($conn->query($delete_sql) === TRUE) {
                    $_SESSION['success'] = "Car processed and moved to orders successfully.";
                } else {
                    $_SESSION['error'] = "Error removing car from wishlist: " . $conn->error;
                }
            } else {
                $_SESSION['error'] = "Error processing car: " . $conn->error;
            }
        } else {
            $_SESSION['error'] = "Car not found in wishlist.";
        }
    }

    if (isset($_POST['feedback'])) {
        $feedback = $conn->real_escape_string($_POST['feedback']);
        $insert_feedback_sql = "INSERT INTO feedback (user_id, message) VALUES ('$user_id', '$feedback')";
        if ($conn->query($insert_feedback_sql) === TRUE) {
            $_SESSION['success'] = "Thank you for your feedback!";
        } else {
            $_SESSION['error'] = "Error submitting feedback: " . $conn->error;
        }
    }
}

// Fetch wishlist items
$wishlist_sql = "SELECT w.id AS wishlist_id, c.id AS car_id, c.brand, c.model, c.price, c.image
                 FROM wishlist w
                 INNER JOIN cars c ON w.car_id = c.id
                 WHERE w.user_id = '$user_id'";
$wishlist_result = $conn->query($wishlist_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .wishlist-item {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
            display: flex;
            align-items: center;
            background-color: #fff;
            border-radius: 8px;
        }

        .car-image {
            width: 100px;
            height: auto;
            margin-right: 10px;
            border-radius: 6px;
        }

        .details {
            flex: 1;
        }

        .details p {
            margin: 5px 0;
            color: #666;
        }

        .remove-button, .process-button {
            background-color: #dc3545;
            color: #fff;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 10px;
        }

        .process-button {
            background-color: #28a745;
        }

        .remove-button:hover {
            background-color: #c82333;
        }

        .process-button:hover {
            background-color: #218838;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

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
        <h2>Wishlist</h2>

        <?php
        // Display success or error messages
        if (isset($_SESSION['success'])) {
            echo '<div class="message success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="message error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }

        if ($wishlist_result->num_rows > 0) {
            while ($row = $wishlist_result->fetch_assoc()) {
                echo '<div class="wishlist-item">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['brand'] . ' ' . $row['model'] . '" class="car-image">';
                echo '<div class="details">';
                echo '<p><strong>' /*. $row['brand'] */. ' ' . $row['model'] . '</strong></p>';
                echo '<p>Price: TZS ' . number_format($row['price']) . '</p>';
                echo '</div>';
                echo '<form action="wishlist.php" method="POST" style="margin: 0;">';
                echo '<input type="hidden" name="remove_id" value="' . $row['wishlist_id'] . '">';
                echo '<button type="submit" class="remove-button">Remove</button>';
                echo '</form>';
                echo '<button class="process-button buy-now" data-car-id="' . htmlspecialchars($row['wishlist_id']) . '" data-car-price="' . htmlspecialchars($row['price']) . '" style="display:none;">Buy Now</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No items in wishlist.</p>';
        }
        ?>
    </div>

    <!-- Floating Feedback Button -->
    <button class="feedback-button" onclick="toggleFeedbackForm()">&#9993;</button>

    <!-- Feedback Form Container -->
    <div class="feedback-form-container" id="feedbackForm">
        <h3>Feedback</h3>
        <form action="wishlist.php" method="POST">
            <textarea name="feedback" placeholder="Write your feedback here..."></textarea>
            <button type="submit" class="form-button">Send</button>
        </form>
    </div>

    <script src="https://pay.google.com/gp/p/js/pay.js" async></script>
    <script src="google_pay.js"></script>
    <script>
        function proceedToPayment(carId) {
            if (confirm('Are you sure you want to proceed to payment?')) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            if (response === 'success') {
                                window.location.href = 'orders.php';
                            } else {
                                alert('Failed to proceed to payment. Please try again.');
                            }
                        } else {
                            alert('Failed to connect to server.');
                        }
                    }
                };
                xhr.open('POST', 'wishlist.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('process_id=' + encodeURIComponent(carId));
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var buyNowButtons = document.querySelectorAll('.buy-now');
            buyNowButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var carId = this.getAttribute('data-car-id');
                    proceedToPayment(carId);
                });
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

<?php
$conn->close();
?>
