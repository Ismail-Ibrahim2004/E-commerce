<?php
session_start();
include '../../../db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// البحث
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC");
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Users</title>
  <link rel="shortcut icon" href="../../../layout/img/home_img/Asset 2.png" type="image/x-icon" />

  <link rel="stylesheet" href="../../../layout/css/dashboard.css">
</head>
<body>

<h1 style="text-align:center;">All Registered Users</h1>

<!-- نموذج البحث -->
 <div class="search-form-container">
<form method="GET">
  <div class="search-form">
  <input class="search" type="text" name="search" placeholder="Search by name or email..." 
         value="<?= htmlspecialchars($search) ?>" style="padding: 7px; width: 300px;">
  <button class="search-button" type="submit">Search</button>
  </div>
</form>
</div>

<table border="1" cellpadding="10" cellspacing="0" style="margin:auto; width: 95%; background-color: #fff;">
  <thead style="background-color: #333; color: white;">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Registered At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr style="text-align:center;">
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo $row['role']; ?></td>
        <td><?php echo $row['created_at']; ?></td>
        <td style="text-align:center;">
          <?php if ($row['role'] !== 'admin'): ?>
            <a class="edit" href="toggle_role.php?id=<?php echo $row['id']; ?>">Make Admin</a> |
          <?php else: ?>
            <a class="view" href="toggle_role.php?id=<?php echo $row['id']; ?>">Make Customer</a> |
          <?php endif; ?>
          <a class="delete" href="delete_users.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6" style="text-align:center;">No users found.</td></tr>
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
