<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo '<p style="text-align:center;">You must login first.</p>';
    return;
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

<!-- Cart Header -->
<div class="cart_header" id="cart_header">
  
  <img src="layout/img/slide_cart_img/cart.svg" alt="Cart" style="width: 30px;">
  <div class="header_title">
    <h2>Your Cart</h2>
    <span id="Items_num"><?= $result->num_rows ?></span>
  </div>
  <span id="close_btn" class="close_btn" onclick="document.getElementById('sidecart').classList.remove('sidecart-open')">&times;</span>
</div>

<!-- Cart Items -->
<div class="cart_items">
<?php if ($result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="cart_item">
      <div class="remove_item">
        <button class="remove-from-cart" data-id="<?= $row['id'] ?>" style="background:none; border:none; font-size:20px;">&times;</button>
      </div>

      <div class="item_img">
        <img src="includes/templates/Admin/uploads image/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="60">
      </div>

      <div class="item_details">
        <p><?= htmlspecialchars($row['name']) ?></p>
        <strong><?= number_format($row['price'], 2) ?> EGP</strong>
        <div class="qty">
          <button class="update-cart" data-id="<?= $row['id'] ?>" data-action="decrease" style="background:none; border:none;">-</button>
          <strong><?= $row['quantity'] ?></strong>
          <button class="update-cart" data-id="<?= $row['id'] ?>" data-action="increase" style="background:none; border:none;">+</button>
        </div>
      </div>
    </div>
    <?php $total += $row['price'] * $row['quantity']; ?>
  <?php endwhile; ?>
<?php else: ?>
  <p style="text-align:center;">Your cart is empty.</p>
<?php endif; ?>
</div>

<!-- Cart Actions -->
<?php if ($total > 0): ?>
<div class="cart_actions">
  <div class="subtotal">
    <p>SUBTOTAL:</p>
    <p><span id="subtotal_price"><?= number_format($total, 2) ?></span> EGP</p>
  </div>

  <a href="payment.php"><button>Checkout</button></a>
  <a href="cart.php"><button style="background-color: #d52d10;">View Cart</button></a>

</div>
<?php endif; ?>
