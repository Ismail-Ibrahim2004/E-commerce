<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// حساب إجمالي السلة
$total_price = 0;
$cart_items = $conn->query("SELECT cart.*, products.price FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = $user_id");
while ($item = $cart_items->fetch_assoc()) {
    $total_price += $item['price'] * $item['quantity'];
}

// إنشاء الطلب
$stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$stmt_order->bind_param("id", $user_id, $total_price);
$stmt_order->execute();
$order_id = $stmt_order->insert_id;
$stmt_order->close();

// إضافة عناصر الطلب
$cart_items->data_seek(0);
while ($item = $cart_items->fetch_assoc()) {
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt_item->execute();
    $stmt_item->close();
}

// حذف محتويات السلة بعد الطلب
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

// استقبال طريقة الدفع من الفورم
$payment_method = $_POST['payment_method'] ?? 'unknown';

// تسجيل الدفع في جدول payments
$payment_status = "Completed";  // أو "Pending" حسب نظامك
$stmt_payment = $conn->prepare("INSERT INTO payments (order_id, amount, payment_method, payment_status) VALUES (?, ?, ?, ?)");
$stmt_payment->bind_param("idss", $order_id, $total_price, $payment_method, $payment_status);
$stmt_payment->execute();
$stmt_payment->close();

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
        <p>Thank you, your order has been confirmed.</p>
        <p class="total">Total: <strong><?= number_format($total_price, 2) ?> EGP</strong></p>

        <div class="button-group">
            <a href="index.php" class="btn">Continue Shopping</a>
            <a href="my_orders.php" class="btn">View My Orders</a>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
