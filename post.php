<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "blog");
if ($conn->connect_error) {
    die("Database connection failed");
}

$success = "";

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $conn->query("INSERT INTO posts (title, content) VALUES ('$title','$content')");
    $success = "Post added successfully";
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM posts WHERE id=$id");
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $conn->query("UPDATE posts SET title='$title', content='$content' WHERE id=$id");
}

$editPost = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editPost = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
}

$search = "";
$showPosts = false;
$sql = "";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = $_GET['search'];
    $showPosts = true;
    $sql = "SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Posts</title>
    <link rel="stylesheet" href="css/post.css?v=11">
</head>
<body>

<?php if ($success) { ?>
<script>
    alert("<?php echo $success; ?>");
</script>
<?php } ?>

<div class="navbar">
    <div class="nav-left">
        <h2>BlogApp</h2>
    </div>

    <div class="nav-center">
        <form method="get" class="nav-search">
            <input type="text" name="search" placeholder="Search post by title..." value="<?php echo $search; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="nav-right">
        <span>Welcome, <?php echo $_SESSION['user']; ?></span>
        <a href="index.php?logout=1" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">

    <h2><?php echo $editPost ? "Edit Post" : "Add New Post"; ?></h2>

    <form method="post">
        <?php if ($editPost) { ?>
            <input type="hidden" name="id" value="<?php echo $editPost['id']; ?>">
        <?php } ?>

        <input type="text" name="title" placeholder="Post Title"
               value="<?php echo $editPost['title'] ?? ''; ?>" required>

        <textarea name="content" placeholder="Post Content" required><?php
            echo $editPost['content'] ?? '';
        ?></textarea>

        <button name="<?php echo $editPost ? 'update' : 'add'; ?>">
            <?php echo $editPost ? 'Update Post' : 'Add Post'; ?>
        </button>
    </form>

</div>

<?php if ($showPosts) { ?>
<div class="search-section">
    <h2>Search Results</h2>

    <div class="post-row">
        <?php
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            echo "<p>No posts found</p>";
        }

        while ($row = $result->fetch_assoc()) {
        ?>
            <div class="post">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['content']; ?></p>

                <div class="actions">
                    <a href="?edit=<?php echo $row['id']; ?>">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>"
                       onclick="return confirm('Delete this post?')">Delete</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<footer class="footer">
    © 2026 BlogApp. Harshvardhan Patil.
</footer>
</body>
</html>
