<?php
session_start();
include '../../../db.php';

// السماح فقط للإداري
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// التحقق من ID
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

// حذف المنتج
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // ممكن تضيف رسالة نجاح في Session لو حبيت
    header("Location: dashboard.php");
    exit();
} else {
    echo "فشل في حذف المنتج.";
}

$stmt->close();
$conn->close();
?>
