<?php 
include('../config.php');
if (!isAdmin()) { header('Location: ../index.php'); exit; }

if (isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];  // ✅ NEW
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Create asset folder
    $upload_dir = '../asset/';
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = 'asset/' . $image_name;
        }
    }
    
    // ✅ INSERT with subcategory
    $stmt = $pdo->prepare("INSERT INTO books (title, author, publisher, category, subcategory, price, description, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$title, $author, $publisher, $category, $subcategory, $price, $description, $image]);
    echo "<div class='success'>✅ Book '$title' ($category > $subcategory) added successfully!</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Add Book with Subcategory</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            /* font-family: monospace; */
            /* font-size: large; */

            font-family: "Roboto Slab", serif;
        }

        body {
            background-color: #393E46;
            color:white;

        }
/* Navigation Bar */
        .navbar {
            display: flex;
            /* background-color: rgba(42, 238, 81, 0.659); */
            background-color: #222831;
            justify-content: space-between;
            padding: 20px;
            /* font-size:large; */
            font-weight: bold;
        

        }

        .navlist {
            /* position: sticky; */
            display: flex;
            gap: 30px;
            list-style: none;

        }

        .logo {
            margin-left: 10px;
        }
        .title {
            display: inline-block;
            font-size: 35px;
            position: absolute;
            margin: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        a {
            text-decoration: none;
            color: white;

        }

        ul {
            list-style: none;
        }
        .menu {
            display: flex;
            padding: 14px;
            gap: 24px;
        }

        a {
            text-decoration: none;
            color: white;

        }

        ul {
            list-style: none;
        }
        .menu {
            display: flex;
            padding: 14px;
            gap: 24px;
        }
        .add-book { 
            max-width: 700px; 
            margin: 40px auto; 
            padding: 30px; 
            background: rgba(148, 137, 121, 0.2); 
            border-radius: 20px; border: 3px solid #2d3748; 
        }
        h1, h2, h3 { 
            text-align: center; 
            margin-bottom: 25px; 
        }
        label { 
            display: block; 
            font-weight: bold; 
            margin: 15px 0 5px 0; 
            color: #f7fafc; 
        }
        input, select, textarea { 
            width: 100%; 
            max-width: 400px; 
            padding: 12px; 
            margin-bottom: 15px; 
            border: 2px solid #4a5568; 
            border-radius: 10px; 
            font-size: 16px; 
            box-sizing: border-box; 
        }
        button { 
            background: linear-gradient(135deg, #48bb78, #38a169); 
            color: white; 
            padding: 15px 40px; 
            border: none; 
            border-radius: 10px; 
            font-size: 18px; 
            font-weight: bold; 
            cursor: pointer; 
            width: 100%; 
            max-width: 300px; 
        }
        button:hover { 
            background: linear-gradient(135deg, #38a169, #2f855a);
         }
        .success { 
            background: rgba(46,204,113,0.3); 
            color: #2d8749; padding: 15px; 
            border-radius: 10px; 
            margin: 20px 0; 
            text-align: center; 
            font-weight: bold; 
        }
        .books-grid { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 25px; 
            padding: 30px; 
            justify-content: center; 
            max-width: 1400px; 
            margin: 0 auto; 
        }
        .book-item { 
            background: rgba(255,255,255,0.1); 
            padding: 25px; 
            border-radius: 15px; 
            border: 2px solid #4a5568; 
            width: 300px; 
            text-align: center; 
            backdrop-filter: blur(10px); 
        }
        .book-item img { 
            width: 100px; 
            height: 140px; 
            object-fit: cover; 
            border-radius: 8px; 
            border: 3px solid #4299e1; 
        }
        .edit-btn { 
            background: #4299e1; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 8px; 
            margin: 5px; 
            display: inline-block;
        }
        .delete-btn { 
            background: #f56565; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none;
            border-radius: 8px; 
            margin: 5px; 
            display: inline-block; 
        }
    </style>
</head>
<body>
    <header><nav class="navbar">
            <div class="logo"><a href="../index.php"> <img src="../asset/logo cut.png" alt="logo" height="60px">
                    <p class="title">Book Store</p></a>
            </div>
            <div class="menu">
                <div><a href="../index.php">🏚️Home</a></div>
                <a href="logout.php">🚪 Logout</a>
            </div>
        </nav>

    </header>

    <h1>Admin Control Panel</h1>
    
    <!-- Add Book Form with Subcategory -->
    <div class="add-book">
        <h2> Add New Book</h2>
        <?php if (isset($_POST['add_book'])) echo "<div class='success'>✅ Book added successfully!</div>"; ?>
        
        <form method="POST" action="admin.php" enctype="multipart/form-data">
            <label>📖 Book Title</label>
            <input type="text" name="title" placeholder="Enter book title" required>
            
            <label>✍️ Author</label>
            <input type="text" name="author" placeholder="Author name" required>
            
            <label>🏢 Publisher</label>
            <input type="text" name="publisher" placeholder="Publisher" required>
            
            <label>📂 Main Category</label>
            <select name="category" id="categorySelect" onchange="loadSubcategories()" required>
                <option value="">Select Main Category</option>
                <option value="Fiction"> Fiction</option>
                <option value="Non-Fiction"> Non-Fiction</option>
                <option value="Academics">Academics</option>
                <option value="Kids"> Kids</option>
                <option value="Adults"> Adults</option>
                <option value="Comics"> Comics</option>
                <option value="Regional Books"> Regional Books</option>
            </select>
            
            <label>📋 Subcategory</label>
            <select name="subcategory" id="subcategorySelect" required>
                <option value="">First select category</option>
            </select>
            
            <label>💰 Price (₹)</label>
            <input type="number" name="price" step="0.01" min="0" placeholder="99.99" required>
            
            <label>📝 Description</label>
            <textarea name="description" maxlength="500" required placeholder="Enter book description..."></textarea>
            
            <label>🖼️ Book Cover Image</label>
            <input type="file" name="image" accept="image/*" required>
            
            <button type="submit" name="add_book">🚀 Add Book Now</button>
        </form>
    </div>

    <!-- Books List -->
    <h3>📚 All Books (<?php echo $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn(); ?>)</h3>
    <div class="books-grid">
        <?php
        $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
        while($book = $stmt->fetch()) {
            echo "<div class='book-item'>";
            echo "<h4>{$book['title']}</h4>";
            echo "<p><strong>✍️ {$book['author']}</strong></p>";
            echo "<p>💰 ₹{$book['price']}</p>";
            echo "<p><strong>📂 {$book['category']} / {$book['subcategory']}</strong></p>";
            if ($book['image']) {
                echo "<img src='../{$book['image']}' alt='{$book['title']}' loading='lazy'>";
            }
            echo "<div style='margin-top: 15px;'>";
            echo "<a href='edit_book.php?id={$book['id']}' class='edit-btn'>✏️ Edit</a>";
            echo "<a href='delete_book.php?id={$book['id']}' class='delete-btn' onclick='return confirm(\"Delete {$book['title']}?\")'>🗑️ Delete</a>";
            echo "</div></div>";
        }
        ?>
    </div>

    <script>
    // Dynamic subcategory loader
    function loadSubcategories() {
        const category = document.getElementById('categorySelect').value;
        const subcatSelect = document.getElementById('subcategorySelect');
        
        const subcategories = {
            'Fiction': ['Classics', 'Mythological'],
            'Non-Fiction': ['Self Improvement', 'Biography'],
            'Academics': ['Competitive Exam', 'Entrance exam', 'School', 'General Knowledge'],
            'Kids': ['Activity & Puzzles', 'Colouring & Art book', 'Essay & Letter', 'Work Book'],
            'Adults': ['Crime', 'Mystery Thriller', 'Gen Fiction', 'Fantasy Science Fiction', 'Horror'],
            'Comics': ['Superhero Comics', 'Manga Comics', 'Horror Comics'],
            'Regional Books': ['Marathi', 'Hindi', 'Gujarati']
        };
        
        subcatSelect.innerHTML = '<option value="">Select Subcategory</option>';
        if (subcategories[category]) {
            subcategories[category].forEach(subcat => {
                subcatSelect.innerHTML += `<option value="${subcat}">${subcat}</option>`;
            });
        }
    }
    </script>
</body>
</html>
