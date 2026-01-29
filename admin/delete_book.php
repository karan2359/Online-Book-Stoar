<?php 
include('../config.php');
if (!isAdmin()) { 
    header('Location: ../index.php'); 
    exit; 
}

// Get book ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$book_id = $_GET['id'];

// DELETE BOOK
$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
if ($stmt->execute([$book_id])) {
    echo "<h2 style='color:green;'>✅ Book Deleted Successfully!</h2>";
} else {
    echo "<h2 style='color:red;'>❌ Delete Failed!</h2>";
}

// Redirect back to admin after 2 seconds
echo "<script>setTimeout(function(){ window.location.href='admin.php'; }, 2000);</script>";
?>
