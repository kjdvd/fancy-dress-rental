<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fancy Dress Rental - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="page/styles.css"> <!-- Link to your styles.css -->
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #CBA4C3;
            text-transform: uppercase;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: 600;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color: #FCBEB5;
        }
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        .auth-buttons a {
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .login {
            background-color: #CBA4C3;
            color: white;
        }
        .login:hover {
            background-color: #BDECE2;
        }
        .signup {
            background-color: #BDECE2;
            color: black;
        }
        .signup:hover {
            background-color: #CBA4C3;
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

        /* Hero Section from index.html */
        .hero {
            background: url('https://i.pinimg.com/736x/5e/5f/5a/5e5f5a8c5f5e5f5a8c5f5e5f5a8c5f.jpg') no-repeat center center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }
        .hero .container {
            max-width: 600px;
        }
        .hero h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
        }
        .cta-button {
            background-color: #45BABB;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .cta-button:hover {
            background-color: #CBA4C3;
        }

        /* Category Card Styles from index.html */
        .categories {
            padding: 2rem 0;
            background-color: #f9f9f9;
        }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .category-card {
            text-align: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px;
            transition: transform 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .category-card h3 {
            margin: 0;
            font-size: 18px;
            color: #45BABB;
        }

        /* Footer Styles from index.php */
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
            <a href="about.php">about</a>
            <a href="checkout.php">checkout</a>
            
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

    <!-- Hero Section from index.html -->
    <section class="hero">
        <div class="container">
            <h2>Welcome to Fancy Dress Rental</h2>
            <p>Find the perfect dress for any occasion. Rent stunning western dresses at affordable prices!</p>
            <a href="shop.php" class="cta-button">Explore Collection</a>
        </div>
    </section>

    <!-- Categories Section from index.html -->
    <section class="categories">
        <div class="container">
            <h2>Popular Categories</h2>
            <div class="category-grid">
                <div class="category-card">
                    <img src="https://i.pinimg.com/736x/cf/21/bc/cf21bc0749a3e077b019ab68c3a31f4d.jpg" alt="Evening Gowns">
                    <h3>Evening Gowns</h3>
                </div>
                <div class="category-card">
                    <img src="https://i.pinimg.com/736x/16/57/9b/16579b9f7cd56043b261701594a67a0f.jpg" alt="Casual Dresses">
                    <h3>Casual Dresses</h3>
                </div>
                <div class="category-card">
                    <img src="https://i.pinimg.com/474x/9e/d4/bb/9ed4bb621840f9e3e8481576cf77db93.jpg" alt="Party Wear">
                    <h3>Party Wear</h3>
                </div>
                <div class="category-card">
                    <img src="https://i.pinimg.com/474x/c2/db/9e/c2db9e18a0ecb5f862ac0603c8f6adc8.jpg" alt="Bridal Dresses">
                    <h3>Bridal Dresses</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer from index.php -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h4>About Us</h4>
                <ul>
                    <li><a href="page/about.html">Our Story</a></li>
                    <li><a href="page/contact_us.html">Contact Us</a></li>
                    <li><a href="page/FAQs.html">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="page/return.html">Returns</a></li>
                    <li><a href="page/Finding Your Fit.html">Size Guide</a></li>
                    <li><a href="page/How_It_Works.html">How It Works</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Follow Us</h4>
                <div class="social-section">
                    <p>Connect with us</p>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <p>© 2025 Fancy Dress Rental. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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