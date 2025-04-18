<?php
// about.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Fancy Dress Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="page/styles.css"> <!-- Link to your styles.css -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FCF6B6; /* Pale Yellow */
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #45BABB; /* Teal */
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #CBA4C3; /* Soft Pink */
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
            color: #FCBEB5; /* Light Coral */
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
            background-color: #CBA4C3; /* Soft Pink */
            color: white;
        }
        .login:hover {
            background-color: #BDECE2; /* Mint Green */
        }
        .signup {
            background-color: #BDECE2; /* Mint Green */
            color: black;
        }
        .signup:hover {
            background-color: #CBA4C3; /* Soft Pink */
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
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .about-content {
            padding: 20px;
        }
        .about-content h1 {
            font-size: 32px;
            color: #45BABB; /* Teal */
            margin-bottom: 20px;
            text-align: center;
        }
        .about-content p {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .about-content .mission {
            font-style: italic;
            color: #45BABB;
            font-weight: 500;
        }
        /* Footer Styles (consistent with index.php) */
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
            color: #45BABB; /* Teal */
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <?php else: ?>
                <a href="login.php" class="login">Log in</a>
                <a href="signup.php" class="signup">Sign up</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- About Content -->
    <div class="container">
        <div class="about-content">
            <h1>About Fancy Dress Rental</h1>
            <p>Welcome to Fancy Dress Rental, your one-stop destination for renting stunning dresses and costumes for any occasion! Whether it’s a themed party, a wedding, or a special event, we’ve got you covered with a wide range of unique and elegant options.</p>
            <p>Founded in 2023, our mission is to make fashion accessible and sustainable. We believe everyone deserves to look their best without breaking the bank or cluttering their closet. That’s why we offer high-quality rentals at affordable prices, delivered right to your door.</p>
            <p class="mission">“Dress up, stand out, and return – it’s that simple!”</p>
            <p>Our team is passionate about creativity, quality, and customer satisfaction. We carefully curate every piece in our collection to ensure you find something that fits your style and makes you feel fabulous. Explore our shop, rent your favorite dress, and let us handle the rest!</p>
        </div>
    </div>

    <!-- Footer (consistent with index.php) -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h4>About Us</h4>
                <ul>
                    <li><a href="about.php">Our Story</a></li>
                    <li><a href="contact-us.html">Contact Us</a></li>
                    <li><a href="faqs.html">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="returns.html">Returns</a></li>
                    <li><a href="size-guide.html">Size Guide</a></li>
                    <li><a href="how-it-works.html">How It Works</a></li>
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

    <script>
        // Sync session cart with localStorage
        let cart = <?php echo isset($_SESSION['cart']) ? json_encode($_SESSION['cart']) : '[]'; ?>;
        localStorage.setItem('cart', JSON.stringify(cart));

        window.onload = function() {
            updateCartCount();
        };

        // Update cart count in the header
        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = cart.length;
        }
    </script>
</body>
</html>