<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Information</title>
    <link rel="stylesheet" href="customer2.css"> <!-- Link to your customer2.css file -->
</head>
<body>
    <?php include 'header_customer.php'; ?>

    <div class="container">
        <h2>Car Information</h2>

        <?php
        include 'db_connect.php';

        // Check if car ID is provided in the URL
        if (isset($_GET['id'])) {
            $car_id = $conn->real_escape_string($_GET['id']);

            // Fetch all columns from the cars table for the specific car
            $sql = "SELECT * FROM cars WHERE id = $car_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="car-details">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['brand'] . ' ' . $row['model'] . '">';
                echo '<p><strong>' /*. $row['brand'] */. ' ' . $row['model'] . '</strong></p>';
                echo '<p>Price: TZS ' . number_format($row['price']) . '</p>';
                echo '<p>Fuel: ' . $row['fuel'] . '</p>';
                echo '<p>Transmission: ' . $row['transmission'] . '</p>';
                echo '<p>Doors: ' . $row['doors'] . '</p>'; // Assuming 'doors' is a column in your database
                echo '<p>Wheel: ' . $row['wheel'] . '</p>'; // Assuming 'wheel' is a column in your database
                echo '<p>Color: ' . $row['color'] . '</p>'; // Assuming 'color' is a column in your database
                echo '<p>Mileage: ' . $row['mileage'] . '</p>';
                echo '<p>Year: ' . $row['year'] . '</p>';

                // Check if 'description' key exists in $row array before accessing it
                if (isset($row['description'])) {
                    echo '<p>Description: ' . $row['description'] . '</p>';
                } else {
                    echo '<p>Description: Not available</p>';
                }

                echo '</div>';

                // Add button to place order (goes to wishlist.php)
                echo '<a href="wishlist.php?id=' . $row['id'] . '" class="order-button">Place Order</a>';
            } else {
                echo '<p>Car not found.</p>';
            }
        } else {
            echo '<p>No car ID specified.</p>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
