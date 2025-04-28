<?php
session_start();
include '../../../db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Order ID is missing.";
    exit();
}

$order_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];

    $allowed_statuses = ['pending', 'processing', 'shipped', 'delivered', 'canceled'];

    if (!in_array($new_status, $allowed_statuses)) {
        echo "Invalid status.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        header("Location: admin_view_orders.php");
        exit();
    } else {
        echo "Failed to update order status.";
    }

    $stmt->close();
}

$stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->bind_result($current_status);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Order Status</title>
  <link rel="stylesheet" href="../../../layout/css/edit_order_status.css">
  <link rel="shortcut icon" href="../../../layout/img/home_img/Asset 2.png" type="image/x-icon" />
</head>
<body>

<button class="toggle-dark" onclick="toggleDarkMode()">Dark Mode</button>

<div class="container">
  <h1>Edit Order #<?= $order_id ?> Status</h1>

  <form method="post" >
    <label for="status">Select New Status:</label>
    <select id="status" name="status">
      <option value="pending" selected <?= $current_status == 'pending' ? 'selected' : '' ?>>Pending</option>
      <option value="processing"  <?= $current_status == 'processing' ? 'selected' : '' ?>>Processing</option>
      <option value="shipped" <?= $current_status == 'shipped' ? 'selected' : '' ?>>Shipped</option>
      <option value="delivered" <?= $current_status == 'delivered' ? 'selected' : '' ?>>Delivered</option>
      <option value="canceled" <?= $current_status == 'canceled' ? 'selected' : '' ?>>Canceled</option>
    </select>
    <button type="submit">Update Status</button>
  </form>

  <a href="admin_view_orders.php" class="back-link">← Back to Orders</a>
</div>

<script>
function toggleDarkMode() {
  document.body.classList.toggle('dark');
}
</script>

</body>
</html>

<?php $conn->close(); ?>
