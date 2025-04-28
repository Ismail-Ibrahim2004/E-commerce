<?php
session_start();
include '../../../db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../../../../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // جلب الدور الحالي
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($currentRole);
    $stmt->fetch();
    $stmt->close();

    // عكس الدور
    $newRole = ($currentRole === 'admin') ? 'customer' : 'admin';

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: view_users.php");
exit();
?>
