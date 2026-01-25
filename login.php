
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    
</head>

<body>
   <header>
         <nav class="navbar">
            <div class="logo"><a href="index.php"> <img src="/assets/Logo/1766324583766.png" alt="logo" height="60px">
              <p class="title">Book Store</p>  </a></div>
            <div class="menu">
                <div><a href="index.php">🏚️Home</a></div>
                <div class="center card"><a href="#">🛒Cart</a></div>

                <div class="center acc">
                    <a href="signin.php" >👤 SignIn</a>
                </div>
            </div>
        </nav>
    </header>
    <form action="#">


        <div class="container">
            <h2>LogIn Page</h2>
            <div class="data">
                
                <label for="email">Email</label>
                <input type="email" id="loginEmail"  required>
                
                <label for="Password" Name="Password">Password</label>
                <input type="text" id="loginPassword" n required >
            </div>

            <button type="submit">LogIn</button>
            <p>If You Not Have A Account Then <a href="signin.php">Signin</a></p>
        </div>
        
    </form>
    <?php
include 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // LOGIN SUCCESS
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful!',
            'user' => [
                'name' => $user['fullname'],
                'is_admin' => $user['is_admin']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password!'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
<script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const errorMsg = document.getElementById('errorMsg');
            
            fetch('login.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('loggedIn', 'true');
                    localStorage.setItem('userName', data.user.name);
                    localStorage.setItem('isAdmin', data.user.is_admin);
                    window.location.href = 'index.html';
                } else {
                    errorMsg.textContent = data.message;
                    errorMsg.style.display = 'block';
                }
            });
        });
    </script>

    <footer>
        &copy;Footer Page
    </footer>
</body>

</html>