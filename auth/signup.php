<?php
    session_start();
    // error_reporting(E_WARNING || E_NOTICE || E_ERROR);

    include_once("../security/config.php");
    
    // Redirect user back to login if not logged in
    if (! empty($_SESSION['log_status'])) {
        header("location: ../");
    }
    
    // POST STUDENT DATA TO THE ADMIN DB
    if(isset($_POST['Signup'])){
        
        $student_first_name = htmlspecialchars($_POST['first_name']);
        $roll_number = htmlspecialchars($_POST['roll_number']);
        $student_PassWd = htmlspecialchars($_POST['pass1']);
        $hash_student_pass = password_hash($student_PassWd, PASSWORD_DEFAULT);
        $pass_confirm = $_POST['pass2'];
        $student_phone = htmlspecialchars($_POST['telephone']);
        $email = htmlspecialchars($_POST['email']);

        if( empty($roll_number) || empty($student_first_name) || empty($student_PassWd) ) {
            $fields_required = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>All fields are required!</strong>
                </div>';
        }
        elseif (($student_PassWd) != ($pass_confirm)) {
            $wrong_input = '
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Passwords do not match</strong>
                </div>';
        }
        elseif (! is_numeric($student_phone) || ! is_numeric($roll_number)) {
            $numemeric_val = '
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Roll # and Telephone must be numeric</strong>
                </div>';
        }
        else {
            // Comaparing user info to the one in the database
            $Data = "SELECT * FROM `students_data` WHERE student_index = '$roll_number' OR telephone = '$student_phone' ";
            $Query = mysqli_query($PDO, $Data) or die("Error fetching password");

            if(mysqli_num_rows($Query) > 0){
                $user_exists = '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Roll # or Telephone exists!</strong>
                    </div>';
            }
            else{
                mysqli_query($PDO, "INSERT INTO `students_data`(`f_id`, `student_index`, `student_f_name`, `student_l_name`, `email`, `PassWD`, `telephone`, `courses`, `country`, `avatar`, `deleted`) VALUES ('','$roll_number','$student_first_name','','$email','$hash_student_pass','$student_phone','','','','no')");

                $_SESSION['student_index_num'] = $roll_number;
                $_SESSION['log_status'] = 'Logged In';
                
                $user_added = '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>You are added sucessfully</strong>
                        <a href="../" class="nav-link text-decoration-none text-primary">Visit Dashboard</a>
                    </div>';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Class Attendance Records Software">
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
                <div class="modal-header"><h1 class="modal-title fs-3">Sign Up</h1></div>
                <?php if(isset($fields_required)){echo($fields_required);} if(isset($wrong_input)){echo($wrong_input);} if(isset($user_exists)){echo($user_exists);} if(isset($user_added)){echo($user_added);} if(isset($numemeric_val)){echo($numemeric_val);} if(isset($roll_above_twelve)){echo($roll_above_twelve);} if(isset($roll_less_12)){echo($roll_less_12);} if(isset($wrong_tel_num)){echo($wrong_tel_num);} ?>
                <div class="modal-body">
                        <div class="form-floating mb-2">
                            <input required type="text" class="form-control rounded-3" id="roll_number" name="roll_number" placeholder="Roll #:">
                            <label for="roll_number">Roll #</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input required type="email" class="form-control rounded-3" id="email" name="email" placeholder="example@bluecrest.edu.gh">
                            <label for="email">Student Email</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input required type="text" class="form-control rounded-3" id="fname" name="first_name" placeholder="Frank">
                            <label for="first_name">First Name</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input required type="tel" class="form-control rounded-3" id="telephone" name="telephone" placeholder="0540544760">
                            <label for="telephone">Telephone</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input required type="password" name="pass1" class="form-control rounded-3" id="pass1" placeholder="Password">
                            <label for="pass1">Password</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input required type="password" name="pass2" class="form-control rounded-3" id="pass2" placeholder="Comfirm Password">
                            <label for="pass2">Comfirm Password</label>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <button class=" mb-1 btn btn-lg rounded-3 btn-outline-primary" data-bs-dismiss="modal" name="Signup" type="submit">Register</button>
                        <!-- or <a href="./login.php" class="btn btn-secondary btn-sm">Login</a> -->
                    <div>
                        <small class="text-muted">By registering, you agree to our <a class="text-decoration-none" href="#">terms</a> and <a class="text-decoration-none" href="#">conditions</a></small>
                </div>
            </form>
        </main>
    </body>
</html>
