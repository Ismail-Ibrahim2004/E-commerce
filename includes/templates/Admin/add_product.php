<?php
session_start();
include '../../../db.php';

// السماح فقط للإداريين
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

// عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $stock = intval($_POST["stock"]);
    
    // حفظ الصورة
    $imageName = $_FILES["image"]["name"];
    $imageTmp = $_FILES["image"]["tmp_name"];
    $imagePath = "uploads image/" . basename($imageName);

    if (move_uploaded_file($imageTmp, $imagePath)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $description, $price, $stock, $imageName);

        if ($stmt->execute()) {
            $message = "تم إضافة المنتج بنجاح!";
        } else {
            $message = "فشل في إضافة المنتج.";
        }

        $stmt->close();
    } else {
        $message = "حدث خطأ أثناء رفع الصورة.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Product</title>
  <link rel="stylesheet" href="../../../layout/css/add_product.css"> <!-- أنشئه أو خليه بسيط -->
  <link rel="icon" href="../../../layout/img/home_img/asset 2.png" type="image/svg+xml">

</head>
<body>



  <div class="add-product-container">
    <form class="add-product-form" method="post" enctype="multipart/form-data">
      <h2>Add New Product</h2>
      <?php if (!empty($message)) echo "<p style='color: green;'>$message</p>"; ?>

        <label class="label" for="name">Product Name :</label>
        <input class="input-field" type="text" id="name" name="name" required>

        <label class="label" for="description">Discription:</label>
        <textarea class="input-field" id="description" name="description" rows="4" required></textarea>

        <label class="label" for="price">Price (EGP):</label>
        <input class="input-field" type="number" id="price" name="price" required>

        <label class="label" for="stock">Stock:</label>
        <input class="input-field" type="number" id="stock" name="stock" required>

        <label class="label" for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit" class="submit-button">Add Product</button>
      </form>
    </div>
    <div class="back-button">
      <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

  

</body>
</html>



<?php $conn->close(); ?>
