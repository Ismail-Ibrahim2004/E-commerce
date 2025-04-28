<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.*, products.name, products.image, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="layout/css/cart.css">
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        margin-top: 30px;
        color: #333;
    }

    table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    th,
    td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #4CAF50;
        color: white;
        font-size: 18px;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
    a {
        text-decoration: none;
    }

    img {
        width: 80px;
        height: auto;
        border-radius: 5px;
    }

    .btn {
        padding: 8px 16px;
        background-color: #f44336;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn:hover {
        background-color: #d32f2f;
    }

    .checkout-btn {
        display: block;
        width: fit-content;
        margin: 20px auto;
        padding: 12px 24px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .checkout-btn:hover {
        background-color: #45a049;
    }

    .total {
        text-align: center;
        font-size: 24px;
        margin-top: 20px;
        color: #333;
    }
    </style>

</head>

<body>
    <h1 style="text-align:center">Your Shopping Cart</h1>

    <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="10" cellspacing="0" style="margin:auto; width:90%;">
        <thead style="background-color:#f0f0f0;">
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr style="text-align:center;">
                <td><img src="includes/templates/Admin/uploads image/<?= htmlspecialchars($row['image']) ?>" width="60">
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 2) ?> EGP</td>
                <td>
                    <form action="update_cart.php" method="post" style="display:inline;">
                        <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="decrease">
                        <button type="submit">-</button>
                    </form>
                    <?= $row['quantity'] ?>
                    <form action="update_cart.php" method="post" style="display:inline;">
                        <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="increase">
                        <button type="submit">+</button>
                    </form>
                </td>
                <td><?= number_format($row['price'] * $row['quantity'], 2) ?> EGP</td>
                <td>
                    <form action="remove_from_cart.php" method="post">
                        <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                        <button class="btn">Remove</button>
                    </form>
                </td>
            </tr>
            <?php $total += $row['price'] * $row['quantity']; ?>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2 style="text-align:center; margin-top: 20px;">Total: <?= number_format($total, 2) ?> EGP</h2>
    <div style="text-align:center; margin-top: 20px;">
        <a href="payment.php"><button class="checkout-btn">Proceed to Checkout</button>
        </a>
    </div>

    <?php else: ?>
    <p style="text-align:center;">Your cart is empty.</p>
    <?php endif; ?>
</body>

</html>

<?php $conn->close(); ?>