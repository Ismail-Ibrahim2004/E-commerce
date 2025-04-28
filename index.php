<?php require_once 'includes/templates/header.php'; ?>
<?php


// لو المستخدم مش مسجل دخوله
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Home_page</title>
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <link rel="stylesheet" href="layout/css/home_css/home_pagee.css">
    <link rel="stylesheet" href="layout/css/footer.css">
    <link rel="stylesheet" href="layout/css/slide_cart.css">

    <link rel="stylesheet" type="text/css"href="layout/css/home_css/all.min.css">
    <script src="layout/js/slide_cart.js" defer></script>
  </head>

  <body>

    
    <div class="top-img">
      <section class="content">
        <p class="lifestyle">Trade-in-offer</p>
        <p class="sup">Super Value deals</p>
        <p class="sale">On all products</p>
        <p class="free-shipping">
          save more with coupons &<span>up to 70% off!</span>
        </p>  
        <button class="btn">
          <a id="transform-btn" href="product.php">
          Shop Now  
          <img  class="imgs" src="layout/img/home_img/Ecommerce campaign-cuate 1.png"  alt="photo">
          </a>
        </button>
      </section>
    </div>
    <!-- Last Line in Content Section -->

    <!-- Slide Cart -->
  

<?php require_once 'includes/templates/footer.php'; ?>
