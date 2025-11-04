<?php
include '../includes/db.php';
session_start();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_input = $_POST['user']; // email or phone
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? OR phone=?");
    $stmt->execute([$user_input,$user_input]);
    if($user = $stmt->fetch()){
        if(password_verify($password, $user['password'])){
            if($user['email_verified'] || $user['phone_verified']){
                $_SESSION['user_id'] = $user['id'];
                header("Location: account.php");
            } else {
                echo "Please verify your email or phone first.";
            }
        } else echo "Incorrect password.";
    } else echo "User not found.";
}
?>
<form method="POST">
    <input name="user" placeholder="Email or Phone" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
