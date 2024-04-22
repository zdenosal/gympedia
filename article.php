<?php
    session_start();

    include('db_conn.php');

    if(isset($_GET['id_a'])){
        $id_a = mysqli_real_escape_string($conn, $_GET['id_a']);
        $sql = "SELECT * FROM articles WHERE id_a = '$id_a'";
        $result = mysqli_query($conn, $sql);
        $article = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>
<!DOCTYPE html>
<html lang="en">

    <?php include('header.php'); ?>
    
    <div class="s_articles">
        <div class="article">
            <?php if($article): ?>
                <h1 class="a_title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <em><?php echo date($article['created_at']); ?></em>
                <p class="content"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                <b><?php echo htmlspecialchars($article['author']); ?></b>
            <?php else: ?>
                <h1 class="title">This article doesn't exist or was deleted.</h1>
            <?php endif; ?>
        </div>
    </div>

    <?php include('footer.php'); ?>
    
</html>