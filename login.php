<?php
require_once "config.php";
require_once "functions.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT id,name,profile_image,password_hash FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $student = $stmt->fetch();
    if ($student && password_verify($password, $student["password_hash"])) {
        session_regenerate_id(true);
        $_SESSION["student_id"] = (int)$student["id"];
        $_SESSION["student_name"] = $student["name"];
        $_SESSION["profile_image"] = $student["profile_image"] ?? "";
        header("Location: dashboard.php");
        exit;
    }
    $error = "Incorrect email or password.";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head><meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Login | Internship Tracker</title><link rel="stylesheet" href="assets/style.css" /></head>
<body class="auth-page">
<main class="auth-card">
    <a class="auth-back" href="index.php"><span aria-hidden="true">←</span> Back to home</a>
    <a class="brand" href="index.php">InternTrack</a>
    <h1>Welcome back</h1><p class="muted">Sign in to manage your applications.</p>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="login.php">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>" />
        <label>Email address</label><input type="email" name="email" required placeholder="you@example.com" />
        <label>Password</label><input type="password" name="password" required placeholder="Your password" />
        <button class="btn full" type="submit">Sign in</button>
    </form>
    <p class="auth-switch">New here? <a href="register.php">Create an account</a></p>
</main>
</body></html>
