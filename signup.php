<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $address = $conn->real_escape_string($_POST['address']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);

    // Set role to 'user' by default
    $role = 'user';

    $sql = "INSERT INTO users (fullname, address, contact, email, password, role) VALUES ('$fullname', '$address', '$contact', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $success = "Sign up successful. You can now sign in.";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signin_signup.css">
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="address">Address (Optional)</label>
                <input type="text" id="address" name="address">
            </div>
            <div class="form-group">
                <label for="contact">Contact/Phone Number</label>
                <input type="text" id="contact" name="contact" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="form-button">Sign Up</button>
            <p>Already have an account? <a href="signin.php">Sign In</a></p>
        </form>
    </div>
</body>
</html>
