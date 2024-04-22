<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

    <?php include('header.php'); ?>

    <section class="swelcome">
        <img class="welcome_img" src="pictures/welcome_section.webp" alt="">
        <div class="position">
            <h1 class="s1text">Everything you need to know to achieve your dream physique.</h1>
            <h2 class="s1text">Knowledge is key.</h2>
            <button class="btn open-modal">Learn</button>
        </div>
        <dialog class="modal">
            <h1>Learn</h1>
            <p>We have lot of useful information about fitness. Good exercises and how to perform them, in what foods are best proteins or how to diet and lot of other things. So do you want to know?</p>
            <div class="btn_group">
                <a href="food.php"><button class="reglog_btn">Food</button></a>
                <a href="training.php"><button class="reglog_btn">Training</button></a>
                <a href="supplements.php"><button class="reglog_btn">Supplements</button></a>
            </div>
            <i class="gg-close close-modal"></i>
        </dialog>
       
    </section>

    <section class="section_links">
        <div class="comunity">
            <h1>Our comunity</h1>
            <p>If you want to know if some supplement or exercise is good, or which 
                is the best, or if you have some experiences about food, supplements, training etc.
                 you can write or read our forum there's a lot of information from our community and
                  you can have your say in it.</p>
            <a href="forum.php"><button class="darkbtn">Check it</button></a>
        </div>

        <div class="motivation_home">
            <h1>Motivation</h1>
            <p>If you ever feel tired or lazy do not submit to that feeling. Here is a collection of 
                motivational quotes, videos that can help you get motivated and get things done!
            </p>
            <a href="motivation.php"><button class="darkbtn">Read</button></a>
        </div>
       

    </section>

    <?php include('footer.php'); ?>

</html>