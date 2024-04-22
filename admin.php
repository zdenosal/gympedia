<?php

    session_start(); 

    include('db_conn.php');

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['logOut'])) {
        unset($_SESSION['userLoggedIn']);
        unset($_SESSION['email']);
        session_destroy();
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['subArticles'])){
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $order_a = mysqli_real_escape_string($conn, $_POST['order_a']);

        $sql = "INSERT INTO articles(title,author,content,order_a) VALUES('$title', '$author', '$content', '$order_a')";

        mysqli_query($conn, $sql);

    }
    if (isset($_POST['subMotivation'])){
        $type = mysqli_real_escape_string($conn, $_POST['file_type']);
        $txt = mysqli_real_escape_string($conn, $_POST['txt']);
        $file = mysqli_real_escape_string($conn, $_POST['file']);
        $url = mysqli_real_escape_string($conn, $_POST['url']);


        $sql = "INSERT INTO motivation_files(type,txt,file,url) VALUES('$type', '$txt', '$file', '$url')";

        mysqli_query($conn, $sql);

    }

    $navPanel = 'Log Out';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin</title>
    <script src="reglog_nav_form.js" defer></script>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const logOut = document.querySelector('.logOut-form')
            joinUsButton.addEventListener('click', () => {
                logOut.showModal();
            });
            const cancel = document.querySelector('.cancel')
            cancel.addEventListener('click', () => {
                logOut.close();
            });
        });
        window.addEventListener('DOMContentLoaded', (event) => {
            const motivation = document.querySelector('.mot');
            const articles = document.querySelector('.art');
            const artContent = document.querySelector('.articles');
            const motContent = document.querySelector('.motivation');

            motContent.style.display = "none";

            motivation.addEventListener('click', () => {
                artContent.style.display = "none";
                motContent.style.display = "flex";
            });
            articles.addEventListener('click', () => {
                motContent.style.display = "none";
                artContent.style.display = "flex";
            });
        });
        window.addEventListener('DOMContentLoaded', (event) => {
            var fileType = document.querySelector('.file_type');
            var video = document.getElementById('video');
            var audio = document.getElementById('audio');
            var quote = document.getElementById('quote');
            
            video.style.display = "none";
            audio.style.display = "none";
            quote.style.display = "none";

            fileType.addEventListener('change', function() {
                var selectedFileType = this.value;
                video.style.display = "none";
                audio.style.display = "none";
                quote.style.display = "none";

                if (selectedFileType === 'video') {
                    video.style.display = "block";
                } else if (selectedFileType === 'audio') {
                    audio.style.display = "block";
                } else if (selectedFileType === 'quote') {
                    quote.style.display = "block";
                }
            });
        });
    </script>
</head>
<body>
    
    <nav>
        <header>GymPedia</header>
        <div class="nav">
            <a class="navlink art" href="#">Articles</a>
            <a class="navlink mot" href="#">Motivation Files</a>
        </div>
        <button class="navbutton open-registration-modal"><?php echo $navPanel; ?></button>
    </nav>

    <dialog class="logOut-form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <p>Are you sure you want to log out?</p>
            <button class="switch" name = "logOut">Yes</button>
            <button class="switch cancel">Cancel</button>
        </form>
    </dialog>

    <div class="articles">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="text" class = "title" name = "title" placeholder = "Title" required><br>
            <textarea name="content" cols="80" rows="30" placeholder = "Content" required></textarea><br>
            <label>Select article inclusion: </label>
            <select name="order_a">
                <option value="food">Food</option>
                <option value="supplements">Supplement</option>
                <option value="training">Training</option>
            </select>
            <input type="text" name = "author" placeholder = "Author"><br>
            <button type = "submit" name = "subArticles">Submit</button>
        </form>
    </div>

    <div class="motivation">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label style="font-size: 1.5em;">Select file type: </label>
            <select class="file_type" name="file_type">
                <option hidden disabled selected value> -- select an option -- </option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
                <option value="quote">Quote</option>
            </select>

            <input type="text" id="video" name = "url" placeholder="Paste a URL.">

            <input type="file" id="audio" name="file" multiple>

            <input type="text" id="quote" name = "txt" placeholder="Write a quote.">

            <button type = "submit" name = "subMotivation">Submit</button>
        </form>
    </div>



</body>
</html>