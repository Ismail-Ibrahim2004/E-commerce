<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Order ID is missing.";
    exit();
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// تأكد إن الطلب خاص بالمستخدم الحالي
$checkOrder = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
$checkOrder->bind_param("ii", $order_id, $user_id);
$checkOrder->execute();
$checkOrder->store_result();

if ($checkOrder->num_rows == 0) {
    echo "You don't have permission to view this order.";
    exit();
}

$sql = "SELECT order_items.*, products.name, products.image
        FROM order_items
        JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Details</title>
  <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
  <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f0f4f8;
        padding: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 32px;
        color:rgb(123, 190, 117);
    }

    table {
        width: 90%;
        margin: 0 auto;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    th, td {
        padding: 15px;
        text-align: center;
        font-size: 16px;
    }

    th {
        background-color:rgb(96, 136, 95);
        color: white;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #dff0ff;
    }

    a.view-link {
        color:rgb(78, 151, 109);
        text-decoration: none;
        font-weight: bold;
    }

    a.view-link:hover {
        text-decoration: underline;
    }

    .back-link {
        /* display: block; */
        margin: 30px auto 0;
        text-align: center;
        font-size: 18px;
        text-decoration: none;
        color:rgb(34, 109, 19);
        border-radius: 15px;
    }

    .back-link:hover {
        text-decoration: underline;
        
    }
</style>
</head>
<body>

<h1 style="text-align:center;">Order #<?= $order_id ?> Details</h1>

<?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="10" cellspacing="0" style="margin:auto; width:90%;">
        <thead style="background-color:#333; color:white;">
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Price (Each)</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grand_total = 0;
            while ($row = $result->fetch_assoc()): 
                $subtotal = $row['price'] * $row['quantity'];
                $grand_total += $subtotal;
            ?>
            <tr style="text-align:center;">
                <td><img src="includes/templates/Admin/uploads image/<?= htmlspecialchars($row['image']) ?>" width="60"></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 2) ?> EGP</td>
                <td><?= $row['quantity'] ?></td>
                <td><?= number_format($subtotal, 2) ?> EGP</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2 style="text-align:center; margin-top:20px;">Grand Total: <?= number_format($grand_total, 2) ?> EGP</h2>

<?php else: ?>
    <p style="text-align:center;">No items found for this order.</p>
<?php endif; ?>

<br>
<div style="text-align:center;">
    <a class="view-link " href="my_orders.php">⬅ Back to My Orders</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
