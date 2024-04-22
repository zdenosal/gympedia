<?php
    session_start();

    include('db_conn.php');

    $sql = "SELECT id_a, content, title, author, created_at FROM articles WHERE order_a = 'food' ORDER BY created_at DESC";

    $result = mysqli_query($conn, $sql);
    $articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

    <?php include('header.php'); ?>

    <section class="s_articles">
        <?php foreach($articles as $article){ ?>
            <div class="article">
                <h1 class="a_name"><?php echo htmlspecialchars($article['title']); ?></h1>
                <em style = "font-size: 0.8em;"><?php echo htmlspecialchars($article['created_at']); ?></em>
                <p class="a_text">
                    <?php 
                        $content = explode('.', $article['content']);
                        for($i = 0; $i<4; $i++){
                            $content = explode('.', $article['content']);
                            echo $content[$i] . '.';
                        }
                        echo '...';
                    ?>
                </p>
                <a href="article.php?id_a=<?php echo $article['id_a']; ?>"><button class="switch">Show more</button></a>
            </div>
        <?php } ?>
    </section>


    <?php include('footer.php'); ?>
    
</html>