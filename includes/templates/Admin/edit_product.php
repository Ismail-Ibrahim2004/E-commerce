<?php
session_start();
include '../../../db.php';

// تأكد إن اللي داخل هو Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// تحقق من وجود ID في الرابط
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);
$message = "";

// جلب بيانات المنتج
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "المنتج غير موجود.";
    exit();
}

// التحديث عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $stock = intval($_POST["stock"]);

    // تحديث بدون تغيير الصورة
    if ($_FILES["image"]["error"] == 4) {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdii", $name, $description, $price, $stock, $id);
    } else {
        $imageName = $_FILES["image"]["name"];
        $imageTmp = $_FILES["image"]["tmp_name"];
        $imagePath = "uploads image/" . basename($imageName);
        move_uploaded_file($imageTmp, $imagePath);

        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?");
        $stmt->bind_param("ssdssi", $name, $description, $price, $stock, $imageName, $id);
    }

    if ($stmt->execute()) {
        $message = "تم تحديث المنتج بنجاح!";
    } else {
        $message = "حدث خطأ أثناء التحديث.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="icon" href="../../../layout/img/home_img/asset 2.png" type="image/svg+xml">
    <link rel="stylesheet" href="../../../layout/css/add_product.css">
</head>
<body>

<div class="add-product-container">
    <form class="add-product-form" method="post" enctype="multipart/form-data">
    <h2>Edit Product</h2>
    <?php if (!empty($message)) echo "<p style='color: green;'>$message</p>"; ?>
        
    <label class="label" for="name">Product Name:</label>
    <input class="input-field" type="text"  id="name" name="name" value="<?php echo $product['name']; ?>" required><br><br>

    <label class="label" for="description">Description:</label>
    <textarea class="input-field" id="description" name="description" rows="4" required><?php echo $product['description']; ?></textarea><br><br>

    <label class="label" for="price">Price (EGP):</label>
    <input class="input-field" type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required><br><br>

    <label class="label" for="stock">Stock:</label>
    <input class="input-field" type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required><br><br>

    <label class="label" for="image">Image:</label>
    <img src="uploads image/<?php echo htmlspecialchars($product['image']); ?>" width="120">
    <input type="file" id="image" name="image" accept="image/*">

    <button type="submit" class="submit-button">Update Product</button>
</form>
</div>
<div class="back-button">
  <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>
</body>
</html>

<?php $conn->close(); ?>
