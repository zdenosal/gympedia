<?php 


    include('db_conn.php');

    $errorReg = '';
    $errorLog = array('email'=>'', 'password'=>'');
    
    if(isset($_POST['submitReg'])){

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $_SESSION['email'] = $email;

        $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
        $checkStmt = mysqli_stmt_init($conn);

        $sqlIns = "INSERT INTO users(email,password) VALUES('$email', '$hashedPassword')";

        if (mysqli_stmt_prepare($checkStmt, $checkEmailQuery)) {
            mysqli_stmt_bind_param($checkStmt, "s", $email);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                $errorReg = 'Email already exists.';
                mysqli_close($conn);
            }else{
                mysqli_query($conn, $sqlIns);
                $_SESSION['userLoggedIn'] = true;

            }

            mysqli_stmt_close($checkStmt);
        } else {
            echo 'Error preparing statement: ' . mysqli_error($conn);
            mysqli_close($conn);
            exit();
        }
    }

    if(isset($_POST['submitLog'])){

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $checkLoginQuery = "SELECT * FROM users WHERE email = ?";
        $checkStmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($checkStmt, $checkLoginQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $passwordCheck = password_verify($password, $row['password']);
            if ($passwordCheck) {
                $_SESSION['userLoggedIn'] = true;
                $_SESSION['email'] = $email;

                $sql = "SELECT role FROM users WHERE email = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $role);
                mysqli_stmt_fetch($stmt);

                $_SESSION['role'] = $role;

                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    header("Location: admin.php");
                }
            } else {
                $errorLog['password'] = 'Invalid password.';
            }
        } else {
            $errorLog['email'] = 'Invalid email or user not found.';
        }
    
        mysqli_stmt_close($checkStmt);
    
    }

    if (isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn'] === true) {
        $navPanel = 'Log out';
    } else {
        $navPanel = 'Join Us';
    }

    if (isset($_POST['logOut'])) {
        unset($_SESSION['userLoggedIn']);
        unset($_SESSION['email']);
        session_destroy();
        header("Location: index.php");
        exit();
    }

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <script src="reglog_nav_form.js" defer></script>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            <?php if (!empty($errorReg)) { ?>
                document.querySelector('.registration-form').showModal();
            <?php } ?>
        });
        window.addEventListener('DOMContentLoaded', (event) => {
            <?php if (!empty($errorLog['email']) || !empty($errorLog['password'])) { ?>
                document.querySelector('.login-form').showModal();
            <?php } ?>
        });
        window.addEventListener('DOMContentLoaded', (event) => {
            <?php if($navPanel == 'Log out') { ?>
                const logOut = document.querySelector('.logOut-form')
                joinUsButton.addEventListener('click', () => {
                    registrationForm.close();
                    logOut.showModal();
                });
            <?php } ?>
        });
        window.addEventListener('DOMContentLoaded', (event) => {
            const logOut = document.querySelector('.logOut-form')
            const cancel = document.querySelector('.cancel')
            cancel.addEventListener('click', () => {
                logOut.close();
            });
        });
    </script>

    <title>Gympedia</title>
</head>
<body>
    <nav>
        <header>GymPedia</header>
        <div class="nav">
            <a class="navlink" href="index.php">Home</a>
            <div class="dropdown">
                <a class="navlink dropdown" href="learn.php">Learn</a>
                <div class="dropdown-content">
                    <a href="food.php">Food</a>
                    <a href="training.php">Training</a>
                    <a href="supplements.php">Supplements</a>
                </div>
            </div>
            <a class="navlink" href="forum.php">Forum</a>
            <a class="navlink" href="motivation.php">Motivation</a>
        </div>
        <button class="navbutton open-registration-modal"><?php echo $navPanel; ?></button>
    </nav>

    <dialog class="registration-form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <h1 class="reglog-header">Register</h1>
            <div class="input-box">
                <label>Email</label>
                <input type="email" name="email" required>
                <div class="error"> <?php echo $errorReg; ?> </div>
            </div>
            <div class="input-box">
                <label>Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div class="input-box">
                <label>Repeat password</label>
                <input id="rptpassword" type="password" required>
            </div>
            <button type="submit" name="submitReg" class="reglog_btn">Register</button>
            <button class="close-registration-modal right reglog_btn">Close</button>
            <p>Already have an account? <button class="switch-to-login switch">Login</button></p>
        </form>
    </dialog>

    <dialog class="login-form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <h1 class="reglog-header">Login</h1>
            <div class="input-box">
                <label>Email</label>
                <input type="email" name = "email" required>
                <div class="error"><?php echo $errorLog['email']; ?></div>
            </div>
            <div class="input-box">
                <label>Password</label>
                <input type="password" name="password" required>
                <div class="error"><?php echo $errorLog['password']; ?></div>
            </div>
            <button type="submit" name="submitLog" class="reglog_btn">Login</button>
            <button class="close-login-modal right reglog_btn">Close</button>
            <p>Do not have an account? <button class="switch-to-registration switch">Register</button></p>
        </form>
    </dialog>

    <dialog class="logOut-form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <p>Are you sure you want to log out?</p>
            <button class="switch" name = "logOut">Yes</button>
            <button class="switch cancel">Cancel</button>
        </form>
    </dialog>
