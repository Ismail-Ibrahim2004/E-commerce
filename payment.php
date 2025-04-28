<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="layout/css/payment_page.css">
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
    <link rel="stylesheet" href="layout/css/home_css/home_pagee.css">
    <link rel="stylesheet" href="layout/css/footer.css">
    <link rel="stylesheet" href="layout/css/slide_cart.css">
    <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="layout/js/slide_cart.js" defer></script>
</head>
<body>
    <!-- Navigator Section -->
    <header>
      <?php include 'includes/templates/header.php'; ?>
    </header>

    <div class="payment-container">
        <h1 class="title">payment method</h1>
        <h2 class="subtitle">choose a payment option fill in requested information</h2>
        <div class="form-container">
            <!-- Left Section -->
            <div class="payment-form">
                <div class="form-header">
                    <h3>payment option</h3>
                    <span class="secure-server">Secure server</span>
                </div>
                <div class="payment-method">
                    <input type="radio" id="credit" name="payment" checked>
                    <label for="credit">Credit / debit card</label>
                    <p class="description">Secure transfer using your Bank account</p>
                </div>

                <!-- ✅ Modified form -->
                <form class="details-form" method="POST" action="payment_confirmation.php">
                    <div class="form-group">
                        <input type="text" name="first_name" placeholder="Name" required>
                        <input type="text" name="last_name" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                        <input type="number" name="card_number" placeholder="Card number" required>
                        <input type="text" name="expiration" placeholder="Expiration" required>
                        <input type="text" name="cvv" placeholder="CVV" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="postal_code" placeholder="Postal code" required>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <button class="done-button" type="submit">Done</button>
                </form>
            </div>

            <!-- Right Section -->
            <div class="payment-illustration">
                <img src="layout/img/payment_img/phone-illustration.png" alt="Payment Illustration">
            </div>
        </div>
    </div>
    
    <!-- Slide Cart -->
    

    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="footer-col">
            <h4>company</h4>
            <ul>
              <li><a href="">about us</a></li>
              <li><a href="">our services</a></li>
              <li><a href="">privacy policy</a></li>
              <li><a href="">affiliate program</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>get help</h4>
            <ul>
              <li><a href="">FAQ</a></li>
              <li><a href="">shipping</a></li>
              <li><a href="">returns</a></li>
              <li><a href="">order status</a></li>
              <li><a href="">payment options</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>online shop</h4>
            <ul>
              <li><a href="#">watch</a></li>
              <li><a href="#">bag</a></li>
              <li><a href="#">shoes</a></li>
              <li><a href="#">dress</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>follow us</h4>
            <div class="social-links">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
          </div>
        </div>
      </div>
    </footer>
</body>
</html>