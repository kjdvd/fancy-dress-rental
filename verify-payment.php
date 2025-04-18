<?php
header('Content-Type: application/json');

$api_secret = 'rzp_test_your_secret_key'; // Replace with your Razorpay test secret key

$input = json_decode(file_get_contents('php://input'), true);
$razorpay_payment_id = $input['razorpay_payment_id'];
$razorpay_order_id = $input['razorpay_order_id'];
$razorpay_signature = $input['razorpay_signature'];

$generated_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $api_secret);

if ($generated_signature === $razorpay_signature) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Signature verification failed']);
}
?>