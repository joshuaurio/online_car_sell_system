<?php
session_start();
include 'db_connect.php';

// Redirect to signin if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT fullname, address, contact, email FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $error = "User not found.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $address = $conn->real_escape_string($_POST['address']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $email = $conn->real_escape_string($_POST['email']);

    $update_sql = "UPDATE users SET fullname='$fullname', address='$address', contact='$contact', email='$email' WHERE id='$user_id'";

    if ($conn->query($update_sql) === TRUE) {
        $success = "Profile updated successfully.";
        $user = array('fullname' => $fullname, 'address' => $address, 'contact' => $contact, 'email' => $email);
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="customer.css">
    <style>
        /* Embedded CSS specific to profile.php */
        .form-container {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .form-button:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        .secondary {
            background-color: #28a745;
            margin-top: 10px;
        }

        .secondary:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php include 'header_customer.php'; ?>

    <div class="container">
        <div class="form-container">
            <h2>Profile</h2>
            <?php
            if (isset($error)) {
                echo "<p class='error'>$error</p>";
            }
            if (isset($success)) {
                echo "<p class='success'>$success</p>";
            }
            ?>
            <form action="profile.php" method="POST">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address (Optional)</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                </div>
                <div class="form-group">
                    <label for="contact">Contact/Phone Number</label>
                    <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" class="form-button">Update Profile</button>
            </form>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <a href="add_car.php" class="form-button secondary">Add Car</a>
            <?php } ?>
        </div>
    </div>
</body>
</html>
