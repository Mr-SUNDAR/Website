<?php include 'header.php'; ?>
<?php
// razorpay_order.php
$keyId = "rzp_test_RWp002mNHfI31m";
$keySecret = "vcMvE7qz80ATtkJ78UzvtagE";
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) $data = $_POST;
$amount = isset($data['amount']) ? (float)$data['amount'] : 0;
if ($amount <= 0) { echo json_encode(["error" => "Invalid amount"]); exit; }
$amountPaise = intval($amount * 100);
$orderData = ['amount' => $amountPaise,'currency' => 'INR','receipt' => 'rcptid_' . time()];
$ch = curl_init("https://api.razorpay.com/v1/orders");
curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
if (curl_errno($ch)) { echo json_encode(["error" => curl_error($ch)]); exit; }
curl_close($ch);
echo $response;
?>
<?php include 'footer.php'; ?>
