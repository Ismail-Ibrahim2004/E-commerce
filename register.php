<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["pass"]);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $message = "This email is already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
            header("Location: login.php");
            exit(); // مهم جدًا علشان يوقف تنفيذ الصفحة بعد التحويل

        } else {
            $message = "Error occurred during registration.";
        }
        $stmt->close();
    }

    $check_email->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <link rel="stylesheet" href="layout/css/register.css">
    <script src="layout/js/auth_js/register.js"></script>
</head>

<body>
    <div class="container">

        <div class="infoBox">
            <div class="img">
                <img src="layout/img/auth_img/Webinar-pana 1.png" alt="welcome">
            </div>
            <p class="pargraph">
                By creating a new account, you'll gain access to all our services completely free and without any ads!
            </p>
        </div>

        <div>
            <div class="formBox">
                <div class="header">
                    <h1>Register</h1>
                </div>
                <div class="caption">
                    <p>
                        Create your account to start exploring our features.
                    </p>
                </div>
                <form action="" id="registerForm" method="post" onsubmit="return validateForm(event)">

                    <div>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>

                    <div>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>

                    <div>
                        <input type="password" id="pass" name="pass" placeholder="Password" required>
                    </div>

                    <div>
                        <button class="Register" type="submit">Register</button>
                    </div>

                    <div>
                        <a class="forget" href="login.php">I already have an account</a>
                    </div>

                    <div id="formMessage">
                        <?php if (isset($message)) echo "<p style='color: green; text-align:center;'>$message</p>"; ?>
                    </div>

                </form>
            </div>
        </div>

    </div>
</body>

</html>