<?php
// shop.php
session_start();

// Handle form submission for adding to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $dress_id = $_POST['dress_id'];
    $dress_name = $_POST['dress_name'];

    // Add the dress to the session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add the dress to the cart
    $_SESSION['cart'][] = [
        'id' => $dress_id,
        'name' => $dress_name
    ];

    // Redirect back to shop.php to avoid form resubmission
    header("Location: shop.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Fancy Dress Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="page/styles.css"> <!-- Link to your styles.css -->
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

        /* Styles from shop.html */
        .container { display: flex; margin: 20px; }
        .sidebar { width: 20%; background: white; padding: 20px; border-radius: 10px; text-align: left; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); }
        .sidebar h2 { font-size: 20px; margin-bottom: 15px; color: #45BABB; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { padding: 10px 0; }
        .sidebar ul li a { text-decoration: none; color: black; font-weight: 500; }
        .sidebar ul li a:hover { color: #FCBEB5; }
        .content { flex: 1; padding: 20px; background: white; border-radius: 10px; margin-left: 20px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; }
        .item { text-decoration: none; color: black; background: white; padding: 10px; border-radius: 10px; transition: transform 0.3s; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); cursor: pointer; }
        .item:hover { transform: scale(1.05); }
        .item img { width: 100%; height: 250px; border-radius: 10px; }
        .item p { font-weight: bold; margin-top: 10px; }
        .popup { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center; }
        .popup img { max-width: 80%; max-height: 80%; border-radius: 10px; box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.5); }
        .popup span { position: absolute; top: 20px; right: 30px; font-size: 40px; color: white; cursor: pointer; }
    </style>
</head>
<body>
    <!-- Header -->
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

    <!-- Main Content -->
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Explore Categories</h2>
            <ul>
                <li><a href="gown.php">Dresses</a></li>
                <li><a href="cocktail_party.php">Cocktail & Party</a></li>
                <li><a href="mini.php">Mini</a></li>
                <li><a href="co_ords.php">Co-ords</a></li>
                <li><a href="mermaid_dress.php">Mermaid Dress</a></li>
                <li><a href="top_brand.php">Top Brand</a></li>
                <li><a href="page/jewelry.html">Jewelry</a></li>
            </ul>
        </aside>

        <!-- Content Grid -->
        <main class="content">
            <h2 class="category-title">DRESSES</h2>
            <div class="grid">
                <div class="item" onclick="openPopup('https://i.pinimg.com/474x/21/98/c5/2198c5f4d3317de6b76255d0fa63572e.jpg')">
                    <img src="https://i.pinimg.com/474x/21/98/c5/2198c5f4d3317de6b76255d0fa63572e.jpg" alt="Mini Dress">
                    <p>Mini</p>
                    <form action="shop.php" method="POST">
                        <input type="hidden" name="dress_id" value="1">
                        <input type="hidden" name="dress_name" value="Mini Dress">
                        <input type="submit" name="add_to_cart" value="Add to Cart" class="btn btn-primary">
                    </form>
                </div>
                <div class="item" onclick="openPopup('https://i.pinimg.com/474x/99/83/1e/99831e292d47b9386251b00bd45e81a5.jpg')">
                    <img src="https://i.pinimg.com/474x/99/83/1e/99831e292d47b9386251b00bd45e81a5.jpg" alt="Cocktail & Party Dress">
                    <p>Cocktail & Party</p>
                    <form action="shop.php" method="POST">
                        <input type="hidden" name="dress_id" value="2">
                        <input type="hidden" name="dress_name" value="Cocktail & Party Dress">
                        <input type="submit" name="add_to_cart" value="Add to Cart" class="btn btn-primary">
                    </form>
                </div>
                <div class="item" onclick="openPopup('https://i.pinimg.com/474x/27/f4/17/27f417d01748ffd6a8934eba2cbd7167.jpg')">
                    <img src="https://i.pinimg.com/474x/27/f4/17/27f417d01748ffd6a8934eba2cbd7167.jpg" alt="Midi Dress">
                    <p>Midi</p>
                    <form action="shop.php" method="POST">
                        <input type="hidden" name="dress_id" value="3">
                        <input type="hidden" name="dress_name" value="Midi Dress">
                        <input type="submit" name="add_to_cart" value="Add to Cart" class="btn btn-primary">
                    </form>
                </div>
                <div class="item" onclick="openPopup('https://i.pinimg.com/474x/a7/63/bd/a763bde8dbd4e5bfb312f30b26d68dd4.jpg')">
                    <img src="https://i.pinimg.com/474x/a7/63/bd/a763bde8dbd4e5bfb312f30b26d68dd4.jpg" alt="Mermaid Dress">
                    <p>Mermaid</p>
                    <form action="shop.php" method="POST">
                        <input type="hidden" name="dress_id" value="4">
                        <input type="hidden" name="dress_name" value="Mermaid Dress">
                        <input type="submit" name="add_to_cart" value="Add to Cart" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Popup Image -->
    <div class="popup" id="popup">
        <span onclick="closePopup()">×</span>
        <img id="popup-img" src="" alt="Popup Image">
    </div>

    <script>
        // Sync session cart with localStorage
        let cart = <?php echo isset($_SESSION['cart']) ? json_encode($_SESSION['cart']) : '[]'; ?>;
        localStorage.setItem('cart', JSON.stringify(cart));

        window.onload = function() {
            updateCartCount();
        };

        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = cart.length;
        }

        // Popup functionality
        function openPopup(src) {
            let popup = document.createElement("div");
            popup.className = "popup";
            popup.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); display: flex; justify-content: center; align-items: center;";
            
            let img = document.createElement("img");
            img.src = src;
            img.style = "max-width: 80%; max-height: 80%; border-radius: 10px;";
            
            let closeBtn = document.createElement("span");
            closeBtn.innerHTML = "×";
            closeBtn.style = "position: absolute; top: 20px; right: 30px; font-size: 40px; color: white; cursor: pointer;";
            closeBtn.onclick = function() {
                document.body.removeChild(popup);
            };

            popup.appendChild(img);
            popup.appendChild(closeBtn);
            document.body.appendChild(popup);
        }
    </script>
</body>
</html>