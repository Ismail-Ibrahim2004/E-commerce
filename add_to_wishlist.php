<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);

// هل المنتج موجود فعلاً في الـ wishlist؟
$stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // موجود؟ نحذفه
    $delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete->bind_param("ii", $user_id, $product_id);
    $delete->execute();
    $delete->close();
} else {
    // مش موجود؟ نضيفه
    $insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $product_id);
    $insert->execute();
    $insert->close();
}

$stmt->close();
$conn->close();

header("Location: " . $_SERVER['HTTP_REFERER']); // يرجع لنفس الصفحة بعد التنفيذ
exit();
?>
