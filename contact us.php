<?php
include 'db.php';
session_start();

$messageSent = "";

// استلام البيانات عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST["FirstName"]);
    $lastName = trim($_POST["LastName"]);
    $email = trim($_POST["Email"]);
    $phone = trim($_POST["PhoneNumber"]);
    $msg = trim($_POST["Message"]);

    // إعداد الاستعلام لتخزين الرسالة في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO contact_messages (first_name, last_name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $msg);

    if ($stmt->execute()) {
        $messageSent = "تم إرسال رسالتك بنجاح! سنقوم بالرد عليك قريبًا.";
    } else {
        $messageSent = "حدث خطأ أثناء إرسال الرسالة. حاول مرة أخرى.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us</title>
  <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
  <link rel="stylesheet" href="layout/css/contact_us.css">
</head>

<body>
  <div class="contact-container">
    <div class="contact-form">
      <div class="contact-header">
        <img src="layout/img/contact_us/icon.png" alt="Contact Icon">
        <div>
          <h2>Contact Us</h2>
          <p class="subheading">Send us your message and we'll get back to you.</p>
        </div>
      </div>
      <form id="contactForm" action="" method="post">
        <input class="input" type="text" name="FirstName" placeholder="First Name" required />
        <input class="input" type="text" name="LastName" placeholder="Last Name" required />
        <input class="input" type="email" name="Email" placeholder="Email Address" required />
        <input class="input" type="text" name="PhoneNumber" placeholder="Phone Number" required />
        <textarea class="textinput" name="Message" placeholder="Message" required></textarea>
        <button type="submit" class="send">Send</button>
      </form>

      <?php if (!empty($messageSent)): ?>
        <p style="color: green; text-align:center; margin-top: 10px;"><?php echo $messageSent; ?></p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>
