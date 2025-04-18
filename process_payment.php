<?php
session_start();

$cardHolder = $_POST['card_holder'];
$billing = $_POST['billing_address'];
$contact = $_POST['contact'];
$amount = $_POST['amount'];

// You can simulate validation here
if (!$cardHolder || !$billing || !$contact || !$amount) {
    echo "Payment Failed: Missing details";
    exit;
}

// Simulate a "success"
$_SESSION['payment_status'] = "success";
$_SESSION['paid_amount'] = $amount;

// Redirect to success page
header("Location: success.html");
exit;
?>
