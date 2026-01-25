<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
   
</head>

<body>
    <header>

        <nav class="navbar">
            <div class="logo"><a href="index.php"> <img src="/assets/Logo/1766324583766.png" alt="logo" height="60px">
                    <p class="title">Book Store</p>
                </a></div>
            <div class="menu">
                <div class="search-bar"><input class="searchbar" type="text" placeholder="Search Bar" name="searchbar">
               </div>
               <div class="center card">
    <a href="get_cart.php">🛒 Cart <span class="cart-count">0</span></a>
</div>

                <!-- <div class="center card"><a href="#">🛒Cart</a></div> -->

                <div class="center acc">
                    <a href="Acc.php">👤 Account</a>
                    <div class="acc-dropdown">
                        <a href="signin.php">Sign In</a>
                        <a href="login.php">Login</a>
                        <a href="orders.php">📦 Orders</a>

                    </div>
                </div>
            </div>
        </nav>
       <div class="container">
        <!-- MAIN CATEGORY FILTER BUTTONS -->
        <div class="category-filter">
            <button onclick="filterBooks('All', '')" class="active">📚 All Books</button>
            
            <!-- Fiction with Subcategories -->
            <div class="category-group">
                <button onclick="filterBooks('Fiction', '')">📖 Fiction</button>
                <button onclick="filterBooks('Fiction', 'Classics')">📚 Classics</button>
                <button onclick="filterBooks('Fiction', 'Romance')">💕 Romance</button>
                <button onclick="filterBooks('Fiction', 'Mythological')">🏛️ Mythological</button>
            </div>
            
            <!-- Non-Fiction -->
            <button onclick="filterBooks('Non-Fiction', '')">📘 Non-Fiction</button>
            <button onclick="filterBooks('Non-Fiction', 'Self Improvement')">💡 Self Improvement</button>
            <button onclick="filterBooks('Non-Fiction', 'Biography')">👤 Biography</button>
            
            <!-- Academics -->
            <button onclick="filterBooks('Academics', '')">🎓 Academics</button>
            <button onclick="filterBooks('Academics', 'Competitive Exam')">📝 Competitive Exam</button>
            <button onclick="filterBooks('Academics', 'School')">🏫 School</button>
            
            <!-- Kids -->
            <button onclick="filterBooks('Kids', '')">👶 Kids</button>
            <button onclick="filterBooks('Kids', 'Activity')">🎮 Activity & Puzzles</button>
            
            <!-- Others -->
            <button onclick="filterBooks('Adults', '')">👨 Adults</button>
            <button onclick="filterBooks('Comics', '')">🦸 Comics</button>
            <button onclick="filterBooks('Regional', '')">🌍 Regional</button>
        </div>
        <hr>
    </header>
    <main>
        <div class="books-grid" id="booksContainer">
            <?php
            // Fetch all books with category info
            $stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
            while ($book = $stmt->fetch()) {
                $subcategory = explode(',', $book['subcategory'] ?? $book['category'])[0];
                echo "
                <div class='book-card' 
                     data-category='{$book['category']}' 
                     data-subcategory='{$subcategory}'
                     data-id='{$book['id']}'>
                    <img src='{$book['image']}' alt='{$book['title']}'>
                    <div class='book-info'>
                        <div class='subcategory-tag'>{$book['category']} / {$subcategory}</div>
                        <h3>{$book['title']}</h3>
                        <p><strong>✍️ {$book['author']}</strong></p>
                        <p>🏢 {$book['publisher']}</p>
                        <p class='price'>₹{$book['price']}</p>
                        <p class='desc'>".substr($book['description'], 0, 80)."...</p>
                        <button onclick='addToCart({$book['id']}, \"{$book['title']}\", {$book['price']}, \"{$book['category']}\")'>
                            🛒 Add to Cart
                        </button>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>

    <script>
        // Filter by Category + Subcategory
        function filterBooks(mainCategory, subcategory) {
            const books = document.querySelectorAll('.book-card');
            let visibleCount = 0;
            
            books.forEach(book => {
                const bookCategory = book.dataset.category;
                const bookSubcategory = book.dataset.subcategory;
                
                if (mainCategory === 'All') {
                    book.style.display = 'block';
                    visibleCount++;
                } 
                else if (mainCategory === bookCategory && 
                        (subcategory === '' || subcategory === bookSubcategory)) {
                    book.style.display = 'block';
                    visibleCount++;
                } 
                else {
                    book.style.display = 'none';
                }
            });
            
            // Update active button
            document.querySelectorAll('.category-filter button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Show result count
            document.getElementById('resultCount').textContent = 
                visibleCount + ' books found';
        }

        // Add to cart with category info
        function addToCart(bookId, title, price, category) {
            // Your existing cart logic
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `book_id=${bookId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`✅ ${title} added to cart!`);
                    updateCartCount();
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
    <script src="script.js"></script>
    </main>
    <footer>
       <p>&copy;Footer Page</p>
    </footer>
    
</body>
</html>