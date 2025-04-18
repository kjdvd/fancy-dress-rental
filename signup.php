<?php
// signup.php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Fancy Dress Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FCF6B6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #45BABB;
            color: white;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #CBA4C3;
        }
        .nav-links {
            display: flex;
            align-items: center;
        }
        .nav-links a {
            margin: 0 15px;
            text-decoration: none;
            color: white;
            font-weight: 500;
        }
        .nav-links a:hover {
            color: #FCBEB5;
        }
        .auth-buttons a {
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .login {
            background-color: #CBA4C3;
            color: white;
        }
        .signup {
            background-color: #BDECE2;
            color: black;
        }
        .cart-icon {
            position: relative;
            cursor: pointer;
        }
        .cart-icon img {
            width: 30px;
            height: 30px;
        }
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #FCBEB5;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .signup-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .signup-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .signup-header h1 {
            font-size: 24px;
            color: #45BABB;
        }
        .signup-form label {
            font-weight: bold;
            color: #45BABB;
        }
        .signup-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .signup-form button {
            background-color: #45BABB;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .signup-form button:hover {
            background-color: #3aa3a3;
        }
        .signup-options {
            text-align: center;
            margin-top: 20px;
        }
        .signup-options a {
            color: #45BABB;
            text-decoration: none;
            font-size: 14px;
        }
        .signup-options a:hover {
            text-decoration: underline;
        }
        .footer {
            background-color: #f8f8f8;
            padding: 40px 20px;
            border-top: 1px solid #ddd;
            margin-top: 40px;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .footer-column {
            flex: 1;
            min-width: 200px;
            margin-bottom: 20px;
        }
        .footer-column h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        .footer-column ul {
            list-style: none;
            padding: 0;
        }
        .footer-column ul li {
            margin-bottom: 10px;
        }
        .footer-column ul li a {
            text-decoration: none;
            color: #666;
            font-size: 14px;
        }
        .footer-column ul li a:hover {
            color: #45BABB;
        }
        .social-section {
            margin-top: 20px;
        }
        .social-section p {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .social-section a {
            margin-right: 10px;
            text-decoration: none;
            color: #333;
            font-size: 20px;
        }
        .social-section a:hover {
            color: #45BABB;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="header">
        <div class="logo">FancyDress</div>
        <div class="nav-links">
        <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="about.php">About</a>
            <a href="checkout.php">Checkout</a>
            <div class="cart-icon" onclick="window.location.href='checkout.php'">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/shopping-cart.png" alt="Cart Icon">
                <span class="cart-count" id="cart-count">0</span>
            </div>
        </div>
        <div class="auth-buttons">
            <a href="login.php" class="login">Log in</a>
            <a href="signup.php" class="signup">Sign up</a>
        </div>
    </div>

    <!-- Sign Up Content -->
    <div class="signup-container">
        <div class="signup-header">
            <h1>Create Your Account</h1>
        </div>
        <form class="signup-form" action="signup_process.php" method="post" id="signupForm">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>

            <button type="submit">Sign Up</button>
        </form>
        <div class="signup-options">
            <a href="login.php">Already have an account? Log in</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Footer content -->
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load cart from localStorage and update cart count
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        window.onload = function() {
            updateCartCount();
            document.getElementById('signupForm').addEventListener('submit', handleSignup);
        };

        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = cart.length;
        }

        async function handleSignup(event) {
            event.preventDefault();

            const form = document.getElementById('signupForm');
            const formData = new FormData(form);

            try {
                const response = await fetch('signup_process.php', {
                    method: 'POST',
                    body: formData,
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Signup successful!');
                    window.location.href = 'index.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Signup error:', error);
                alert('Something went wrong. Please try again.');
            }
        }
    </script>
</body>
</html>