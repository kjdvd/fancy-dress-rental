<?php
session_start();
require 'db_connect.php';

header('Content-Type: application/json');

// Verify CSRF token
if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    $conn->beginTransaction();
    
    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, billing_address, status, payment_id, razorpay_order_id, razorpay_signature) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $data['total'],
        $data['billing_address'],
        $data['status'],
        $data['payment_id'],
        $data['razorpay_order_id'],
        $data['razorpay_signature']
    ]);
    $orderId = $conn->lastInsertId();
    
    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, days, image) 
                           VALUES (?, ?, ?, ?, ?)");
    foreach ($data['items'] as $item) {
        $stmt->execute([
            $orderId,
            $item['name'],
            $item['price'],
            $item['days'],
            $item['image']
        ]);
    }
    
    // Clear cart
    unset($_SESSION['cart']);
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'message' => 'Order processed successfully'
    ]);
} catch (PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: '.$e->getMessage()]);
}
?>