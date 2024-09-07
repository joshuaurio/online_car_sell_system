<!-- modify_car.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Cars</title>
    <style>
        /* Basic CSS for layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%; /* Adjusted to full width for responsiveness */
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
            padding-top: 0;
            border-top: none;
            text-align: center; /* Centering the heading */
        }

        .car-item {
            display: flex;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            margin-bottom: 10px;
            align-items: center; /* Align items vertically */
        }

        .car-image {
            width: 150px; /* Fixed width for the square image */
            height: 150px; /* Fixed height for the square image */
            object-fit: cover; /* Ensures the image covers the entire space */
            margin-right: 20px;
            border-radius: 5px; /* Optional: Rounded corners */
        }

        .car-details {
            flex: 1;
        }

        .car-details h3 {
            margin-top: 0;
            margin-bottom: 5px;
        }

        .car-details p {
            margin: 5px 0;
        }

        form {
            display: inline;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .car-item {
                flex-direction: column; /* Stack items vertically on smaller screens */
                align-items: flex-start; /* Align items to the start */
            }

            .car-image {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header_admin.php'; // Include your header template ?>

    <div class="container">
        <h2>List of Cars</h2>

        <?php
        include 'db_connect.php'; // Include your database connection file

        // Fetch all cars from the database
        $sql = "SELECT * FROM cars";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="car-item">';
                echo '<img src="' . $row['image'] . '" alt="' . $row['brand'] . ' ' . $row['model'] . '" class="car-image">';
                echo '<div class="car-details">';
                echo '<h3>' /*. $row['brand']*/ . ' ' . $row['model'] . '</h3>';
                echo '<p>Seats: ' . $row['seats_no'] . '</p>';
                echo '<p>Doors: ' . $row['doors'] . '</p>';
                echo '<p>Fuel: ' . $row['fuel'] . '</p>';
                echo '<p>Transmission: ' . $row['transmission'] . '</p>';
                echo '<p>Wheel Drive: ' . $row['wheel'] . '</p>';
                echo '<p>Color: ' . $row['color'] . '</p>';
                echo '<p>Mileage: ' . $row['mileage'] . '</p>';
                echo '<p>Year: ' . $row['year'] . '</p>';
                echo '<p>Price: ' . $row['price'] . '</p>';
                echo '<form action="edit_car.php" method="POST">';
                echo '<input type="hidden" name="car_id" value="' . $row['id'] . '">';
                echo '<button type="submit" name="edit">Edit</button>';
                echo '</form>';
                echo '<form action="process_delete_car.php" method="POST">';
                echo '<input type="hidden" name="car_id" value="' . $row['id'] . '">';
                echo '<button type="submit" name="delete">Delete</button>';
                echo '</form>';
                echo '</div>'; // .car-details
                echo '</div>'; // .car-item
            }
        } else {
            echo '<p>No cars found.</p>';
        }

        $conn->close();
        ?>

    </div>

</body>
</html>
