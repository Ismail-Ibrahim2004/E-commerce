<?php
session_start();
include '../../../db.php';

// تأكد إن المستخدم أدمن
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../../../login.php");
    exit();
}

// البحث
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM contact_messages 
        WHERE name LIKE ? OR email LIKE ? OR message LIKE ?
        ORDER BY created_at DESC");
    $likeSearch = "%$search%";
    $stmt->bind_param("sss", $likeSearch, $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Messages</title>
    <link rel="stylesheet" href="../../../layout/css/dashboard.css">
    <link rel="shortcut icon" href="../../../layout/img/home_img/Asset 2.png" type="image/x-icon" />

</head>

<body>

    <h1>Messages from Contact Page</h1>

    <!-- نموذج البحث -->
    <form method="GET">
        <div class="search-form">
            <input class="search" type="text" name="search" placeholder="Search by name, email or message..."
                value="<?= htmlspecialchars($search) ?>" style="padding: 7px; width: 300px;">
            <button class="search-button" type="submit">Search</button>
        </div>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 95%; margin: auto;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Sent At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">No messages found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br><br>
    <div style="text-align:center;">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

</body>

</html>

<?php $conn->close(); ?>