<?php
include '../includes/db.php';

if(isset($_GET['code'])){
    $code = $_GET['code'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email_verification_code=?");
    $stmt->execute([$code]);
    if($user = $stmt->fetch()){
        $update = $pdo->prepare("UPDATE users SET email_verified=1, email_verification_code=NULL WHERE id=?");
        $update->execute([$user['id']]);
        echo "Email verified successfully!";
    } else {
        echo "Invalid or expired code.";
    }
}
?>
