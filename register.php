<?php
include '../includes/db.php';
include '../includes/functions.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Generate verification codes
    $email_code = bin2hex(random_bytes(16));
    $phone_code = rand(100000,999999);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,password,email_verification_code,phone_verification_code) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$name,$email,$phone,$password,$email_code,$phone_code]);

    // Send Email
    send_email($email, "Verify Email", "Click to verify: https://yourdomain.com/shop/verify-email.php?code=$email_code");

    // Send SMS
    send_sms($phone, "Your verification code: $phone_code");

    echo "Registration successful! Please verify your email and phone.";
}
?>
<form method="POST">
    <input name="name" placeholder="Full Name" required>
    <input name="email" placeholder="Email" required>
    <input name="phone" placeholder="Phone" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
