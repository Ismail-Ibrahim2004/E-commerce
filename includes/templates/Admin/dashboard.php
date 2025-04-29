<?php
session_start();
include '../../../db.php';

// التحقق من صلاحية المسؤول
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin';

// البحث
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC");
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="icon" href="../../../layout/img/home_img/asset 2.png" type="image/svg+xml">

    <link rel="stylesheet" href="../../../layout/css/dashboard.css">
    <link rel="stylesheet" href="../../../layout/css/home_css/all.min.css">
</head>

<body>

    <h1>Admin Dashboard</h1>
    <h3>Welcome, <?= htmlspecialchars($admin_name) ?></h3>

    <div class="nav-item">
        <a href="../../../handle_logout.php">Logout</a>
    </div>

    <div class="tabs">
        <div class="tab"><a href="view_users.php"><button class="active">View Users</button></a></div>
        <div class="tab"><a href="view_messages.php"><button class="active">User's complaint</button></a></div>
        <div class="tab"><a href="admin_view_orders.php"><button class="active">View Orders</button></a></div>
    </div>
    
    
    <div class="tab"><a href="add_product.php"><button class="active">Add New Product</button></a></div>
    <!-- نموذج البحث -->
    <form method="GET">
        <div class="search-form">
            <input class="search" type="text" name="search" placeholder="Search by name or description..."
            value="<?= htmlspecialchars($search) ?>" style="padding: 7px; width: 300px;">
            <button class="search-button" type="submit">Search</button>
        </div>
    </form>
    <div class="table-container">


    <table border="1" cellpadding="10" cellspacing="0" style="width: 95%; margin: auto;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><img src="uploads image/<?php echo $row['image']; ?>" width="60"></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo number_format($row['price'], 2); ?> EGP</td>
                <td><?php echo $row['stock']; ?></td>
                <td>
                    <a class="edit" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a class="delete" href="delete_product.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">No products found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

</body>

</html>

<?php $conn->close(); ?>