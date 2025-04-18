<?php
// cocktail_party.php
session_start();

// Include database connection
require_once 'db_connect.php';

// Handle form submission for adding to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rent_now'])) {
    $dress_id = $_POST['dress_id'];
    $dress_name = $_POST['dress_name'];
    $dress_price = $_POST['dress_price'];
    $dress_image = $_POST['dress_image'];

    // Add the dress to the session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the dress is already in the session cart
    $alreadyInCart = false;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id'] == $dress_id) {
            $alreadyInCart = true;
            break;
        }
    }

    if (!$alreadyInCart) {
        $_SESSION['cart'][] = [
            'id' => $dress_id,
            'name' => $dress_name,
            'price' => $dress_price,
            'image' => $dress_image
        ];

        // Add to database cart if user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            // Check if the dress is already in the database cart
            $query = "SELECT COUNT(*) FROM cart WHERE user_id = :user_id AND dress_id = :dress_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['user_id' => $user_id, 'dress_id' => $dress_id]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                // Insert only if the dress is not already in the cart
                $query = "INSERT INTO cart (user_id, dress_id) VALUES (:user_id, :dress_id)";
                $stmt = $pdo->prepare($query);
                try {
                    $stmt->execute(['user_id' => $user_id, 'dress_id' => $dress_id]);
                } catch (PDOException $e) {
                    // Handle the error gracefully
                    $_SESSION['error_message'] = "This dress is already in your cart.";
                }
            } else {
                $_SESSION['error_message'] = "This dress is already in your cart.";
            }
        }
    } else {
        $_SESSION['error_message'] = "This dress is already in your cart.";
    }

    // Redirect back to cocktail_party.php to avoid form resubmission
    header("Location: cocktail_party.php");
    exit();
}

// Handle removal from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    $index = $_POST['cart_index'];

    // Remove the item from the session cart
    if (isset($_SESSION['cart'][$index])) {
        $dress_id = $_SESSION['cart'][$index]['id'];
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array

        // Remove from database cart if user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $query = "DELETE FROM cart WHERE user_id = :user_id AND dress_id = :dress_id LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['user_id' => $user_id, 'dress_id' => $dress_id]);
        }
    }

    // Redirect back to cocktail_party.php
    header("Location: cocktail_party.php");
    exit();
}

// Load cart from database for logged-in users
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT d.id, d.name, d.price, d.image_url 
              FROM cart c 
              JOIN dresses d ON c.dress_id = d.id 
              WHERE c.user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Update session cart with database cart
    $_SESSION['cart'] = [];
    foreach ($cart_items as $row) {
        $_SESSION['cart'][] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image' => $row['image_url']
        ];
    }
}

// Fetch cocktail & party dresses from the database
$stmt = $pdo->prepare("SELECT * FROM dresses WHERE category = 'Cocktail & Party'");
$stmt->execute();
$dresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Cocktail & Party - Fancy Dress Rental</title>
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
            color: #45BABB;
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
            color: #FCBEB5;
        }
        .content {
            flex: 1;
            padding: 20px;
            background: white;
            border-radius: 10px;
            margin-left: 20px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        .category-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: black;
            margin-bottom: 20px;
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
            color: black;
        }
        .price-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.8);
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
            background-color: #45BABB;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .rent-button:hover {
            background-color: #3A9A9B;
        }
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
        .cart-item-details form {
            margin-top: 10px;
        }
        .cart-item-details button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .cart-item-details button:hover {
            background-color: #e60000;
        }
        .cart-content span {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            color: #333;
            cursor: pointer;
        }
        .checkout-button {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto 0;
            padding: 10px;
            background-color: #45BABB;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .checkout-button:hover {
            background-color: #3A9A9B;
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
            <a href="../page/How_It_Works.php">How It Works</a>
            <a href="../page/about.html">About</a>
            <div class="cart-icon" onclick="openCart()">
                <img src="https://img.icons8.com/ios-filled/50/ffffff/shopping-cart.png" alt="Cart Icon">
                <span class="cart-count" id="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
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
                    <?php
                    $categoryLinks = [
                        'Dresses' => 'dress.php',
                        'Cocktail & Party' => 'cocktail_party.php',
                        'Mermaid Dress' => 'mermaid_dress.php',
                        'Co-ords' => 'co_ords.php',
                        'Gown' => 'gown.php',
                        'Mini Dress' => 'mini.php',
                        'Top Brands' => 'top_brands.php',
                    ];
                    $link = isset($categoryLinks[$category]) ? $categoryLinks[$category] : "shop.php?category=" . urlencode($category);
                    ?>
                    <li><a href="<?php echo htmlspecialchars($link); ?>"><?php echo htmlspecialchars($category); ?></a></li>
                <?php endforeach; ?>
                <li><a href="shop.php?category=Jewelry">Jewelry</a></li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="content">
            <h2 class="category-title">COCKTAIL & PARTY</h2>
            <div class="grid">
                <?php if (count($dresses) > 0): ?>
                    <?php foreach ($dresses as $dress): ?>
                        <div class="item" onclick="openPopup('<?php echo htmlspecialchars($dress['image_url']); ?>')">
                            <img src="<?php echo htmlspecialchars($dress['image_url']); ?>" alt="<?php echo htmlspecialchars($dress['name']); ?>">
                            <span class="price-tag">₹<?php echo number_format($dress['price'], 2); ?>/day</span>
                            <p><?php echo htmlspecialchars($dress['name']); ?></p>
                            <form action="cocktail_party.php" method="POST">
                                <input type="hidden" name="dress_id" value="<?php echo $dress['id']; ?>">
                                <input type="hidden" name="dress_name" value="<?php echo htmlspecialchars($dress['name']); ?>">
                                <input type="hidden" name="dress_price" value="<?php echo number_format($dress['price'], 2); ?>">
                                <input type="hidden" name="dress_image" value="<?php echo htmlspecialchars($dress['image_url']); ?>">
                                <button type="submit" name="rent_now" class="rent-button" onclick="event.stopPropagation()">Rent Now</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No Cocktail & Party dresses found.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Image Popup -->
    <div class="popup" id="image-popup">
        <span onclick="closePopup()">&times;</span>
        <img id="popup-image" src="" alt="Popup Image">
    </div>

    <!-- Cart Popup -->
    <div class="cart-popup" id="cart-popup">
        <div class="cart-content">
            <span onclick="closeCart()">&times;</span>
            <h2>Your Cart</h2>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="cart-item-details">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>₹<?php echo htmlspecialchars($item['price']); ?>/day</p>
                            <form action="cocktail_party.php" method="POST">
                                <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                <button type="submit" name="remove_from_cart">Remove from Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function openPopup(imageSrc) {
            const popup = document.getElementById('image-popup');
            const popupImage = document.getElementById('popup-image');
            popupImage.src = imageSrc;
            popup.style.display = 'flex';
        }

        function closePopup() {
            const popup = document.getElementById('image-popup');
            popup.style.display = 'none';
        }

        function openCart() {
            const cartPopup = document.getElementById('cart-popup');
            cartPopup.style.display = 'flex';
        }

        function closeCart() {
            const cartPopup = document.getElementById('cart-popup');
            cartPopup.style.display = 'none';
        }

        // Open cart popup after adding an item
        <?php if (isset($_POST['rent_now'])): ?>
            openCart();
        <?php endif; ?>
    </script>
</body>
</html>