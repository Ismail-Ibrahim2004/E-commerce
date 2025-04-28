<?php

include 'db.php';

// جلب المنتجات
$sql = "SELECT id, name, description, price, image FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <link rel="stylesheet" href="layout/css/home_css/home_pagee.css">
    <link rel="stylesheet" href="layout/css/footer.css">
    <link rel="stylesheet" href="layout/css/slide_cart.css">
    <link rel="stylesheet" href="layout/css/content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="layout/js/slide_cart.js" defer></script>
    <style>
        .wishlist-and-cart {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .wishlist-btn:hover {
            cursor: pointer;
            transform: scale(1.1);
        }
    </style>
</head>

<body>

<?php require_once 'includes/templates/header.php'; ?>

<h1>All Products</h1>

<div class="page-container">
    <div class="product-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="includes/templates/Admin/uploads image/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                <h4><?= htmlspecialchars($row['name']); ?></h4>
                <p><?= htmlspecialchars($row['description']); ?></p>
                <div class="rating">⭐⭐⭐⭐ (100+ reviews)</div>
                <div class="discount">-10%</div>
                <div>
                    <span class="price"><?= number_format($row['price'], 2); ?> EGP</span>
                    <span class="old-price"><?= number_format($row['price'] * 1.2, 2); ?> EGP</span>
                </div>
                <div class="delivery">Get it as soon as <strong>Sunday, April 28</strong></div>
                <p class="Fulfilled">Fulfilled by E-commerce website - FREE Shipping</p>

                <?php
                $is_fav = false;
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $product_id = $row['id'];
                    $check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
                    $check->bind_param("ii", $user_id, $product_id);
                    $check->execute();
                    $check->store_result();
                    $is_fav = $check->num_rows > 0;
                    $check->close();
                }
                ?>
                
                <div class="wishlist-and-cart">
                    <form action="add_to_wishlist.php" method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                        <button type="submit" class="wishlist-btn" style="background: none; border: none; font-size: 20px;">
                            <?= $is_fav ? '💚' : '🤍' ?>
                        </button>
                    </form>

                    <button class="add-to-cart-btn" data-id="<?= $row['id']; ?>">Add To Cart</button>
                </div>

            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Side Cart -->
<div class="sidecart" id="sidecart">
    <div class="cart_content">
        <div class="cart_header">
            <img src="layout/img/slide_cart_img/cart.svg" alt="" style="width: 30px;">
            <div class="header_title">
                <h2>Your Cart</h2>
                <span id="Items_num">0</span>
            </div>
            <span id="close_btn" class="close_btn">&times;</span>
        </div>
        <div class="cart_items"></div>
        <div class="cart_actions">
            <div class="subtotal">
                <p>SUBTOTAL:</p>
                <p>$ <span id="subtotal_price">0</span></p>
            </div>
            <button><a class="payment-link" href="payment.php">Checkout</a></button>
            <button><a class="payment-link" href="cart.php">View Cart</a></button>
        </div>
    </div>
</div>

<script>
document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
    btn.addEventListener("click", function () {
        const productId = this.dataset.id;

        fetch("add_to_cart.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "product_id=" + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetch("sidecart.php")
                .then(res => res.text())
                .then(html => {
                    document.getElementById("sidecart").innerHTML = html;
                    document.getElementById("sidecart").classList.add("sidecart-open");
                });
            } else {
                alert("Please login first.");
            }
        });
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
