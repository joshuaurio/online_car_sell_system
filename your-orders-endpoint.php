<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Parse the incoming JSON data
$input = file_get_contents('php://input');
$paymentData = json_decode($input, true);

// Validate and process the payment data
if (!$paymentData || !isset($paymentData['paymentData']) || !isset($paymentData['carId'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid payment data']);
    exit();
}

// Here you would handle the payment processing logic
// For example, you might store the order in your database and process any necessary business logic
$orderId = uniqid(); // Generate a unique order ID (you can adjust this based on your system)

// Example: Store the order in your database (you would replace this with your actual database handling)
$orderDetails = [
    'orderId' => $orderId,
    'carId' => $paymentData['carId'],
    'paymentData' => $paymentData['paymentData'],
    'timestamp' => date('Y-m-d H:i:s'), // Current timestamp
];

// Simulate storing in a database (replace with your actual database logic)
// Example database connection and insert query
include 'config.php'; // Ensure this file contains your database connection

$sql = "INSERT INTO orders (order_id, car_id, payment_data, order_date) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sis", $orderDetails['orderId'], $orderDetails['carId'], json_encode($orderDetails['paymentData']));
$stmt->execute();
$stmt->close();
$conn->close();

// Return a response to Google Pay indicating success
$response = [
    'orderId' => $orderId,
    'message' => 'Payment successful',
];

http_response_code(200); // OK
echo json_encode($response);
?>
