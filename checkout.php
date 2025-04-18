<?php
// checkout.php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

// Load cart from session (server-side validation)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Fancy Dress Rental</title>
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
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .cart-summary {
            width: 45%;
            padding: 20px;
        }
        .cart-summary h2 {
            font-size: 24px;
            color: #45BABB; /* Teal */
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .cart-item img {
            width: 80px;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-details h3 {
            font-size: 16px;
            margin: 0;
        }
        .cart-item-details p {
            margin: 5px 0;
            color: #666;
        }
        .cart-item-details select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
            color: #45BABB;
        }
        .payment-form {
            width: 50%;
            padding: 20px;
        }
        .payment-form h2 {
            font-size: 24px;
            color: #45BABB; /* Teal */
            margin-bottom: 20px;
        }
        .payment-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }
        .payment-form input, .payment-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .pay-button {
            width: 100%;
            padding: 15px;
            background-color: #45BABB; /* Teal */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            position: relative;
        }
        .pay-button:hover {
            background-color: #3A9A9B; /* Darker Teal */
        }
        .pay-button.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid #fff;
            border-top: 3px solid #45BABB;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        #payment-message {
            margin-top: 10px;
            color: #45BABB;
        }
        .error-message {
            color: #dc3545;
            margin-top: 5px;
            font-size: 14px;
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
                <span class="cart-count" id="cart-count"><?php echo count($cart); ?></span>
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

    <!-- Checkout Container -->
    <div class="container">
        <!-- Cart Summary -->
        <div class="cart-summary">
            <h2>Your Cart</h2>
            <div id="cart-items">
                <?php if (empty($cart)): ?>
                    <p>Your cart is empty</p>
                <?php else: ?>
                    <?php foreach ($cart as $index => $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="cart-item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>₹<?php echo htmlspecialchars($item['price']); ?>/day</p>
                                <label for="days-<?php echo $index; ?>">Rental Days:</label>
                                <select id="days-<?php echo $index; ?>" onchange="updateTotal()">
                                    <option value="1">1 Day</option>
                                    <option value="2">2 Days</option>
                                    <option value="3">3 Days</option>
                                    <option value="4">4 Days</option>
                                    <option value="5">5 Days</option>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="total" id="cart-total">Total: ₹<?php 
                $total = 0;
                foreach ($cart as $item) {
                    $price = (int) preg_replace('/[^0-9]/', '', $item['price']);
                    $total += $price;
                }
                echo $total;
            ?></div>
        </div>

        <!-- Payment Form -->
        <div class="payment-form">
            <h2>Payment Details</h2>
            <form id="payment-form">
                <input type="hidden" id="csrf-token" value="<?php echo $csrf_token; ?>">
                
                <label for="card-holder">Card Holder Name</label>
                <input type="text" id="card-holder" placeholder="Your Name" required>
                <div id="card-holder-error" class="error-message"></div>

                <label for="billing-address">Billing Address</label>
                <input type="text" id="billing-address" placeholder="Your Address" required>
                <div id="billing-address-error" class="error-message"></div>

                <label for="contact">Contact Number</label>
                <input type="text" id="contact" placeholder="Your Phone Number" required>
                <div id="contact-error" class="error-message"></div>

                <button type="submit" class="pay-button">Pay Now</button>
                <div id="payment-message"></div>
            </form>
        </div>
    </div>

    <!-- Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        // Server-side cart data
        const serverCart = <?php echo json_encode($cart); ?>;
        
        // Use server cart if available, otherwise fall back to localStorage
        let cart = serverCart.length > 0 ? serverCart : JSON.parse(localStorage.getItem('cart')) || [];
        
        // Update cart count and summary on page load
        window.onload = function() {
            updateCartCount();
            updateCartSummary();
        };

        // Update cart count in the header
        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = cart.length;
        }

        // Update cart summary with rental days
        function updateCartSummary() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');
            let total = 0;

            // Clear previous items
            cartItemsContainer.innerHTML = '';

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p>Your cart is empty</p>';
                cartTotalElement.textContent = 'Total: ₹0';
                return;
            }

            // Populate cart items with days selector
            cart.forEach((item, index) => {
                const basePrice = parseInt(item.price.replace(/[^0-9]/g, '')); // Extract numeric price
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}">
                    <div class="cart-item-details">
                        <h3>${item.name}</h3>
                        <p>₹${basePrice}/day</p>
                        <label for="days-${index}">Rental Days:</label>
                        <select id="days-${index}" onchange="updateTotal()">
                            <option value="1">1 Day</option>
                            <option value="2">2 Days</option>
                            <option value="3">3 Days</option>
                            <option value="4">4 Days</option>
                            <option value="5">5 Days</option>
                        </select>
                    </div>
                `;
                cartItemsContainer.appendChild(cartItem);

                // Calculate initial total (default 1 day)
                total += basePrice;
            });

            // Update total
            cartTotalElement.textContent = `Total: ₹${total}`;
        }

        // Update total when rental days change
        function updateTotal() {
            const cartTotalElement = document.getElementById('cart-total');
            let total = 0;

            cart.forEach((item, index) => {
                const basePrice = parseInt(item.price.replace(/[^0-9]/g, ''));
                const daysSelect = document.getElementById(`days-${index}`);
                const days = daysSelect ? parseInt(daysSelect.value) : 1;
                total += basePrice * days;
            });

            cartTotalElement.textContent = `Total: ₹${total}`;
        }

        // Validate phone number
        function validatePhone(phone) {
            const re = /^[0-9]{10}$/;
            return re.test(phone);
        }

        // Process payment with Razorpay
        const paymentForm = document.getElementById('payment-form');
        paymentForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            // Get billing details
            const cardHolder = document.getElementById('card-holder').value.trim();
            const billingAddress = document.getElementById('billing-address').value.trim();
            const contact = document.getElementById('contact').value.trim();
            const payButton = document.querySelector('.pay-button');
            const csrfToken = document.getElementById('csrf-token').value;

            // Validate inputs
            let isValid = true;
            
            if (!cardHolder) {
                document.getElementById('card-holder-error').textContent = 'Please enter card holder name';
                isValid = false;
            }
            
            if (!billingAddress) {
                document.getElementById('billing-address-error').textContent = 'Please enter billing address';
                isValid = false;
            }
            
            if (!contact) {
                document.getElementById('contact-error').textContent = 'Please enter contact number';
                isValid = false;
            } else if (!validatePhone(contact)) {
                document.getElementById('contact-error').textContent = 'Please enter a valid 10-digit phone number';
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            if (cart.length === 0) {
                document.getElementById('payment-message').textContent = 'Your cart is empty';
                return;
            }

            // Show loading state
            payButton.classList.add('loading');
            document.getElementById('payment-message').textContent = '';

            // Calculate total amount in paise (Razorpay uses the smallest currency unit)
            const totalAmount = calculateTotal() * 100; // Convert to paise
            console.log('Total Amount (paise):', totalAmount);

            // Create order via server
            let orderId;
            try {
                const response = await fetch('create-order.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    },
                    body: JSON.stringify({ 
                        amount: totalAmount,
                        cart: cart
                    })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.error || 'Failed to create order');
                }
                
                if (!data.id || typeof data.id !== 'string') {
                    throw new Error('Invalid or missing order ID from server');
                }
                
                orderId = data.id;
            } catch (error) {
                console.error('Error creating order:', error);
                document.getElementById('payment-message').textContent = error.message;
                payButton.classList.remove('loading');
                return;
            }

            // Prepare order items for processing
            const orderItems = cart.map((item, index) => {
                const daysSelect = document.getElementById(`days-${index}`);
                return {
                    name: item.name,
                    price: parseInt(item.price.replace(/[^0-9]/g, '')),
                    days: daysSelect ? parseInt(daysSelect.value) : 1,
                    image: item.image
                };
            });

            // Razorpay Checkout options
            const options = {
                key: 'rzp_test_YFMHtqtR7lJY4y', // Should be loaded from server/config
                amount: totalAmount,
                currency: 'INR',
                name: 'FancyDress',
                description: 'Dress Rental Payment',
                image: 'https://your-logo-url.com/logo.png',
                order_id: orderId,
                handler: async function(response) {
                    try {
                        // Verify payment
                        const verifyResponse = await verifyPayment(response);
                        
                        if (!verifyResponse.success) {
                            throw new Error(verifyResponse.message || 'Payment verification failed');
                        }

                        // Save order to database
                        const orderData = {
                            user_id: <?php echo $_SESSION['user_id']; ?>,
                            total: calculateTotal(),
                            items: orderItems,
                            billing_address: billingAddress,
                            status: 'completed',
                            payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                            csrf_token: csrfToken
                        };

                        const saveResponse = await fetch('process-order.php', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': csrfToken
                            },
                            body: JSON.stringify(orderData)
                        });

                        const saveData = await saveResponse.json();
                        
                        if (!saveResponse.ok || !saveData.success) {
                            throw new Error(saveData.message || 'Order processing failed');
                        }

                        // Clear cart on success
                        cart = [];
                        localStorage.setItem('cart', JSON.stringify(cart));
                        
                        // Redirect to success page
                        window.location.href = 'order-success.php?order_id=' + saveData.order_id;
                    } catch (error) {
                        console.error('Payment processing error:', error);
                        document.getElementById('payment-message').textContent = error.message;
                    } finally {
                        payButton.classList.remove('loading');
                    }
                },
                prefill: {
                    name: cardHolder,
                    contact: contact,
                    email: '<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>'
                },
                notes: {
                    address: billingAddress,
                    user_id: <?php echo $_SESSION['user_id']; ?>
                },
                theme: {
                    color: '#45BABB'
                }
            };

            const rzp = new Razorpay(options);
            
            rzp.on('payment.failed', function(response) {
                document.getElementById('payment-message').textContent = 
                    `Payment failed: ${response.error.description}`;
                payButton.classList.remove('loading');
            });

            rzp.on('close', function() {
                payButton.classList.remove('loading');
            });

            rzp.open();
        });

        // Calculate total amount in rupees
        function calculateTotal() {
            let total = 0;
            cart.forEach((item, index) => {
                const basePrice = parseInt(item.price.replace(/[^0-9]/g, ''));
                const daysSelect = document.getElementById(`days-${index}`);
                const days = daysSelect ? parseInt(daysSelect.value) : 1;
                total += basePrice * days;
            });
            return total;
        }

        // Verify payment via server
        async function verifyPayment(response) {
            try {
                const verifyResponse = await fetch('verify-payment.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.getElementById('csrf-token').value
                    },
                    body: JSON.stringify(response)
                });
                return await verifyResponse.json();
            } catch (error) {
                console.error('Error verifying payment:', error);
                return { success: false, message: 'Verification failed' };
            }
        }
    </script>
</body>
</html>