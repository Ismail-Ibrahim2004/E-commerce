<?php
include 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, name, password, role, image FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role, $image);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_role"] = $role;

            //  الكائن الموحد للجلسة
            $_SESSION["users"] = serialize((object)[
                "id" => $id,
                "name" => $name,
                "role" => $role,
                "image" => $image
            ]);

            // التوجيه حسب الدور
            if ($role === 'admin') {
                header("Location: includes/templates/Admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $message = "The password is incorrect!";
        }
    } else {
        $message = "There is no account registered with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="layout/css/login.css">
    <script src="layout/js/auth_js/login.js"></script>
</head>

<body class="style">
    <div class="page">
        <div id="errorEl"></div>
        <form id="loginForm" action="" method="post" onsubmit="return validateForm(event)">
            <h1 class="head">Login</h1>
            <p class="para">Enter your credentials to log in and enjoy our services</p>

            <div>
                <input type="email" id="email" name="email" placeholder="Email Address" required>
            </div>

            <div>
                <input type="password" id="pass" name="password" placeholder="Password" required>
            </div>

            <div>
                <a class="forget" href="change password.php">Forgot password?</a>
            </div>

            <div>
                <input class="login" type="submit" value="Login">
            </div>

            <div>
                <input class="Register" type="button" value="Register" onclick="window.location.href='register.php';">
            </div>

            <div id="formMessage">
                <?php
                if (!empty($message)) {
                    echo "<p style='color: red; text-align:center;'>$message</p>";
                }
                ?>
            </div>
        </form>
    </div>

    <div class="image">
        <img class="image-img" src="layout/img/auth_img/2005.png" alt="welcome image">
        <caption>
            <h1 class="image-head">Welcome</h1>
            <p class="image-para">Enter your personal details to use all of site features</p>
        </caption>
    </div>
</body>

</html>
