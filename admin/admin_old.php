<?php 
include('../config.php');  // ← GOES UP ONE FOLDER to ROOT

if (!isAdmin()) { header('Location: index.php'); exit; } ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <title>Admin Panel</title>  
    <style>
        * {
            margin: 0;
            padding: 0;
            /* font-family: monospace;
            font-size: large; */

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

        .books-grid {

            /* margin: auto; */
            padding: 20px;
            display: flex;
            /* align-items: center; */
            justify-content:space-evenly;
            /* overflow-y: scroll; */
            /* height: 5000px; */
            /* width: 100%; */
            /* flex-direction:; */
            gap: 45px;
            background-color: #948979;
            /* border-radius: 30px; */
            /* border: 4px solid black; */

        }
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
/* body css  */
h1{
    color:white;
}
.add-book{
     margin: auto;
     /* display: flex; */
     /* align-items: center; */
     /* justify-content: center; */
     /* height: 500px; */
     /* flex-direction: column; */
     /* background-color: rgba(219, 219, 161, 0.667); */
     width: 600px;
     padding: 20px;
     gap: 15px;
     background-color: #948979;
     /* color: white; */
     color: black;
     border-radius: 20px;
     border: 4px solid black;
}
h2 {
    margin-bottom: 40px;
    font-size: xx-large;
}
button {
    padding: 7px;
    border-radius: 20px;
    margin-top: 22px;
    width: 200px;
    background-color: rgba(82, 82, 155, 0.96);
    color: beige;
    cursor: pointer;
}
input {
    padding: 9px;
    width: 250px;
    /* display:block; */
    margin:10px;
    /* outline: none; */
}
label {
    /* display: block; */
    margin: 20px;
    font-weight: bold;
}
select{
    /* display: block; */
    /* margin:10px; */
    padding:5px;
     width: 250px;
     padding: 9px;
    

}
textarea{
   width: 250px;
   /* display: block; */
   height: 70px;
   /* margin:10px; */
}


    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo"><a href="../index.php"> <img src="../asset/logo cut.png" alt="logo" height="60px">
                    <p class="title">Book Store</p></a>
            </div>
            <div class="menu">
                <div><a href="../index.php">🏚️Home</a></div>
            </div>
        </nav>
    </header>
    <h1>Admin Control Panel</h1>
    
    <!-- Add New Book Form -->
    <div class="add-book">
        <h2>Add New Book</h2>
        <form method="POST" action="admin.php" enctype="multipart/form-data">
            <label for="Book TItle">Book Title</label>
            <input type="text" name="title" placeholder="Book Title" required><br>
            <label for="Book Author">Book Author</label>
            <input type="text" name="author" placeholder="Author" required><br>
            <label for="Publisher">Publisher</label>
            <input type="text" name="publisher" placeholder="Publisher" required><br>
            <label for="Category">Select Category</label>
            <select name="category" required>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
                <option value="Academics">Academics</option>
                <option value="Academics">Kids</option>
                <option value="Academics">Adults</option>
                <option value="Academics">Regional Books</option>
                <!-- Add more -->
            </select><br>
            <label for="Price">Book Price</label>
            <input type="number" name="price" step="0.01" placeholder="Price" required><br>
            <label for="Description">Description</label>
            <textarea name="description" placeholder="Description" required></textarea><br>
            <label for="Image">Select Image From Device</label>
            <input type="file" name="image"><br>
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
        
        $image = "../asset/";
        if (isset($_FILES['asset']) && $_FILES['asset']['error'] == 0) {
            $image = "asset/" . time() . "_" . $_FILES['asset']['name'];
            move_uploaded_file($_FILES['asset']['tmp_name'], $image);
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
        echo "<div style='border:1px solid #ddd; padding:15px; margin:10px 0;'>";
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
