<?php include 'header.php'; ?>
<?php
// verify_payment.php
$keyId = "rzp_test_RWp002mNHfI31m";
$keySecret = "vcMvE7qz80ATtkJ78UzvtagE";
$telegramBot = "8408604987:AAE3UpP_-SAKAuG9v6rRRVQqO-06n9BMD1E";
$chatId = "901714222";
$n8nWebhook = "https://automation.sundarrajan.org/webhook/shoping";
header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);
if (!$input) $input = $_POST;
$paymentId = $input['razorpay_payment_id'] ?? '';
$orderId = $input['razorpay_order_id'] ?? '';
$signature = $input['razorpay_signature'] ?? '';
if (!$paymentId || !$orderId || !$signature) { echo json_encode(["error" => "Missing fields"]); exit; }
$generated_signature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);
if (!hash_equals($generated_signature, $signature)) { echo json_encode(["error" => "Invalid signature"]); exit; }
// forward to n8n
$payload = json_encode(["payment_id" => $paymentId, "order_id" => $orderId, "status" => "success"]);
$ch = curl_init($n8nWebhook);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$n8n_resp = curl_exec($ch);
curl_close($ch);
// telegram message
$msg = "🛍️ New Order Paid\nOrder: $orderId\nPayment: $paymentId";
$telegram_url = "https://api.telegram.org/bot{$telegramBot}/sendMessage";
curl_setopt_array($ch = curl_init(), [
    CURLOPT_URL => $telegram_url,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => http_build_query(["chat_id" => $chatId, "text" => $msg])
]);
$tg_resp = curl_exec($ch);
curl_close($ch);
echo json_encode(["ok" => true, "n8n" => $n8n_resp, "tg" => $tg_resp]);
?>
<?php include 'footer.php'; ?>
