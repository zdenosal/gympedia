<?php
    session_start();

    include('db_conn.php');

    $error = array('log'=>'', 'id'=>'');

    if(isset($_GET['id'])){
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = "SELECT * FROM posts WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $theme = mysqli_fetch_assoc($result);
        
    
        if(isset($_POST['post'])){
            if(isset($_SESSION['email'])){
                $content = mysqli_real_escape_string($conn, $_POST['content']);
                $username = explode('@', $_SESSION['email']);
                $author = ucfirst($username[0]);
                $sql = "INSERT INTO comments (post_id, author, content) VALUES ('$id', '$author', '$content')";
                if(mysqli_query($conn, $sql)){
                    header("Location: post.php?id=" . $id);
                    exit();
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                $error['log'] = "You need to be logged in to post a comment.";
            }
        }
        mysqli_free_result($result);
        mysqli_close($conn);
    } else {
        $error['id'] = "No post ID specified.";
    }
    
?>
<!DOCTYPE html>
<html lang="en">

    <?php include('header.php'); ?>

    <div class="s_articles">
        <div class="container width">
            <?php if($theme): ?>
                <h1 class="p_title"><?php echo htmlspecialchars($theme['title']); ?></h1>
                <em><?php echo date($theme['created_at']); ?></em>
                <p class="content"><?php echo nl2br(htmlspecialchars($theme['content'])); ?></p>
                <b><?php echo htmlspecialchars($theme['author']); ?></b>
            <?php else: ?>
                <h1 class="title">This post doesn't exist or was deleted.</h1>
            <?php endif; ?>
        </div>
        <div class="form width">
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id; ?>" method="POST">
                <textarea class = "theme_content" name="content" cols="82" rows="5" placeholder="Write content" required></textarea>
                <button class="reglog_btn" type="submit" name="post">Post</button>
                <div class="error"><?php echo $error['log']; ?></div>
            </form>
        </div>
        <div class="gap">
            <?php
                $sql = "SELECT * FROM comments WHERE post_id = '$id' ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                while($comment = mysqli_fetch_assoc($result)) {
                    echo "<div class='comment width'>";
                    echo "<h4>" . htmlspecialchars($comment['author']) . "</h4>";
                    echo "<p>" . htmlspecialchars($comment['content']) . "</p>";
                    echo "<em>" . date('H:i d-m-Y', strtotime($comment['created_at'])) . "</em>";
                    echo "</div>";
                }
                mysqli_free_result($result);
            ?>
        </div>
        
    </div>

    

    <?php include('footer.php'); ?>
    
</html>