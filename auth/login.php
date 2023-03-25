
<?php
    session_start();
    error_reporting(E_WARNING || E_NOTICE || E_ERROR);

    include_once("../security/config.php");
    include_once("../tools/controls.php");
    
    // Redirect user back to login if not logged in
    if (! empty($_SESSION['log_status'])) {
        header("location: ../");
    }

    if(isset($_POST['signin'])){
        $stu_input_pass = $_POST['passd'];
        $student_index_number = $_POST['index_number'];

        // Fetch all users from the database
        $Fetch = mysqli_query($PDO, "SELECT * FROM `students_data` WHERE PassWD = '$stu_input_pass' AND student_index = '$student_index_number' OR email = '$student_index_number' OR deleted = 'no' ") or die("Error fetching email and password");

        while($query = mysqli_fetch_array($Fetch)){
            $student_index_num = $query['student_index'];
            $passWD = $query['PassWD'];
        }

        // VERIFY HASH PASSWORD IN THE DATABASE
        if (password_verify($stu_input_pass, $passWD)) {

            $_SESSION['student_index_num'] = $student_index_num;
            $_SESSION['log_status'] = 'Logged In';
            header('location: ../');
        }
        else {
            $message = ' 
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Wrong Password</strong>
            </div>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Supermarket Management Software">
        <meta name="author" content="James Akweter">
        <meta name="generator" content="Angel Dev Team">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <title>Login</title>
        <link rel="stylesheet" href="../../../node_modules/bootstrap.min.css">
        <link rel="stylesheet" href="../../../node_modules/fontawesome.min.css">
        <style>
            html,
            body {
                height: 100%;
            }

            main{width: 60%;;}

            body {
                display: flex;
                align-items: center;
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #b9bfc0;;
            }

            .form-signin {
                max-width: 330px;
                padding: 15px;
                border-radius: 5% 10% 0 15%;
            }

            .form-signin .form-floating:focus-within {
                z-index: 2;
            }

            .form-signin input[type="email"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }

            .form-signin input[type="password"] {
                margin-bottom: 10px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        </style>
    </head>
    <body class="text-center">
        <main class="form-signin m-auto bg-light">
            <form method="post">
                <img class="mb-4" src="../../../public/img/wheat.jpg" alt="" width="72" height="57">
                <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
                <?php if (isset($message)) { echo($message); } ?>
                <?php if (isset($login_success)) {echo($login_success); } ?>
                <div class="form-floating">
                    <input type="text" class="form-control" required id="floatingInput" name="index_number" placeholder="Roll #: eg. 3006241321">
                    <label for="floatingInput">Roll # eg: 3006241321</label>
                </div>
                <div class="form-floating">
                    <input type="password" required class="form-control" name="passd" id="password" placeholder="Password">
                    <label for="password">Password</label>
                </div>
                <div class="">
                    <a class="text-decoration-none" href="#">Forget Password</a>
                </div>            
                <div class="checkbox mb-3">
                    <label><input type="checkbox" value="remember-me"> Remember me</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" name="signin" type="submit">Sign in</button>
                <!-- <a href="./signup.php" class="btn btn-sm btn-warning mt-2">Sign up</a> -->
                <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y");?> Bluerest Go Club</p>
            </form>
        </main>
        
        <script src="../../../node_modules/bootstrap.min.js"></script>
    </body>
</html>

