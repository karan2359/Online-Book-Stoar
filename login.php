<?php
include 'config.php';
header('Content-Type: application/json');

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
}
?>
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
                <Input type="email" name="email" id="email" required></Input>
                
                <label for="Password" Name="Password">Password</label>
                <Input type="text" required ></Input>
            </div>

            <button type="submit">LogIn</button>
            <p>If You Not Have A Account Then <a href="signin.php">Signin</a></p>
        </div>
       


    </form>
    <footer>
        &copy;Footer Page
    </footer>
</body>

</html>