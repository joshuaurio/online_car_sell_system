<?php
session_start();
include 'db_connect.php'; // Include database connection file

// Function to handle status change
if (isset($_GET['change_status']) && $_GET['change_status'] == 'picked' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    // Update status to 'picked'
    $sql_update = "UPDATE orders SET status = 'picked' WHERE id = $order_id";
    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['success'] = "Order status updated successfully.";
        header("Location: notification.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating order status: " . $conn->error;
        header("Location: notification.php");
        exit;
    }
}

// Fetch users who have not picked up their cars
$sql_not_picked = "SELECT u.fullname AS user_name, u.contact, u.email, o.*, c.brand, c.model, c.image 
                   FROM users u
                   INNER JOIN orders o ON u.id = o.user_id
                   INNER JOIN cars c ON o.car_id = c.id
                   WHERE o.status = 'not picked'";
$result_not_picked = $conn->query($sql_not_picked);

// Fetch users who have picked up their cars
$sql_picked = "SELECT u.fullname AS user_name, u.contact, u.email, o.*, c.brand, c.model, c.image 
               FROM users u
               INNER JOIN orders o ON u.id = o.user_id
               INNER JOIN cars c ON o.car_id = c.id
               WHERE o.status = 'picked'";
$result_picked = $conn->query($sql_picked);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification - Car Pickup Status</title>
    <link rel="stylesheet" href="admin_css.css"> <!-- Replace with your CSS file -->
    <style>
      /* Form styling */
form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
select {
    width: calc(100% - 22px); /* Adjust width to fit within the form */
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

select {
    width: 100%; /* Full width for select elements */
}

input[type="file"] {
    margin-top: 10px;
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

/* Notification page specific styles */
.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
}

h2 {
    text-align: center;
}

.user-list {
    margin-top: 20px;
}

.user-item {
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.user-item p {
    margin: 5px 10px;
}

.picked {
    background-color: #d4edda;
}

.not-picked {
    background-color: #f8d7da;
}

.change-status-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 3px;
}

.change-status-btn:hover {
    background-color: #0056b3;
}

.car-image {
    width: 100px;
    height: auto;
    margin-right: 20px;
    border-radius: 5px;
}

    </style>
</head>
<body>
    <?php include 'header_admin.php'; // Include your header template ?>

    <div class="container">
        <h2>Car Pickup Status</h2>

        <!-- Users who have not picked up their cars -->
        <div class="user-list">
            <h3>Users who have not picked up their cars</h3>
            <?php
            if ($result_not_picked->num_rows > 0) {
                while ($row = $result_not_picked->fetch_assoc()) {
                    echo '<div class="user-item not-picked">';
                    echo '<div class="car-info">';
                    echo '<img src="' . $row['image'] . '" alt="Car Image" class="car-image">';
                    echo '<div>';
                    echo '<p><strong>User Name:</strong> ' . $row['user_name'] . '</p>';
                    echo '<p><strong>Contact:</strong> ' . $row['contact'] . '</p>';
                    echo '<p><strong>Email:</strong> ' . $row['email'] . '</p>';
                    echo '<p><strong>Car:</strong> ' . $row['brand'] . ' ' . $row['model'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<form action="notification.php" method="GET">';
                    echo '<input type="hidden" name="order_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="change_status" value="picked" class="change-status-btn">Mark as Picked</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p>No users have not picked up their cars.</p>';
            }
            ?>
        </div>

        <!-- Users who have picked up their cars -->
        <div class="user-list">
            <h3>Users who have picked up their cars</h3>
            <?php
            if ($result_picked->num_rows > 0) {
                while ($row = $result_picked->fetch_assoc()) {
                    echo '<div class="user-item picked">';
                    echo '<div class="car-info">';
                    echo '<img src="' . $row['image'] . '" alt="Car Image" class="car-image">';
                    echo '<div>';
                    echo '<p><strong>User Name:</strong> ' . $row['user_name'] . '</p>';
                    echo '<p><strong>Contact:</strong> ' . $row['contact'] . '</p>';
                    echo '<p><strong>Email:</strong> ' . $row['email'] . '</p>';
                    echo '<p><strong>Car:</strong> ' . $row['brand'] . ' ' . $row['model'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<p><strong>Status:</strong> Picked</p>';
                    echo '<p><strong>Action:</strong> Car already picked up</p>'; // Replace with appropriate action
                    echo '</div>';
                }
            } else {
                echo '<p>No users have picked up their cars.</p>';
            }
            ?>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
