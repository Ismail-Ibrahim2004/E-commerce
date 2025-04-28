<?php
include 'db.php';

// 3. استقبال البيانات من الفورم
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$card_number = $_POST['card_number'];
$expiration = $_POST['expiration'];
$cvv = $_POST['cvv'];
$postal_code = $_POST['postal_code'];
$email = $_POST['email'];

// 4. تحضير الاستعلام (Query) للإدخال
$sql = "INSERT INTO payments (first_name, last_name, card_number, expiration, cvv, postal_code, email)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// 5. تجهيز و تنفيذ الاستعلام بشكل آمن (prepared statement)
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $first_name, $last_name, $card_number, $expiration, $cvv, $postal_code, $email);

// 6. تنفيذ وإعطاء نتيجة
if ($stmt->execute()) {
    echo "<h2> Payment received successfully!</h2>";
    echo "<a href='Payment.html'>Back to payment page</a>";
} else {
    echo " Error: " . $stmt->error;
}

// 7. غلق الاتصال
$stmt->close();
$conn->close();
?>