<?php
include 'db.php';
require_once 'includes/templates/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "
SELECT products.* FROM wishlist
JOIN products ON wishlist.product_id = products.id
WHERE wishlist.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <link rel="stylesheet" href="layout/css/home_css/home_pagee.css">
    <link rel="stylesheet" href="layout/css/footer.css">
    <link rel="stylesheet" href="layout/css/slide_cart.css">

    <link rel="stylesheet" type="text/css" href="layout/css/home_css/all.min.css">
    <script src="layout/js/slide_cart.js" defer></script>
    <style>
        body {
  padding-top: 65px;
  background-color: #f9f9f4;
}
        h1 {
  font-weight: bold;
  margin-top: 20px;
  margin-left: 100px;
  color: #000000;
  font-family: "ABeeZee", sans-serif;
  font-size: 24px;
  font-weight: 400;
  line-height: 28.37px;
  text-align: center;
  margin-bottom: 20px;
}
.product-container {
    display: flex;
    flex-wrap: wrap;
    gap: 50px;
    justify-content: center;
}

.product-card {
    border: 1px solid #ddd;
    padding: 15px;
    width: 270px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.product-image {
    width: 10%;
    height: 180px;
    object-fit: contain;
    border-radius: 8px;
}

.product-name {
    font-size: 16px;
    margin: 10px 0 5px;
    color: #333;
}

.product-description {
    font-size: 14px;
    color: #555;
}

.product-price {
    font-size: 15px;
    color: #92e3a9;
    font-weight: bold;
    margin: 8px 0;
}

.add-to-cart {
    background-color: #6d977d;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.add-to-cart:hover {
    background-color:rgb(213, 45, 16);
}
</style>

<body>

    <h1>Your Wishlist</h1>
    <div class="page-container">

        <?php if ($result->num_rows > 0): ?>
        <div class="product-container">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
            <img src="includes/templates/Admin/uploads image/<?= htmlspecialchars($row['image']) ?>" width="100">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= number_format($row['price'], 2) ?> EGP</p>
                <form action="remove_from_wishlist.php" method="post">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="add-to-cart">Remove</button>
                </form>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p>Your wishlist is empty.</p>
        <?php endif; ?>
    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>