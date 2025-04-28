<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT orders.id, orders.total_price, orders.status, orders.created_at
        FROM orders
        WHERE orders.user_id = ?
        ORDER BY orders.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
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

<h1 style="text-align:center;">My Orders</h1>

<?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="10" cellspacing="0" style="margin:auto; width:90%; background-color:#fff;">
        <thead style="background-color:#333; color:white;">
            <tr>
                <th>Order ID</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Date</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr style="text-align:center;">
                <td><?= $row['id'] ?></td>
                <td><?= number_format($row['total_price'], 2) ?> EGP</td>
                <td><?= ucfirst($row['status']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><a class="view-link" href="order_details.php?id=<?= $row['id'] ?>">View</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center;">You have no orders yet.</p>
<?php endif; ?>

<br>
<div style="text-align:center;">
    <a href="index.php" class="back-link">⬅ Back to Home</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
