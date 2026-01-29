<?php 
include('../config.php');  // ← GOES UP ONE FOLDER to ROOT

if (!isAdmin()) { header('Location: index.php'); exit; } ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style> /* Your admin CSS */ </style>
</head>
<body>
    <h1>Admin Control Panel</h1>
    
    <!-- Add New Book Form -->
    <div class="add-book">
        <h2>Add New Book</h2>
        <form method="POST" action="admin.php" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <input type="text" name="publisher" placeholder="Publisher" required>
            <select name="category" required>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
                <option value="Academics">Academics</option>
                <option value="Academics">Kids</option>
                <option value="Academics">Adults</option>
                <option value="Academics">Regional Books</option>
                <!-- Add more -->
            </select>
            <input type="number" name="price" step="0.01" placeholder="Price" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="file" name="image">
            <button type="submit" name="add_book">Add Book</button>
        </form>
    </div>

    <?php
    if (isset($_POST['add_book'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        
        $image = "../images/";
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = "images/" . time() . "_" . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }
        
        $stmt = $pdo->prepare("INSERT INTO books (title, author, publisher, category, price, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $publisher, $category, $price, $description, $image]);
        echo "<p>Book added successfully!</p>";
    }
    ?>

    <!-- Books List with Edit/Delete -->
    <h3>📚 Books List (<?php echo $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn(); ?>)</h3>
<?php
$stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
if ($stmt->rowCount() > 0) {
    while($book = $stmt->fetch()) {
        echo "<div style='border:1px solid #ddd; padding:15px; margin:10px 0; background:#f9f9f9;'>";
        echo "<strong>{$book['title']}</strong> by {$book['author']}<br>";
        echo "Price: ₹{$book['price']} | Category: {$book['category']}<br>";
        if ($book['image']) {
            echo "<img src='../{$book['image']}' style='width:80px; height:100px; object-fit:cover;'> ";
        }
        echo "<a href='edit_book.php?id={$book['id']}' style='background:#ff6b6b; color:white; padding:5px 10px; text-decoration:none; border-radius:3px;'>✏️ Edit</a> ";
        echo "<a href='delete_book.php?id={$book['id']}' onclick='return confirm(\"Delete {$book['title']}?\")' style='background:#e74c3c; color:white; padding:5px 10px; text-decoration:none; border-radius:3px;'>🗑️ Delete</a>";
        echo "</div>";
    }
} else {
    echo "<p style='color:#666;'>No books found. <a href='#add-book'>Add first book!</a></p>";
}
?>

    <script src="script.js"></script>
</body>
</body>
</html>
