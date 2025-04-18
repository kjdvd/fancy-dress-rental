<?php
header('Content-Type: application/json');

$api_key = 'rzp_test_YFMHtqtR7lJY4y'; // Your test key ID
$api_secret = 'your_actual_secret_key'; // Replace with your Razorpay test secret key

$input = json_decode(file_get_contents('php://input'), true);
$amount = $input['amount'];

if (!$amount || !is_numeric($amount) || $amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'amount' => $amount,
    'currency' => 'INR',
    'receipt' => 'receipt_' . time()
]));
curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $api_secret);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

echo $response;
?>