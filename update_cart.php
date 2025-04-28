<?php
session_start();
include 'db.php';

// عشان ترجع JSON
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_id']) && isset($_POST['action'])) {
    $cart_id = intval($_POST['cart_id']);
    $action = $_POST['action'];

    if ($action === "increase") {
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = ?";
    } elseif ($action === "decrease") {
        $sql = "UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND quantity > 1";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit();
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
