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
    <form action="#">


        <div class="container">
            <h2>SignIn Page</h2>
            <div class="data">
                <label for="fname">Full Name </label>
                <input type="text" name="fname" id="fname" placeholder="Enter Full Name" required>

                <label for="Mobile">Mobile</label>
                <input type="tel" name="Mobile" id="mobile" required pattern="[1-9]" size="10">

                <label for="email">Email</label>
                <Input type="email" name="email" id="email"></Input>

                <label for="Password">Password</label>
                <Input type="password" Name="Password" id="password" required pattern="[A-Z,a-z,1-0,@,#,$,%]"></Input>

                <label for="Conpass">Confirm Password</label>
                <input type="password" Name="conpass" id="conform">



            </div>
            <button type="submit">Signin </button>
            <p>If You Already Have a Account: <a href="login.php">LogIn</a></p>
        </div>
    </form>
    <footer>
        &copy;Copyright Part
    </footer>

</body>

</html>