<?php
$conn = new mysqli("localhost", "root", "", "blog");
if ($conn->connect_error) {
    die("Database connection failed");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>All Blog Posts</title>
    <link rel="stylesheet" href="css/view_post.css?v=2">
</head>
<body>
<nav class="navbar">
    <div class="nav-left">
        <span class="logo">BlogApp</span>
    </div>

    <div class="nav-right">
        <a href="index.php" class="btn-post">Login</a>
    </div>
</nav>
<div class="container">
    <h2>All Blog Posts</h2>
    <?php
    $result = $conn->query("SELECT * FROM posts ORDER BY id DESC");

    if ($result->num_rows == 0) {
        echo "<p class='no-posts'>No posts available</p>";
    }

    while ($row = $result->fetch_assoc()) {
    ?>
        <div class="post-card">
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo nl2br($row['content']); ?></p>
        </div>
    <?php } ?>
</div>
<footer class="footer">
    © 2026 BlogApp. Harshvardhan Patil.
</footer>

</body>
</html>
