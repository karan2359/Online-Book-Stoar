<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page</title>
    <link rel="stylesheet" href="signin.css">

</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo"><a href="index.php"> <img src="/assets/Logo/1766324583766.png" alt="logo" height="60px">
                    <p class="title">Book Store</p>
                </a></div>
            <div class="menu">
                <div><a href="index.php">🏚️Home</a></div>
                <div class="center cart"><a href="#">🛒Cart</a></div>

                <div class="center acc">
                    <a href="login.php">👤 LogIn</a>
                </div>
            </div>
        </nav>
    </header>
    <form method="POST" id="signupForm">


        <div class="container">
            <h2>SignIn Page</h2>
            <div class="data">
                <label for="fname">Full Name </label>
                <input id="signupName" type="text" name="fname" id="fname" placeholder="Enter Full Name" required>

                <label for="mobile">Mobile</label>
                <input id="signupMobile" type="tel" name="mobile" id="mobile" required>

                <label for="email">Email</label>
                <input id="signupEmail" type="email" name="email" id="email">

                <label for="Password">Password</label>
                <input id="signupPassword" type="password" Name="password" id="password" required>
                
                <label for="City">City</label>
                <input type="text" id="signupCity" placeholder="City" required>
                
                <label for="State">State</label>
                <input type="text" id="signupState" placeholder="State" required>

            </div>
            <button type="submit">Create Account </button>
            <div class="links">
                Already have account? <a href="login.html">Login</a>
            </div>
        </div>
    </form>
    <?php
include 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists!']);
        exit;
    }
    
    // Generate user ID
    $stmt = $pdo->query("SELECT COALESCE(MAX(userid), 0) + 1 as newid FROM users");
    $userid = $stmt->fetch()['newid'];
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (userid, fullname, email, password, mobile, city, state) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$userid, $fullname, $email, $hashed_password, $mobile, $city, $state])) {
        echo json_encode(['success' => true, 'message' => 'Account created! Please login.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed!']);
    }
}
?>
    <script>
        document.getElementById('signupForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const data = {
                fullname: document.getElementById('signupName').value,
                mobile: document.getElementById('signupMobile').value,
                email: document.getElementById('signupEmail').value,
                password: document.getElementById('signupPassword').value,
                city: document.getElementById('signupCity').value,
                state: document.getElementById('signupState').value
            };

            const errorMsg = document.getElementById('errorMsg');

            fetch('signup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(data)
            })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        window.location.href = 'login.html';
                    } else {
                        errorMsg.textContent = result.message;
                        errorMsg.style.display = 'block';
                    }
                });
        });
    </script>

    <footer>
        &copy;Copyright Part
    </footer>

</body>

</html>