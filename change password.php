<?php
include 'db.php';
session_start();

$step = 1;
$error = "";
$success = "";

// التحقق من الإيميل
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION["reset_email"] = $email;
        $step = 2; // الانتقال لخطوة تعيين كلمة السر
    } else {
        $error = "Email not registered.";
    }

    $stmt->close();
}

// تحديث كلمة المرور
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_password"])) {
    $new_password = password_hash(trim($_POST["new_password"]), PASSWORD_DEFAULT);
    $email = $_SESSION["reset_email"];

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        $success = "Your password has been changed successfully.";
        unset($_SESSION["reset_email"]);
        $step = 1;
    } else {
        $error = "An error occurred while changing your password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Change Password</title>
  <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
  <link rel="stylesheet" href="layout/css/change_password.css" />
</head>
<body>

<div class="container">
  <h2>Change Your Password</h2>

  <?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
  <?php endif; ?>

  <?php if ($step == 1): ?>
    <form method="post">
      <label for="email">Email Address:</label>
      <input type="email" name="email" placeholder="Enter your email" required>
      <button type="submit">Continue</button>
    </form>
  <?php elseif ($step == 2): ?>
    <form method="post">
      <label for="new_password">New Password:</label>
      <input type="password" name="new_password" placeholder="Enter new password" required>
      <button type="submit">Reset Password</button>
    </form>
  <?php endif; ?>

  <a href="login.php"><button class="button2">Back to Login</button></a>
</div>

</body>
</html>
