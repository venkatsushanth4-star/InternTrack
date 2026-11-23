<?php
require_once "config.php";
require_once "functions.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $college = trim($_POST["college"] ?? "");
    $password = $_POST["password"] ?? "";
    $profileImage = null;
    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = "Enter your name, a valid email and a password with at least 8 characters.";
    } elseif (empty($_FILES["profile_image"]["name"])) {
        $error = "A profile picture is required.";
    } else {
        $file = $_FILES["profile_image"];
        $allowedTypes = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];
        $imageInfo = @getimagesize($file["tmp_name"]);
        $mime = $imageInfo ? ($imageInfo["mime"] ?? "") : "";
        if ($file["error"] !== UPLOAD_ERR_OK) {
            $error = "The profile picture could not be uploaded.";
        } elseif ($file["size"] > 3 * 1024 * 1024) {
            $error = "The profile picture must be smaller than 3 MB.";
        } elseif (!isset($allowedTypes[$mime])) {
            $error = "Upload a JPG, PNG or WebP profile picture.";
        } else {
            $uploadDir = __DIR__ . "/uploads/profiles";
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                $error = "Could not create the profile picture folder.";
            } else {
                $filename = "student-" . bin2hex(random_bytes(12)) . "." . $allowedTypes[$mime];
                if (!move_uploaded_file($file["tmp_name"], $uploadDir . "/" . $filename)) {
                    $error = "Could not save the profile picture.";
                } else {
                    $profileImage = "uploads/profiles/" . $filename;
                }
            }
        }
        if (!$error && $profileImage) {
            try {
                $stmt = $pdo->prepare("INSERT INTO students (name,email,college,profile_image,password_hash) VALUES (?,?,?,?,?)");
                $stmt->execute([$name, $email, $college, $profileImage, password_hash($password, PASSWORD_DEFAULT)]);
                $_SESSION["student_id"] = (int)$pdo->lastInsertId();
                $_SESSION["student_name"] = $name;
                $_SESSION["profile_image"] = $profileImage;
                header("Location: dashboard.php");
                exit;
            } catch (PDOException $ex) {
                if (is_file(__DIR__ . "/" . $profileImage)) {
                    unlink(__DIR__ . "/" . $profileImage);
                }
                $error = "That email address is already registered.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head><meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Register | Internship Tracker</title><link rel="stylesheet" href="assets/style.css" /></head>
<body class="auth-page">
<main class="auth-card">
    <a class="auth-back" href="index.php"><span aria-hidden="true">←</span> Back to home</a>
    <a class="brand" href="index.php">InternTrack</a>
    <h1>Create your account</h1><p class="muted">Start organizing your internship journey.</p>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="register.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>" />
        <label>Full name</label><input name="name" required maxlength="100" placeholder="Your name" />
        <label>Email address</label><input type="email" name="email" required maxlength="150" placeholder="you@example.com" />
        <label>College / University</label><input name="college" maxlength="150" placeholder="Your college" />
        <label>Password</label><input type="password" name="password" minlength="8" required placeholder="At least 8 characters" />
        <label>Profile picture <span class="required">*</span></label>
        <input type="file" name="profile_image" accept="image/jpeg,image/png,image/webp" required />
        <small class="muted">JPG, PNG or WebP; maximum 3 MB.</small>
        <button class="btn full" type="submit">Create account</button>
    </form>
    <p class="auth-switch">Already registered? <a href="login.php">Sign in</a></p>
</main>
</body></html>
