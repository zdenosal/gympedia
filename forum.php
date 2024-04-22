<?php
    include('db_conn.php');
    session_start();

    $error = array('title'=>'', 'content'=>'', 'log'=>'');

    if(isset($_POST['subForm'])){
        if(isset($_SESSION['email'])){
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $content = mysqli_real_escape_string($conn, $_POST['content']);
            $theme = mysqli_real_escape_string($conn, $_POST['theme']);
            $username = explode('@', $_SESSION['email']);
            $author = ucfirst($username[0]);
            
            $sqlIns = "INSERT INTO posts(title,content,theme,author) VALUES ('$title', '$content', '$theme', '$author')";

            if(empty($title)){
                $error['title'] = "You need to write a title.";
            }
            if(empty($content)){
                $error['content'] = "You need to write some content.";
            }else{
                mysqli_query($conn, $sqlIns);
                header("Location: forum.php");
                exit();
            }
        } else {
            $error['log'] = "You need to be logged in to post a comment.";
        }   
    }


    $sql = "SELECT * FROM posts ORDER BY created_at DESC";
    if (isset($_GET['apply_filter'])) {
        $filter = $_GET['filter'];
        if ($filter !== 'all') {
            $sql = "SELECT * FROM posts WHERE theme = '$filter' ORDER BY created_at DESC";
        }
    }
    $result = mysqli_query($conn, $sql);
    $themes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

    <?php include('header.php'); ?>

    <div class="motivation-content">
        <div class="inline">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                <label>Show only: </label>
                <select name="filter">
                    <option value="all">All</option>
                    <option value="food">Food</option>
                    <option value="supplements">Supplements</option>
                    <option value="training">Training</option>
                </select>
                <button type="submit" name="apply_filter">Apply Filter</button>
            </form>

            <button class="show_theme">Add post</button>
        </div>
        
        <dialog class="add_theme">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="text" name="title" placeholder="Write a title">
                <div class="error"><?php echo $error['title']; ?></div>
                <textarea class = "theme_content" name="content" cols="30" rows="10" placeholder="Write content"></textarea>
                <div class="error"><?php echo $error['content']; ?></div>
                <select name="theme">
                    <option value="food">Food</option>
                    <option value="supplements">Supplements</option>
                    <option value="training">Training</option>
                </select>
                <button type="submit" name = "subForm">Add</button>
                <button class="cancel">Cancel</button>
                <div class="error"><?php echo $error['log']; ?></div>
            </form>
        </dialog>

        <div class="container">
        
            <?php foreach($themes as $theme) { ?>
                <div class="article">
                    <h1 class="a_name"><?php echo htmlspecialchars($theme['title']); ?></h1>
                    <em style = "font-size: 0.8em;"><?php echo htmlspecialchars($theme['created_at']); ?></em>
                    <p class="a_text">
                        <?php 
                            $content = explode('.', $theme['content']);
                            $numSentences = count($content);
                            $numSentencesToDisplay = min($numSentences, 4);
                        
                            for ($i = 0; $i < $numSentencesToDisplay; $i++) {
                                echo $content[$i] . '.';
                            }
                        
                            if ($numSentences > 4) {
                                echo '...';
                            }
                        ?>
                    </p>
                    <a href="post.php?id=<?php echo $theme['id']; ?>"><button class="switch">Show post</button></a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include('footer.php'); ?>
    

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const showTheme = document.querySelector('.show_theme');
            const themeModal = document.querySelector('.add_theme');
            showTheme.addEventListener('click', () => {
                themeModal.showModal();
            });
        });
        window.addEventListener('DOMContentLoaded', (event) => {
            const cancelModal = document.querySelector('.cancel');
            cancelModal.addEventListener('click', () => {
                themeModal.close();
            });
        });
        window.addEventListener('DOMContentLoaded', (event) => {
            <?php if (!empty($error['title']) || !empty($error['content']) || !empty($error['log'])) { ?>
                document.querySelector('.add_theme').showModal();
            <?php } ?>
        });
    </script>
</html>