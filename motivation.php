<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

    <?php include('header.php'); ?>


    <div class="motivation-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <label>Show only: </label>
            <select name="filter">
                <option value="all">All</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
                <option value="quote">Quote</option>
            </select>
            <button type="submit" name="apply_filter">Apply Filter</button>
        </form>

        <?php
        include('db_conn.php');

        $sql = "SELECT * FROM motivation_files ORDER BY created_at DESC";

        if (isset($_GET['apply_filter'])) {
            $filter = $_GET['filter'];
            if ($filter !== 'all') {
                $sql = "SELECT * FROM motivation_files WHERE type = '$filter' ORDER BY created_at DESC";
            }
        }

        $result = mysqli_query($conn, $sql); ?>
        <div class = "container">
        <?php if ($result) {
            while ($row = mysqli_fetch_assoc($result)) { ?>

                <?php if ($row['type'] === 'video' && !empty($row['url'])) { ?>
                    <div class="center"><iframe width="560" height="315" src="<?php echo $row['url']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>
                <?php } elseif ($row['type'] === 'audio' && !empty($row['file'])) { ?>
                    <div class="center">
                        <audio controls>
                            <source src="<?php echo $row['file']; ?>" type="audio/mpeg">
                            <source src="<?php echo $row['file']; ?>" type="audio/wav">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                <?php } elseif ($row['type'] === 'quote' && !empty($row['txt'])) { ?>
                    <blockquote><?php echo $row['txt']; ?></blockquote>
                <?php } 
            } 
        } else {
            echo "Error: " . mysqli_error($conn);
        } 
        ?></div><?php
        mysqli_close($conn);
        ?>
    </div>

    
    <?php include('footer.php'); ?>
    
</html>