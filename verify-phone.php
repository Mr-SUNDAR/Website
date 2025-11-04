<?php
include '../includes/db.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $code = $_POST['code'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE phone_verification_code=?");
    $stmt->execute([$code]);
    if($user = $stmt->fetch()){
        $update = $pdo->prepare("UPDATE users SET phone_verified=1, phone_verification_code=NULL WHERE id=?");
        $update->execute([$user['id']]);
        echo "Phone verified successfully!";
    } else {
        echo "Invalid code.";
    }
}
?>
<form method="POST">
    <input name="code" placeholder="Enter phone code" required>
    <button type="submit">Verify</button>
</form>
