<?php
include_once 'config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$cart = $input['cart'];
$total_amount = $input['total_amount'];

$user_id = $_SESSION['user_id'];

// Start transaction
$pdo->beginTransaction();

try {
    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->execute([$user_id, $total_amount]);
    $order_id = $pdo->lastInsertId();
    
    // Add order items
    foreach ($cart as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['book_id'], $item['quantity'], $item['price']]);
    }
    
    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    $pdo->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Order failed']);
}
?>
