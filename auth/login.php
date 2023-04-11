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
        $stu_input_pass = htmlspecialchars($_POST['passd']);
        $student_index_number = htmlspecialchars($_POST['index_number']);

        // Fetch all users from the database
        $Fetch = mysqli_query($PDO, "SELECT * FROM `students_data` WHERE PassWD = '$stu_input_pass' AND student_index = '$student_index_number' OR email = '$student_index_number' OR deleted = 'not' ") or die("Fatal Server Error");

        while($query = mysqli_fetch_array($Fetch)){
            $student_index_num = $query['student_index'];
            $passWD = $query['PassWD'];
            $user_tel = $query['telephone'];
        }

        // VERIFY HASH PASSWORD IN THE DATABASE
        if (password_verify($stu_input_pass, $passWD)) {

            $_SESSION['user_tel'] = $user_tel;
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
            
            /* The message box is shown when the user clicks on the password field */
            #message {
            display:none;
            background: #f1f1f1;
            color: #000;
            position: relative;
            padding: 20px;
            margin-top: 10px;
            }

            #message span { padding: 10px 35px; }

            /* Add a green text color and a checkmark when the requirements are right */
            .valid { color: green; }

            .valid:before {
                position: relative;
                left: -15px;
                content: "✔";
            }

            /* Add a red text color and an "x" when the requirements are wrong */
            .invalid { color: red; }

            .invalid:before {
            position: relative;
            left: -15px;
            content: "✖";
            }

            .invalid_num{color:red;}

            #length{
                margin-left: 18%;
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
                    <input type="text" class="form-control" required id="index_num_id" name="index_number" placeholder="Roll number">
                    <label for="floatingInput">Roll number</label>
                </div>
                <span id="invalid_num" class="invalid_num">Must be numeric and spaces no allowed</span>
                <div class="form-floating">
                    <input id="psw" type="password"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at number, uppercase, lowercase and 8 or more characters" required class="form-control" name="passd" id="password" placeholder="Password">
                    <label for="password">Password</label>
                </div>
                <div id="message">
                    <strong>Password must contain:</strong><br>
                    <span id="letter" class="invalid">Lowercase<span><br>
                    <span id="capital" class="invalid">Uppercase<span><br>
                    <span id="number" class="invalid"> Numbers<span><br>
                    <span id="length" class="invalid">8 characters Mininum<span>
                </div>
                <div>
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
        <script>
            
            var index_num = document.getElementById("index_num_id");
            var myInput = document.getElementById("psw");
            var letter = document.getElementById("letter");
            var capital = document.getElementById("capital");
            var number = document.getElementById("number");
            var length = document.getElementById("length");

            // When user clicks on the index number
            index_num.onfocus = function () {
                document.getElementById("invalid_num").style.display = "block";
            }

            // When user click outside the field
            index_num.onblur = function () {
                document.getElementById("invalid_num").style.display = "none";
            }

            // When use start typing
            index_num.onkeyup = function() {
                // Validate numbers
                var numbers = /[0-9]/g;
                if(index_num.value.match(numbers)) {  
                    letter.classList.add("valid_num");
                    number.classList.remove("invalid_num");
                } else {
                    // letter.classList.add("invalid_num");
                    // letter.classList.remove("valid_num");
                    alert("should be numeric");
                }
            }
            
            // When the user clicks on the password field, show the message box
            myInput.onfocus = function() {
                document.getElementById("message").style.display = "block";
            }

            // When the user clicks outside of the password field, hide the message box
            myInput.onblur = function() {
                document.getElementById("message").style.display = "none";
            }

            // When the user starts to type something inside the password field
            myInput.onkeyup = function() {
                // Validate lowercase letters
                var lowerCaseLetters = /[a-z]/g;
                if(myInput.value.match(lowerCaseLetters)) {  
                    letter.classList.remove("invalid");
                    letter.classList.add("valid");
                } else {
                    letter.classList.remove("valid");
                    letter.classList.add("invalid");
                }
                
                // Validate capital letters
                var upperCaseLetters = /[A-Z]/g;
                if(myInput.value.match(upperCaseLetters)) {  
                    capital.classList.remove("invalid");
                    capital.classList.add("valid");
                } else {
                    capital.classList.remove("valid");
                    capital.classList.add("invalid");
                }

                // Validate numbers
                var numbers = /[0-9]/g;
                if(myInput.value.match(numbers)) {  
                    number.classList.remove("invalid");
                    number.classList.add("valid");
                } else {
                    number.classList.remove("valid");
                    number.classList.add("invalid");
                }
                
                // Validate length
                if(myInput.value.length >= 8) {
                    length.classList.remove("invalid");
                    length.classList.add("valid");
                } else {
                    length.classList.remove("valid");
                    length.classList.add("invalid");
                }
            }
        </script>
    </body>
</html>

