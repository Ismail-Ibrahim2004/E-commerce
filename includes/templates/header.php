<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasweek</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" href="../../layout/img/home_img/Asset 2.png" type="image/svg+xml">
    <link rel="stylesheet" href="../../layout/css/footer.css">
    <link rel="stylesheet" href="../../layout/css/home_css/home_pagee.css">
    <link rel="stylesheet" href="../../layout/js/slide_cart.js">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" type="text/css"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <link href="../../layout/css/home_css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
    <nav>
        <div class="navbar-container">
          <div class="navbar-logo">
            <img src="../../layout/img/home_img/Asset 2.png" alt="logo">
          </div>
          <!-- Navigator Links -->
          <ul class="navbar-links"> 
            <li>
              <form id="searchForm" action="../../content.php" method="GET">
                <input class="search" type="search" name="search" id="searchInput" placeholder="search....." />
              </form>
            </li>
            
            
            
            
            <li><a href="../../index.php">Home</a></li>
            <li><a href="../../product.php">products</a></li>
            <li><a href="../../wishlist.php">wishlist</a></li>
            <li><a href="../../Services.php">Services</a></li>
            <li><a href="../../contact us.php">contact</a></li>
            <li><a href="../../about.php">About</a></li>
            <li>
              <!-- <a href="../../sidecart.php" id="cart_header">
                <img style="scale: 60%;" src="../../layout/img/home_img/🦆 icon _shopping cart_.png" alt="Shopping Cart">
              </a>
            </li> -->
            
            <?php if (isset($_SESSION["user_name"])): ?>
    <li style="display: flex; align-items: center; gap: 10px;">
        <strong style="color: white;">Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?></strong>
        <a href="../../handle_logout.php" title="Logout">
            <img src="../../layout/img/home_img/logout.png" alt="Logout" style="width: 25px;">
        </a>
    </li>
<?php else: ?>
    <li>
        <a href="../../register.php">
            <img id="profile_icon" src="../../layout/img/home_img/profile.svg" alt="Profile">
        </a>
    </li>
<?php endif; ?>

          </ul>
        </div>
      </nav>
      
    </header>
    