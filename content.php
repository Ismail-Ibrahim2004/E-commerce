<?php
include 'db.php';
// التحقق من وجود كلمة البحث
$searchTerm = "";
$resultsFound = false;

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = $_GET['search'];

    // استخدام استعلام مستعد لمنع حقن SQL
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $likeSearchTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $likeSearchTerm, $likeSearchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultsFound = true;
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>search of product</title>
    <link rel="stylesheet" href="layout/css/content.css">
    <link rel="shortcut icon" href="layout/img/home_img/Asset 2.png" type="image/x-icon" />
</head>
<style>
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
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.add-to-cart:hover {
    background-color: #45a049;
}
</style>

<body>
    <h2>
        <?php 
        if ($resultsFound) {
            echo 'Search results for: "' . htmlspecialchars($searchTerm) . '"';
        } else {
            echo "Please enter a word for search.";
        }
        ?>
    </h2>

    <div class="product-container">
        <?php
        if ($resultsFound) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    
                    // عرض الصورة
                    if (!empty($row['image_url'])) {
                        echo '<img src="http://localhost/Tasweek-E-commerce--main/img/content_img/' . urlencode($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="product-image">';
                    } else {
                        echo '<img src="default_image.jpg" alt="No image" class="product-image">';
                    }
                    
                    // عرض الاسم والوصف والسعر
                    echo '<h4 class="product-name">' . htmlspecialchars($row['name']) . '</h4>';
                    echo '<p class="product-description">' . htmlspecialchars($row['description']) . '</p>';
                    echo '<p class="product-price">price: $' . htmlspecialchars($row['price']) . '</p>';
                    echo '<button class="add-to-cart">Add to Cart</button>';
                    echo '</div>';
                }
            } else {
                echo "<p>There are no matching results.</p>";
            }
        }
        ?>
    </div>
</body>
<script>
<?php if ($resultsFound && isset($result) && $result->num_rows == 0): ?>
window.noResults = true;
<?php else: ?>
window.noResults = false;
<?php endif;?>
</script>

</html>