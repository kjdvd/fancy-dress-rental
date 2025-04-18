<?php
// forgot-password.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Fancy Dress Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #FCF6B6; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background: #45BABB; color: white; }
        .logo { font-size: 28px; font-weight: bold; color: #CBA4C3; }
        .nav-links { display: flex; align-items: center; }
        .nav-links a { margin: 0 15px; text-decoration: none; color: white; font-weight: 500; }
        .nav-links a:hover { color: #FCBEB5; }
        .auth-buttons a { padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
        .login { background-color: #CBA4C3; color: white; }
        .signup { background-color: #BDECE2; color: black; }
        .cart-icon { position: relative; cursor: pointer; }
        .cart-icon img { width: 30px; height: 30px; }
        .cart-count { position: absolute; top: -10px; right: -10px; background-color: #FCBEB5; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FancyDress</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="about.php">about</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
            <div class="cart-icon" onclick="window.location.href='checkout.php'">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/shopping-cart.png" alt="Cart Icon">
                <span class="cart-count" id="cart-count">0</span>
            </div>
        </div>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <?php else: ?>
                <a href="login.php" class="login">Log in</a>
                <a href="signup.php" class="signup">Sign up</a>
            <?php endif; ?>
        </div>
    </div>
    <div style="text-align: center; margin-top: 40px;">
        <h1>Forgot Password</h1>
        <p>This is a placeholder for the forgot password page.</p>
    </div>
    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        window.onload = function() {
            updateCartCount();
        };
        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = cart.length;
        }
    </script>
</body>
</html>