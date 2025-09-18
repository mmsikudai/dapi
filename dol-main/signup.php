<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'conf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Get form values
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    // 2. Hash password
    $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

    // 3. Generate verification token
    $token = bin2hex(random_bytes(16));

    // 4. Save to database
    $mysqli = new mysqli($conf['db_host'], $conf['db_user'], $conf['db_pass'], $conf['db_name']);

    // Check if email already exists
    $check = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "This email is already registered. Please log in.";
        $check->close();
        $mysqli->close();
        exit;
    }
    $check->close();

    // Insert new user
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password, token) VALUES (?, ?, ?, ?)");
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
