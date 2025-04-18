<?php
// remove_from_cart.php
session_start();

// Include database connection (using PDO)
require_once 'db_connect.php';

// Handle form submission for removing from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['index'])) {
    $index = (int)$_POST['index'];
    $dress_id = (int)$_POST['dress_id'];

    try {
        // Remove from session cart
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
        }

        // Remove from database cart if user is logged in
        if (isset($_SESSION['user_id']) && $dress_id > 0) {
            $user_id = $_SESSION['user_id'];
            $query = "DELETE FROM cart WHERE user_id = :user_id AND dress_id = :dress_id LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['user_id' => $user_id, 'dress_id' => $dress_id]);
        }

    } catch (PDOException $e) {
        // Log the error (in a production environment, you might log to a file)
        error_log("Error removing from cart: " . $e->getMessage());
        // Optionally, set an error message in the session to display to the user
        $_SESSION['error_message'] = "An error occurred while removing the item from your cart. Please try again.";
    }
} else {
    // If the request is invalid, set an error message
    $_SESSION['error_message'] = "Invalid request to remove item from cart.";
}

// Redirect back to the referring page
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dress.php';
header("Location: $referer");
exit();
?>