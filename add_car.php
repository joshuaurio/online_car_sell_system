<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car - Jaki General Supply</title>
    <link rel="stylesheet" href="admin_css.css"> <!-- Include your CSS file for styling -->
    <link rel="stylesheet" href="header_styles.css"> <!-- Include your header CSS file for consistent styling -->
    <style>
        /* Additional styles specific to add_car.php */
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        .message {
            text-align: center;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .message.success {
            color: green;
            background-color: #e9ffe9;
        }

        .message.error {
            color: red;
            background-color: #ffe9e9;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        form input, form select {
            padding: 10px;
            margin: 5px 0;
            flex: 1;
            min-width: 150px;
        }

        form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'header_admin.php'; ?>

    <div class="container">
        <h2>Add New Car</h2>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="message success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="message error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <!-- Add Car Form -->
        <form action="process_add_car.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="brand" placeholder="Brand" required>
            <input type="text" name="model" placeholder="Model" required>
            <input type="number" name="seats" placeholder="Seats No" required>
            <input type="number" name="doors" placeholder="Doors" required>
            <select name="fuel" required>
                <option value="">Select Fuel Type</option>
                <option value="Petrol">Petrol</option>
                <option value="Diesel">Diesel</option>
                <option value="Gas">Gas</option>
                <option value="Electric">Electric</option>
                <option value="Hybrid">Hybrid</option>
            </select>
            <select name="transmission" required>
                <option value="">Select Transmission</option>
                <option value="Automatic">Automatic</option>
                <option value="Manual">Manual</option>
                <option value="CVT">CVT</option>
            </select>
            <select name="wheel" required>
                <option value="">Select Wheel Drive</option>
                <option value="2WD">2 Wheel Drive (2WD)</option>
                <option value="4WD">4 Wheel Drive (4WD)</option>
            </select>
            <select name="color" required>
                <option value="">Select Color</option>
                <option value="Pearl white">Pearl White</option>
                <option value="Metallic maroon">Metallic Maroon</option>
                <option value="Gray">Gray</option>
                <option value="Matte black">Matte Black</option>
                <option value="Blue">Blue</option>
                <option value="Silver">Silver</option>
                <option value="Black">Black</option>
            </select>
            <input type="number" name="mileage" placeholder="Mileage" required>
            <input type="number" name="year" placeholder="Year" required>
            <input type="number" name="price" placeholder="Price" required>
            <input type="file" name="image" required>
            <button type="submit" name="submit">Add Car</button>
        </form>
    </div>

</body>
</html>
