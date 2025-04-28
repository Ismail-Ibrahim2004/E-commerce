<?php
session_start();
include '../../../db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

$sql = "SELECT orders.id, orders.total_price, orders.status, orders.created_at, users.name as customer_name
        FROM orders
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Orders - Admin</title>
  <link rel="shortcut icon" href="../../../layout/img/home_img/Asset 2.png" type="image/x-icon" />

  <link rel="stylesheet" href="../../../layout/css/dashboard.css">
</head>
<body>

<h1 style="text-align:center;">All Customer Orders</h1>

<table border="1" cellpadding="10" cellspacing="0" style="margin:auto; width:95%; background-color:#fff;">
  <thead style="background-color:#333; color:white;">
    <tr>
      <th>Order ID</th>
      <th>Customer Name</th>
      <th>Total Price</th>
      <th>Status</th>
      <th>Order Date</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr style="text-align:center;">
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['customer_name']) ?></td>
      <td><?= number_format($row['total_price'], 2) ?> EGP</td>
      <td><?= ucfirst($row['status']) ?></td>
      <td><?= $row['created_at'] ?></td>
      <td style="text-align:center;">
        <a class="view" href="admin_order_details.php?id=<?= $row['id'] ?>">View</a>
        |
        <a class="change" href="edit_order_status.php?id=<?= $row['id'] ?>">Change Status</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<br><br>
<div style="text-align:center;">
  <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
