<?php
include 'db.php';
session_start();

$messageSent = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استلام البيانات وتأمينها
    $firstName = htmlspecialchars(trim($_POST["FirstName"] ?? ''));
    $lastName = htmlspecialchars(trim($_POST["LastName"] ?? ''));
    $email = htmlspecialchars(trim($_POST["Email"] ?? ''));
    $phone = htmlspecialchars(trim($_POST["PhoneNumber"] ?? ''));
    $msg = htmlspecialchars(trim($_POST["Message"] ?? ''));

    $name = $firstName . ' ' . $lastName;

    // تحقق أن الحقول ليست فارغة
    if ($name && $email && $msg) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $msg);

        if ($stmt->execute()) {
            $messageSent = "✅ تم إرسال رسالتك بنجاح! سنقوم بالرد عليك قريبًا.";
        } else {
            $messageSent = "❌ حدث خطأ أثناء إرسال الرسالة. حاول مرة أخرى.";
        }

        $stmt->close();
    } else {
        $messageSent = "❗ من فضلك املأ جميع الحقول المطلوبة.";
    }
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