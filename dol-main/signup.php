<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'ClassAutoLoad.php'; // loads PHPMailer, configs, etc.
require_once 'conf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Get form values
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    // 2. Hash password (important for security)
    $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

    // 3. Generate verification token
    $token = bin2hex(random_bytes(16));

    // 4. Save to database (pseudo-code, you must have a DB + users table)
    $mysqli = new mysqli($conf['db_host'], $conf['db_user'], $conf['db_pass'], $conf['db_name']);
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password, token, verified) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $name, $email, $hashedPass, $token);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();

    // 5. Send verification email
    $verifyLink = $conf['site_url'] . "/verify.php?token=$token&email=" . urlencode($email);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $conf['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $conf['smtp_user'];
        $mail->Password   = $conf['smtp_pass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $conf['smtp_port'];

        $mail->setFrom($conf['smtp_user'], $conf['site_name']);
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "Welcome to {$conf['site_name']}! Account Verification";
        $mail->Body    = "
            Hello $name,<br><br>
            You requested an account on {$conf['site_name']}.<br>
            In order to use this account you need to <a href='$verifyLink'>Click Here</a> to complete the registration process.<br><br>
            Regards,<br>
            Systems Admin<br>
            {$conf['site_name']}
        ";

        $mail->send();
        echo "Signup successful! Please check your email to verify your account.";
    } catch (Exception $e) {
        echo "Signup saved, but email could not be sent. Error: {$mail->ErrorInfo}";
    }
}
?>

<!-- HTML signup form -->
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>
    <h2>Signup Form</h2>
    <form method="post" action="">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Sign Up">
    </form>
</body>
</html>