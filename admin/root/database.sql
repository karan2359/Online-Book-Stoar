-- ===========================================
-- BookStore DATABASE - 5 TABLES ONLY
-- Books + Cart + Orders + Users
-- ONE CLICK SETUP → READY!
-- ===========================================

DROP DATABASE IF EXISTS bookstore;
CREATE DATABASE bookstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookstore;

-- 1. USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userid INT UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    mobile VARCHAR(15),
    city VARCHAR(50),
    state VARCHAR(50),
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. BOOKS TABLE
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    publisher VARCHAR(100),
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(500),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. CART TABLE
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- 4. ORDERS TABLE
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    address TEXT,
    payment_method VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 5. ORDER_ITEMS TABLE
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    book_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- USERS
INSERT INTO users (userid, fullname, email, password, mobile, city, state, is_admin) VALUES 
(1, 'Admin User', 'admin@test.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', '9876543210', 'Mumbai', 'Maharashtra', 1),
(2, 'Test User', 'user@test.com', '$2y$10$K.0uI98fzu9xmgqnkl3Du.DIBGNdSvRVfdTfrrqED3E3A5Tktj0Oa', '1234567890', 'Pune', 'Maharashtra', 0);

-- BOOKS (6 Sample Books)
INSERT INTO books (title, author, publisher, category, price, description, stock) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Scribner', 'Fiction', 299.00, 'Classic novel', 25),
('PHP Programming', 'John Smith', 'Tech Press', 'Academics', 799.00, 'Learn PHP MySQL', 15),
('Atomic Habits', 'James Clear', 'Penguin', 'Non-Fiction', 499.00, 'Build habits', 30),
('Python Guide', 'Eric Matthes', 'No Starch', 'Academics', 899.00, 'Python tutorial', 20),
('Rich Dad Poor Dad', 'Robert Kiyosaki', 'Plata', 'Non-Fiction', 399.00, 'Financial education', 18),
('JavaScript Basics', 'John Resig', 'OReilly', 'Academics', 599.00, 'JS fundamentals', 12);

-- SAMPLE CART (User added books)
INSERT INTO cart (user_id, book_id, quantity) VALUES 
(2, 1, 2),  -- Test user added 2 Great Gatsby
(2, 2, 1);  -- Test user added 1 PHP book

-- SAMPLE ORDER
INSERT INTO orders (user_id, total_amount, status, address, payment_method) VALUES 
(2, 1598.00, 'delivered', '123 Test Street, Pune', 'COD');

INSERT INTO order_items (order_id, book_id, quantity, price) VALUES 
(1, 1, 2, 299.00),  -- 2 Great Gatsby
(1, 2, 1, 799.00);  -- 1 PHP book


-- 👑 ADMIN: admin@test.com / admin123
-- 👤 USER:  user@test.com / 123456

SELECT CONCAT('✅ USERS: ', COUNT(id)) as users FROM users
UNION SELECT CONCAT('✅ BOOKS: ', COUNT(id)) as books FROM books
UNION SELECT CONCAT('✅ CART: ', COUNT(id)) as cart FROM cart
UNION SELECT CONCAT('✅ ORDERS: ', COUNT(id)) as orders FROM orders
UNION SELECT CONCAT('✅ ORDER ITEMS: ', COUNT(id)) as order_items FROM order_items
UNION SELECT '🚀 DATABASE READY! 5 Tables Perfect!' as message;
