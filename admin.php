<?php include 'config.php'; 
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
        
        $image = "images/default.jpg";
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
    <div class="books-list">
        <?php
        $stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
        while ($book = $stmt->fetch()) {
            echo "
            <div class='book-admin'>
                <img src='{$book['image']}' width='100'>
                <h3>{$book['title']}</h3>
                <p>₹{$book['price']} | {$book['category']}</p>
                <a href='edit_book.php?id={$book['id']}'>Edit</a>
                <a href='delete_book.php?id={$book['id']}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </div>";
        }
        ?>
    </div>
</body>
</html>
