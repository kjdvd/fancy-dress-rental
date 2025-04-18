<?php
// top_brand.php
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

    // Add the dress to the session cart
    $_SESSION['cart'][] = [
        'id' => $dress_id,
        'name' => $dress_name,
        'price' => $dress_price,
        'image' => $dress_image
    ];

    // Add to database cart if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $query = "INSERT INTO cart (user_id, dress_id) VALUES (:user_id, :dress_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'dress_id' => $dress_id]);
    }

    // Redirect back to top_brand.php to avoid form resubmission
    header("Location: top_brand.php");
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

// Fetch top brand dresses from the database
$stmt = $pdo->prepare("SELECT * FROM dresses WHERE category = 'Top Brand'");
$stmt->execute();
$dresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group dresses by brand (based on name prefix or manual assignment)
$chanel_dresses = [];
$dior_dresses = [];
$prada_dresses = [];
$gucci_dresses = [];

foreach ($dresses as $dress) {
    if (stripos($dress['name'], 'Chanel') !== false || in_array($dress['name'], ['Evening Dress', 'Vintage', 'Casual Dress'])) {
        $chanel_dresses[] = $dress;
    } elseif (stripos($dress['name'], 'Dior') !== false || in_array($dress['name'], ['Midi Gown', 'Black Dress'])) {
        $dior_dresses[] = $dress;
    } elseif (stripos($dress['name'], 'Prada') !== false || in_array($dress['name'], ['Midi Dress', 'Long Dress', 'Dress'])) {
        $prada_dresses[] = $dress;
    } elseif (stripos($dress['name'], 'Gucci') !== false || in_array($dress['name'], ['Gown'])) {
        $gucci_dresses[] = $dress;
    }
}

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
    <title>Top Brands - Fancy Dress Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .category-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: black;
            margin-bottom: 20px;
        }
        .brand-section {
            margin: 40px 0;
        }
        .brand-section h2 {
            font-size: 30px;
            color: #45BABB; /* Teal */
        }
        .brand-logo {
            max-width: 150px;
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
            background: #FCBEB5; /* Light Pink */
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
            background-color: rgba(0, 0, 0, 0.8); /* Darker background for better contrast */
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

        /* Footer Styles */
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
            <a href="../page/How_It_Works.php">How It Works</a>
            <a href="../page/about.html">About</a>
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
                    <?php elseif ($category === 'Top Brand'): ?>
                        <li><a href="top_brand.php"><?php echo htmlspecialchars($category); ?></a></li>
                    <?php else: ?>
                        <li><a href="shop.php?category=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li><a href="shop.php?category=Jewelry">Jewelry</a></li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="content">
            <h2 class="category-title">TOP BRANDS</h2>

            <!-- Chanel Brand Section -->
            <?php if (!empty($chanel_dresses)): ?>
                <div class="brand-section">
                    <img src="https://cdn.shopify.com/s/files/1/0775/7363/files/chanel-iconic-cc-logo_480x480.jpg?v=1627421098" alt="Chanel Logo" class="brand-logo">
                    <div class="grid">
                        <?php foreach ($chanel_dresses as $dress): ?>
                            <div class="item" onclick="openPopup('<?php echo htmlspecialchars($dress['image_url']); ?>')">
                                <img src="<?php echo htmlspecialchars($dress['image_url']); ?>" alt="<?php echo htmlspecialchars($dress['name']); ?>">
                                <span class="price-tag">₹<?php echo number_format($dress['price'], 2); ?>/day</span>
                                <p><?php echo htmlspecialchars($dress['name']); ?></p>
                                <form action="top_brand.php" method="POST">
                                    <input type="hidden" name="dress_id" value="<?php echo $dress['id']; ?>">
                                    <input type="hidden" name="dress_name" value="<?php echo htmlspecialchars($dress['name']); ?>">
                                    <input type="hidden" name="dress_price" value="<?php echo number_format($dress['price'], 2); ?>">
                                    <input type="hidden" name="dress_image" value="<?php echo htmlspecialchars($dress['image_url']); ?>">
                                    <button type="submit" name="rent_now" class="rent-button" onclick="event.stopPropagation()">Rent Now</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Dior Brand Section -->
            <?php if (!empty($dior_dresses)): ?>
                <div class="brand-section">
                    <img src="https://images.seeklogo.com/logo-png/4/2/dior-logo-png_seeklogo-41696.png" alt="Dior Logo" class="brand-logo">
                    <div class="grid">
                        <?php foreach ($dior_dresses as $dress): ?>
                            <div class="item" onclick="openPopup('<?php echo htmlspecialchars($dress['image_url']); ?>')">
                                <img src="<?php echo htmlspecialchars($dress['image_url']); ?>" alt="<?php echo htmlspecialchars($dress['name']); ?>">
                                <span class="price-tag">₹<?php echo number_format($dress['price'], 2); ?>/day</span>
                                <p><?php echo htmlspecialchars($dress['name']); ?></p>
                                <form action="top_brand.php" method="POST">
                                    <input type="hidden" name="dress_id" value="<?php echo $dress['id']; ?>">
                                    <input type="hidden" name="dress_name" value="<?php echo htmlspecialchars($dress['name']); ?>">
                                    <input type="hidden" name="dress_price" value="<?php echo number_format($dress['price'], 2); ?>">
                                    <input type="hidden" name="dress_image" value="<?php echo htmlspecialchars($dress['image_url']); ?>">
                                    <button type="submit" name="rent_now" class="rent-button" onclick="event.stopPropagation()">Rent Now</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Prada Brand Section -->
            <?php if (!empty($prada_dresses)): ?>
                <div class="brand-section">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJQAAACUCAMAAABC4vDmAAAAbFBMVEX///8AAACsrKzBwcH7+/vt7e3l5eW+vr7o6Og6OjqwsLBXV1e3t7cHBwdcXFzi4uL19fWkpKTOzs5lZWXV1dVFRUUnJyeCgoLIyMiJiYkYGBhsbGwQEBAgICBAQEB0dHSTk5MxMTGbm5tMTEzb+4vRAAADYElEQVR4nO2X24LiIAyGS8/WOj1hrYfq6Lz/O25JQAHRdS52r/7vShoS/oaQYhQBAAAAAAAAAAAAAAAAAAAAAAAAAAAAwP+irIuEKIgk6XNj6j1L0rSOa1t3MdG7IdtCOxJ9njnWvNNe7mOHLBbMWTGqXxfJJrljy6gsK5pTOL7yW/u6IdPiLGyuO1t1Og38+PIuVwVN+WpThaTFJ226kKmOlCU6qt8713fi+HsvZHtire1CfVUCStus30W+EZWRjq+UR6UazDpGrEXxK36pQeU6dzTj0Hgxt1YCS6XqnD+bh/RTUdFejY78u7JFRY0arNxSSHkvBi/m2t5VSufWrkd5IHvysajeiueKimjkVXUc3ApHVPq8xVQKYvWxKEn10IRE/ahR7Di3A7/02j2YAVEHS7c8zfTs9rEo8VIUlULnOPdiGt1Jr0TZjpeZI88v24InavrV9l130Y3XdKo2JOq+QpQt1cQb+LIteKIGq3BdUVRto3O4pdL4lIiwqM19h/fnjE+N2LxqC64oStQhD4jKaPeclpAORyNWbOy2EBIljKiGgtApf9kWtCj276gCTfNkUdzGJZW52zwbPnXUwEwb+buoGxmaMVANniiL4/2dK8+ydcs5+uFtztg6PQzvRKX6ANf0cAynikVtzQd0ko9pLGpZ4dZP02Jxz/1SUVVWLmQXfytCotbafJtlSV5X8VQPnqivkIlFJf0ojonMS+m14IuYNwTvhNUWQqJ0Uyqvh5P22qjHp2Bb8E7fs6g6KpdVT9fV7Ha7XNRlTpTc1x8dOiDqpA9aLHJNxp8o/2v+qago3Zl6s6YN18dv3orvN6LMEbFerHTU/lrU/bohhnu2G7scuGpPpok5ouhuZD7ksX2j4FoMdVAWtQ6Jsq8u+uokZhN0t7LqPl078e2rC539lfZqt3ZT6fnDGWgLCdWbCNXbxU58r1vHgWM04tueypnccFtoebE0y9qmU5qOJoWxcK5e/Co/3qmO2oo/2GJV+LmSe52cPe96u9bjKo3yJYlbK36z1bauWa7DZsCMO9PB+iXi3lKQsHhxrl1Zjb7Gx3HniyqMqdNB25rHVRv13eOxYrLmtibi0x+HSnk97qBpfF/bv7oCAAAAAAAAAAAAAAAAAAAAAAAAAAAAwL/jD43HJ4VaV/0CAAAAAElFTkSuQmCC" alt="Prada Logo" class="brand-logo">
                    <div class="grid">
                        <?php foreach ($prada_dresses as $dress): ?>
                            <div class="item" onclick="openPopup('<?php echo htmlspecialchars($dress['image_url']); ?>')">
                                <img src="<?php echo htmlspecialchars($dress['image_url']); ?>" alt="<?php echo htmlspecialchars($dress['name']); ?>">
                                <span class="price-tag">₹<?php echo number_format($dress['price'], 2); ?>/day</span>
                                <p><?php echo htmlspecialchars($dress['name']); ?></p>
                                <form action="top_brand.php" method="POST">
                                    <input type="hidden" name="dress_id" value="<?php echo $dress['id']; ?>">
                                    <input type="hidden" name="dress_name" value="<?php echo htmlspecialchars($dress['name']); ?>">
                                    <input type="hidden" name="dress_price" value="<?php echo number_format($dress['price'], 2); ?>">
                                    <input type="hidden" name="dress_image" value="<?php echo htmlspecialchars($dress['image_url']); ?>">
                                    <button type="submit" name="rent_now" class="rent-button" onclick="event.stopPropagation()">Rent Now</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Gucci Brand Section -->
            <?php if (!empty($gucci_dresses)): ?>
                <div class="brand-section">
                    <img src="data:image/jpeg;base64,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" alt="Gucci Logo" class="brand-logo">
                    <div class="grid">
                        <?php foreach ($gucci_dresses as $dress): ?>
                            <div class="item" onclick="openPopup('<?php echo htmlspecialchars($dress['image_url']); ?>')">
                                <img src="<?php echo htmlspecialchars($dress['image_url']); ?>" alt="<?php echo htmlspecialchars($dress['name']); ?>">
                                <span class="price-tag">₹<?php echo number_format($dress['price'], 2); ?>/day</span>
                                <p><?php echo htmlspecialchars($dress['name']); ?></p>
                                <form action="top_brand.php" method="POST">
                                    <input type="hidden" name="dress_id" value="<?php echo $dress['id']; ?>">
                                    <input type="hidden" name="dress_name" value="<?php echo htmlspecialchars($dress['name']); ?>">
                                    <input type="hidden" name="dress_price" value="<?php echo number_format($dress['price'], 2); ?>">
                                    <input type="hidden" name="dress_image" value="<?php echo htmlspecialchars($dress['image_url']); ?>">
                                    <button type="submit" name="rent_now" class="rent-button" onclick="event.stopPropagation()">Rent Now</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
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

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h4>About Us</h4>
                <ul>
                    <li><a href="../page/about.html">Our Story</a></li>
                    <li><a href="../page/contact_us.html">Contact Us</a></li>
                    <li><a href="../page/FAQs.html">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="../page/return.html">Returns</a></li>
                    <li><a href="../page/Finding Your Fit.html">Size Guide</a></li>
                    <li><a href="../page/How_It_Works.php">How It Works</a></li>
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
                        <p>₹${item.price}/day</p>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="index" value="${index}">
                            <input type="hidden" name="dress_id" value="${item.id}">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                `;
                cartItemsContainer.appendChild(cartItem);
            });

            // Show the cart popup
            cartPopup.style.display = 'flex';
        }

        // Close cart popup
        function closeCart() {
            const cartPopup = document.getElementById('cart-popup');
            cartPopup.style.display = 'none';
        }

        // Open image popup
        function openPopup(src) {
            const cartPopup = document.getElementById('cart-popup');
            if (cartPopup.style.display === 'flex') {
                return; // Prevent image popup if cart popup is open
            }
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