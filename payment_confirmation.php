<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// الحصول على user_id من الجلسة
$user_id = $_SESSION['user_id'];

// استقبال بيانات الدفع
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$card_number = $_POST['card_number'] ?? '';
$expiration = $_POST['expiry'] ?? ''; // في الفورم كان اسمه expiry
$cvv = $_POST['cvv'] ?? '';
$postal_code = $_POST['postal_code'] ?? ''; // لو موجود في الفورم

// تسجيل الدفع في جدول payments
$stmt = $conn->prepare("INSERT INTO payments (user_id, first_name, last_name, email, card_number, expiration, cvv, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssss", $user_id, $first_name, $last_name, $email, $card_number, $expiration, $cvv, $postal_code);
$stmt->execute();
$stmt->close();


// 1. حساب إجمالي السلة
$total_price = 0;
$cart_items = $conn->query("SELECT cart.*, products.price FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = $user_id");
while ($item = $cart_items->fetch_assoc()) {
    $total_price += $item['price'] * $item['quantity'];
}

// 2. إنشاء الطلب
$stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$stmt_order->bind_param("id", $user_id, $total_price);
$stmt_order->execute();
$order_id = $stmt_order->insert_id;
$stmt_order->close();

// 3. إضافة العناصر في order_items
$cart_items->data_seek(0);
while ($item = $cart_items->fetch_assoc()) {
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt_item->execute();
    $stmt_item->close();
}

// 4. تفريغ السلة
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <link rel="stylesheet" href="layout/css/payment_confirmation.css">
</head>

<body>




    <div class="confirmation-box">
        <h2>Payment Successful!</h2>
        <p>Thank you <strong><?= htmlspecialchars($first_name . ' ' . $last_name) ?></strong>, your order has been
            confirmed.</p>
        <p class="total">Total: <strong><?= number_format($total_price, 2) ?>EGP</strong></p>

        <div class="button-group">
            <a href="index.php" class="btn">Continue Shopping</a>
            <a href="my_orders.php" class="btn">View My Orders</a>
        </div>
    </div>

</body>

</html>

<?php $conn->close(); ?>