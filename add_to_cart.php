<?php
include_once 'config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_POST) {
    $book_id = $_POST['book_id'];
    $quantity = $_POST['quantity'] ?? 1;
    $user_id = $_SESSION['user_id'];
    
    // Check if item exists in cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$user_id, $book_id]);
    $cart_item = $stmt->fetch();
    
    if ($cart_item) {
        // Update quantity
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$quantity, $user_id, $book_id]);
    } else {
        // Add new item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $book_id, $quantity]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Added to cart']);
}
?>
