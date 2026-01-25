<?php
include 'config.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $mobile = trim($_POST['mobile']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    
    // VALIDATION
    $errors = [];
    if (empty($fullname)) $errors[] = 'Full name required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required';
    if (empty($password) || strlen($password) < 6) $errors[] = 'Password 6+ characters';
    if (empty($mobile) || !preg_match('/^[0-9]{10}$/', $mobile)) $errors[] = '10-digit mobile';
    if (empty($city) || empty($state)) $errors[] = 'City/State required';
    
    if (empty($errors)) {
        // CHECK EMAIL EXISTS
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 0) {
            // GENERATE USER ID
            $stmt = $pdo->query("SELECT COALESCE(MAX(userid), 0) + 1 as newid FROM users");
            $userid = $stmt->fetch(PDO::FETCH_ASSOC)['newid'];
            
            // HASH PASSWORD
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // SAVE USER
            $stmt = $pdo->prepare("INSERT INTO users (userid, fullname, email, password, mobile, city, state) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$userid, $fullname, $email, $hashed_password, $mobile, $city, $state])) {
                $success_msg = "✅ Account created! <a href='login.php'>Login now</a>";
            } else {
                $error_msg = "❌ Registration failed. Try again.";
            }
        } else {
            $error_msg = "❌ Email already registered!";
        }
    } else {
        $error_msg = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - BookStore</title>
    <style>
        * { margin: 0; padding: 0; font-family: monospace; }
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .signup-container {
            background: rgba(255,255,255,0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%; max-width: 400px;
            text-align: center;
        }
        .logo { font-size: 2.5rem; color: #2ae84f; margin-bottom: 20px; }
        h2 { color: #333; margin-bottom: 20px; }
        .message { 
            padding: 12px; margin: 15px 0; border-radius: 10px; font-weight: bold;
            display: <?php echo $success_msg || $error_msg ? 'block' : 'none'; ?>;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        input { 
            width: 100%; padding: 15px; margin: 10px 0; border: 2px solid #ddd; 
            border-radius: 10px; font-size: 16px; box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus { outline: none; border-color: #2ae84f; }
        button { 
            width: 100%; padding: 15px; background: linear-gradient(45deg, #2ae84f, #1dd75f); 
            color: white; border: none; border-radius: 10px; font-size: 18px; 
            font-weight: bold; cursor: pointer; margin: 10px 0; transition: transform 0.2s;
        }
        button:hover { transform: translateY(-2px); }
        .links { margin-top: 20px; }
        .links a { color: #2ae84f; text-decoration: none; font-weight: bold; }
        .links a:hover { text-decoration: underline; }
        .test-data { 
            margin-top: 20px; padding: 15px; background: #f8f9fa; 
            border-radius: 10px; font-size: 14px; 
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="logo">📚 BookStore</div>
        <h2>📝 Create Account</h2>
        
        <?php if ($success_msg): ?>
            <div class="message success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="message error"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <?php if (!$success_msg): ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name *" 
                   value="<?php echo $_POST['fullname'] ?? ''; ?>" required>
            <input type="tel" name="mobile" placeholder="Mobile Number (10 digits) *" 
                   value="<?php echo $_POST['mobile'] ?? ''; ?>" required>
            <input type="email" name="email" placeholder="Email *" 
                   value="<?php echo $_POST['email'] ?? ''; ?>" required>
            <input type="password" name="password" placeholder="Password (6+ chars) *" required>
            <input type="text" name="city" placeholder="City *" 
                   value="<?php echo $_POST['city'] ?? ''; ?>" required>
            <input type="text" name="state" placeholder="State *" 
                   value="<?php echo $_POST['state'] ?? ''; ?>" required>
            <button type="submit">🚀 Create Account</button>
        </form>
        
        <div class="links">
            Already have account? <a href="login.php">Login Here</a>
        </div>
        
        <div class="test-data">
            <strong>💡 Test Data:</strong><br>
            Email: test123@example.com<br>
            Password: 123456<br>
            Mobile: 9876543210
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
