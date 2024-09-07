<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's name for display
$user_sql = "SELECT fullname FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_sql);
if ($user_result->num_rows > 0) {
    $user_row = $user_result->fetch_assoc();
    $user_name = $user_row['fullname'];
} else {
    $user_name = "Unknown User";
}

// Fetch all feedbacks
$feedback_sql = "SELECT f.id, f.message, u.fullname AS user_name, f.created_at
                 FROM feedback f
                 INNER JOIN users u ON f.user_id = u.id
                 ORDER BY f.created_at DESC";
$feedback_result = $conn->query($feedback_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
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

        .feedback-item {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .feedback-user {
            font-weight: bold;
            color: #555;
        }

        .feedback-date {
            color: #888;
            font-size: 0.9em;
        }

        .feedback-message {
            margin-top: 5px;
            color: #666;
        }
    </style>
</head>
<body>
    <?php include 'header_admin.php'; ?>

    <div class="container">
        <h2>All Feedbacks</h2>

        <?php
        if ($feedback_result->num_rows > 0) {
            while ($row = $feedback_result->fetch_assoc()) {
                echo '<div class="feedback-item">';
                echo '<div class="feedback-header">';
                echo '<span class="feedback-user">' . htmlspecialchars($row['user_name']) . '</span>';
                echo '<span class="feedback-date">' . date('F j, Y, g:i a', strtotime($row['created_at'])) . '</span>';
                echo '</div>';
                echo '<div class="feedback-message">' . htmlspecialchars($row['message']) . '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No feedbacks found.</p>';
        }
        ?>
    </div>

</body>
</html>

<?php
$conn->close();
?>
