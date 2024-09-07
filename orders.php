<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $status_update_sql = "UPDATE orders SET status = 'picked' WHERE id = '$order_id' AND user_id = '$user_id'";
    if ($conn->query($status_update_sql) === TRUE) {
        $_SESSION['success'] = "Order status updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating order status: " . $conn->error;
    }
}

// Fetch orders
$orders_sql = "SELECT o.id AS order_id, c.id AS car_id, c.brand, c.model, c.price, c.image, o.order_date, o.status
               FROM orders o
               INNER JOIN cars c ON o.car_id = c.id
               WHERE o.user_id = '$user_id'
               ORDER BY o.order_date DESC";
$orders_result = $conn->query($orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
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
            position: relative; /* Added for positioning feedback button */
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .order-item {
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

        .order-date {
            color: #888;
            font-size: 0.9em;
        }

        .status {
            color: green;
            font-weight: bold;
        }

        .status.not-picked {
            color: red;
        }

        /* Feedback button styles */
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

        .feedback-button i {
            font-size: 32px; /* Adjust icon size */
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
        <h2>My Orders</h2>

        <?php
        // Display success or error messages
        if (isset($_SESSION['success'])) {
            echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }

        if ($orders_result->num_rows > 0) {
            while ($row = $orders_result->fetch_assoc()) {
                echo '<div class="order-item">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['brand'] . ' ' . $row['model'] . '" class="car-image">';
                echo '<div class="details">';
                echo '<p><strong>' /*. $row['brand']*/ . ' ' . $row['model'] . '</strong></p>';
                echo '<p>Price: TZS ' . number_format($row['price']) . '</p>';
                echo '<p class="order-date">Ordered on: ' . date('F j, Y, g:i a', strtotime($row['order_date'])) . '</p>';
                echo '</div>';
                echo '<div class="status ' . ($row['status'] === 'not picked' ? 'not-picked' : '') . '">';
                if ($row['status'] === 'not picked') {
                    echo 'Go to Pickup Station';
                } else {
                    echo 'Picked';
                }
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No orders found.</p>';
        }
        ?>

        <!-- Floating Feedback Button -->
    <button class="feedback-button" onclick="toggleFeedbackForm()">&#9993;</button> <!-- Font Awesome icon for feedback -->
        </div>

        <!-- Feedback Form Container -->
        <div class="feedback-form-container" id="feedbackForm">
            <h3>Feedback</h3>
            <form action="orders.php" method="POST">
                <textarea name="feedback" placeholder="Write your feedback here..."></textarea>
                <button type="submit" class="form-button">Send</button>
            </form>
        </div>
    </div>

    <script>
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
