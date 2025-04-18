<?php
// co_ords.php
session_start();

require_once 'db_connect.php';

// Fetch Co-ord items from the database
$stmt = $pdo->prepare("SELECT * FROM dresses WHERE category = 'Co-ords'");
$stmt->execute();
$co_ords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch unique categories for the sidebar
$stmt = $pdo->prepare("SELECT DISTINCT category FROM dresses");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Co-ords - Fancy Dress Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #CBA4C3; /* Soft Pink */
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
            color: #FCBEB5; /* Light Coral */
        }
        .auth-buttons a {
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .login {
            background-color: #CBA4C3; /* Soft Pink */
            color: white;
        }
        .signup {
            background-color: #BDECE2; /* Mint Green */
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
        .container {
            display: flex;
            margin: 20px;
        }
        .sidebar {
            width: 20%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: left;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #45BABB; /* Teal */
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: black;
            font-weight: 500;
        }
        .sidebar ul li a:hover {
            color: #FCBEB5; /* Light Coral */
        }
        .content {
            flex: 1;
            padding: 20px;
            background: white;
            border-radius: 10px;
            margin-left: 20px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .item {
            text-decoration: none;
            color: black;
            background: #FCBEB5;
            padding: 15px;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }
        .item:hover {
            transform: scale(1.05);
            box-shadow: 4px 4px 15px rgba(0,0,0,0.2);
        }
        .item img {
            width: 100%;
            height: 300px;
            border-radius: 10px;
            object-fit: cover;
        }
        .item p {
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
            font-size: 18px;
        }
        .price-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .rent-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #45BABB; /* Teal */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .rent-button:hover {
            background-color: #3A9A9B; /* Darker Teal */
        }

        /* Popup Image Styles */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }
        .popup img {
            max-width: 80%;
            max-height: 80%;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.5);
        }
        .popup span {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 40px;
            color: white;
            cursor: pointer;
        }

        /* Cart Popup Styles */
        .cart-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .cart-content {
            background: white;
            width: 80%;
            max-width: 600px;
            padding: 20px;
            border-radius: 10px;
            position: relative;
        }
        .cart-content h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #45BABB;
        }
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .cart-item img {
            width: 100px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-details h3 {
            font-size: 18px;
            margin: 0;
        }
        .cart-item-details p {
            margin: 5px 0;
            color: #666;
        }
        .cart-item-details button {
            background-color: #45BABB;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
        }
        .cart-item-details button:hover {
            background-color: #3A9A9B;
        }
        .cart-content span {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
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
                <a href="logout.php" class="login">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login">Log in</a>
                <a href="signup.php" class="signup">Sign up</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Display error messages if any -->
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_SESSION['error_message']); ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Explore Categories</h2>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <?php if ($category === 'Co-ords'): ?>
                        <li><a href="co_ords.php"><?php echo htmlspecialchars($category); ?></a></li>
                    <?php elseif ($category === 'Cocktail & Party'): ?>
                        <li><a href="cocktail_party.php"><?php echo htmlspecialchars($category); ?></a></li>
                    <?php elseif ($category === 'Mermaid Dress'): ?>
                        <li><a href="mermaid_dress.php"><?php echo htmlspecialchars($category); ?></a></li>
                    <?php elseif ($category === 'Dresses'): ?>
                        <li><a href="gown.php"><?php echo htmlspecialchars($category); ?></a></li>
                        <?php elseif ($category === 'Mini Dress'): ?>
                            <li><a href="mini.php"><?php echo htmlspecialchars($category); ?></a></li>
                    <?php else: ?>
                        <li><a href="shop.php?category=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a></li>
                        
                    <?php endif; ?>
                <?php endforeach; ?>
                <li><a href="shop.php?category=Jewelry">Jewelry</a></li>
            </ul>
        </aside>
        <!-- Content Grid -->
        <main class="content">
            <h2 class="category-title">CO-ORDS</h2>
            <div class="grid">
                <?php foreach ($co_ords as $co_ord): ?>
                    <div class="item" onclick="openPopup('<?php echo htmlspecialchars($co_ord['image_url']); ?>')">
                        <img src="<?php echo htmlspecialchars($co_ord['image_url']); ?>" alt="<?php echo htmlspecialchars($co_ord['name']); ?>">
                        <span class="price-tag">₹<?php echo number_format($co_ord['price'], 2); ?>/day</span>
                        <p><?php echo htmlspecialchars($co_ord['name']); ?></p>
                        <button class="rent-button" onclick="addToCart('<?php echo htmlspecialchars($co_ord['name']); ?>', '₹<?php echo number_format($co_ord['price'], 2); ?>/day', '<?php echo htmlspecialchars($co_ord['image_url']); ?>', event)">Rent Now</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Popup Image -->
    <div class="popup" id="popup">
        <span onclick="closePopup()">×</span>
        <img id="popup-img" src="" alt="Popup Image">
    </div>

    <!-- Cart Popup -->
    <div class="cart-popup" id="cart-popup">
        <div class="cart-content">
            <span onclick="closeCart()">×</span>
            <h2>Your Cart</h2>
            <div id="cart-items"></div>
        </div>
    </div>

    <script>
        // Cart array to store items, initialized from localStorage or empty
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Add item to cart
        function addToCart(name, price, image, event) {
            event.stopPropagation(); // Prevent the image popup from opening when clicking "Rent Now"
            
            // Add item to cart
            cart.push({ name, price, image });
            
            // Save cart to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();
            
            // Open the cart popup
            openCart();
        }

        // Update cart count in the header
        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = cart.length;
        }

        // Open cart popup and populate items
        function openCart() {
            const cartPopup = document.getElementById('cart-popup');
            const cartItemsContainer = document.getElementById('cart-items');
            
            // Clear previous items
            cartItemsContainer.innerHTML = '';

            // Add each cart item to the popup
            cart.forEach((item, index) => {
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}">
                    <div class="cart-item-details">
                        <h3>${item.name}</h3>
                        <p>${item.price}</p>
                        <button onclick="removeFromCart(${index})">Remove</button>
                    </div>
                `;
                cartItemsContainer.appendChild(cartItem);
            });

            // Show the cart popup
            cartPopup.style.display = 'flex';
        }

        // Remove item from cart
        function removeFromCart(index) {
            cart.splice(index, 1); // Remove item at the specified index
            localStorage.setItem('cart', JSON.stringify(cart)); // Update localStorage
            updateCartCount(); // Update cart count
            openCart(); // Refresh cart popup
        }

        // Close cart popup
        function closeCart() {
            const cartPopup = document.getElementById('cart-popup');
            cartPopup.style.display = 'none';
        }

        // Open image popup
        function openPopup(src) {
            const popup = document.getElementById('popup');
            const popupImg = document.getElementById('popup-img');
            popupImg.src = src;
            popup.style.display = 'flex';
        }

        // Close image popup
        function closePopup() {
            const popup = document.getElementById('popup');
            popup.style.display = 'none';
        }

        // Initialize cart count on page load
        window.onload = function() {
            updateCartCount();
        };
    </script>
</body>
</html>