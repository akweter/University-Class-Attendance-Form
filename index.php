<?php
    session_start();
    // error_reporting(E_WARNING || E_ERROR || E_NOTICE);
    include_once("./security/config.php");
    include_once("./tools/time_date.php");

    $index_session = $_SESSION['student_index_num'];
    $log_status = $_SESSION['log_status'];

    // Redirect user back to login if not logged in
    if (empty($log_status)) {
        header("location: ./auth/login.php");
    }
    
    // SELECT STUDENT ID BASED ON THE GIVEN ID
    if (! empty($_SESSION['student_index_num'])) {
        $user_data = mysqli_query($PDO, "SELECT * FROM students_data WHERE student_index = '$index_session' AND deleted = 'no' ");
        while($scan_row = mysqli_fetch_array($user_data)){
            $student_f_name_dis = $scan_row['student_f_name'];
            $student_last_name = $scan_row['student_l_name'];
            $student_status = $scan_row['status'];
            $_user_id = $a['f_id '];
        }
    }
    
    // POST FORM DATA TO THE ATTENDANCE DATABASE
    if (isset($_POST['submit_form'])) {
        $dating = new DateTime();
        
        $gmt = $dating->setTImezone(new DateTimeZone('GMT'));

        $form_id = '';
        $student_id = $index_session;
        $submmitted_by = $student_f_name_dis;
        $current_time;
        $current_date;
        $course_name = htmlspecialchars($_POST['course_name']);
        $lecturer_name = htmlspecialchars($_POST['lecturer_name']);
        $roll_call = htmlspecialchars($_POST['roll_call']);
        $topic = htmlspecialchars($_POST['topic']);
        $l_approval = htmlspecialchars($_POST['l_approval']);
        
        $insert = mysqli_query($PDO, "INSERT INTO `attendance_data`(`f_id`, `student_index`, `submitted_by`, `courses`, `lecturers_name`, `date`, `time`, `status`, `class_number`, `topic`, `deleted`) VALUES ('$form_id','$student_id','$submmitted_by','$course_name','$lecturer_name','$current_date','$current_time','$l_approval','$roll_call','$topic','no');");

        if (isset($insert)) {
            $insert_succesfully = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <big>Attendance submitted successfully!</big>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        };
    }
    
    // POST STUDENT DATA TO THE ADMIN DB
    if(isset($_POST['new_student_sub'])){
        
        $student_first_name = htmlspecialchars($_POST['first_name']);
        $roll_number = htmlspecialchars($_POST['roll_number']);
        $student_PassWd = htmlspecialchars($_POST['pass1']);
        $hash_student_pass = password_hash($student_PassWd, PASSWORD_DEFAULT);
        $pass_confirm = $_POST['pass2'];
        $student_phone = htmlspecialchars($_POST['telephone']);
        $email = htmlspecialchars($_POST['email']);
        $student_country = htmlspecialchars($_POST['country']);
        $student_lastName = htmlspecialchars($_POST['last_name']);

        if( empty($roll_number) || empty($student_first_name) || empty($student_PassWd) ) {
            $fields_required = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>All fields are required!</strong>
                </div>';
        }
        elseif (($student_PassWd) != ($pass_confirm)) {
            $wrong_input = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
            $Data = "SELECT * FROM `students_data` WHERE student_index = '$roll_number' OR telephone = '$student_phone' OR email = '$email' OR deleted = 'no' ";
            $Query = mysqli_query($PDO, $Data) or die("Error fetching password");

            if(mysqli_num_rows($Query) > 0){
                $user_exists = '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Roll, Email or Telephone exists!</strong>
                    </div>';
            }
            else{
                mysqli_query($PDO, "INSERT INTO `students_data`(`f_id`, `student_index`, `student_f_name`, `student_l_name`, `email`, `PassWD`, `telephone`, `courses`, `country`, `department`, `avatar`, `deleted`, `status`) VALUES ('','$roll_number','$student_first_name','$student_lastName','$email','$hash_student_pass','$student_phone','','$student_country', '','','no', 'Student')");

                $user_added = '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>New student added sucessfully</strong>
                        <a href="./view/view_student_lecturer.php?details='.$roll_number.'" class="nav-link text-decoration-none text-primary">View Details</a>
                    </div>';
            }
        }
    }

    // POST LECTURER'S DATA TO STUDENT DATABASE
    if (isset($_POST['submit_new_lecturer'])) {
        $l_first_name = htmlspecialchars($_POST['l_first_name']);
        $l_lastname = htmlspecialchars($_POST['l_lastname']);
        // $student_PassWd = htmlspecialchars($_POST['pass1']);
        // $hash_student_pass = password_hash($student_PassWd, PASSWORD_DEFAULT);
        // $pass_confirm = $_POST['pass2'];
        $l_telephone = htmlspecialchars($_POST['l_telephone']);
        $l_email = htmlspecialchars($_POST['l_email']);
        $l_department = htmlspecialchars($_POST['l_department']);
        $l_specialisation = htmlspecialchars($_POST['l_specialisation']);

        if( empty($l_telephone) || empty($l_email) || empty($l_first_name) ) {
            $fields_required = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>All fields are required!</strong>
                </div>';
        }
        elseif (! is_numeric($l_telephone)) {
            $l_tel_num = '
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Telephone must be numeric</strong>
                </div>';
        }
        else {
            // Comaparing user info to the one in the database
            $Data = "SELECT * FROM `lecturers_data` WHERE telephone = '$l_telephone' OR email = '$l_email' OR deleted = 'no' ";
            $Query = mysqli_query($PDO, $Data) or die("Error fetching password");

            if(mysqli_num_rows($Query) > 0){
                $user_exists = '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Email or Telephone exists!</strong>
                    </div>';
            }
            else{
                $user_added = mysqli_query($PDO, "INSERT INTO `students_data`(`f_id`, `student_index`, `student_f_name`, `student_l_name`, `email`, `PassWD`, `telephone`, `courses`, `country`, `department`, `avatar`, `deleted`, `status`) VALUES ('','$l_telephone','$l_first_name','$l_lastname','$l_email','0000','$l_telephone','Not Available','Not Available','$l_department','','no','Lecturer')");

                $user_added = '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>New Lecturer added sucessfully</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
    }

?>

<html lang="en" onload="displayTime();">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Supermarket Management Software">
        <meta name="author" content="James Akweter">
        <meta name="generator" content="Angel Dev Team">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <title>Class Attendance Form | BSP</title>
        <link rel="stylesheet" href="../../node_modules/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            main{
                margin: 0 20px;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    </head>

    <body>
        <div class="bg-primary">
            <header class="blog-header lh-1 py-2 container fluid">
                <div class="row flex-nowrap justify-content-around align-items-center">
                    <div class="col-md-1">
                        <a class="navbar-brand" href="#">
                        <img src="https://avatars.githubusercontent.com/u/71665600?v=4" width="40" height="35" class="d-inline-block me-2" alt="Bluecrest Go Club" loading="lazy">
                        </a>
                    </div>
                    <div class="col-md-10 justify-content-center"> 
                        <form action="./search/index.php" method="post" role="search" class="mt- d-flex">
                            <input class="form-control me-2" name="q" type="search" placeholder="Search name or Roll" aria-label="Search">
                            <button class="btn btn-light" type="submit"><i class="fa fa-search fa-lg"></i></button>
                        </form>
                    </div>
                    <div class="col-md-auto justify-content-end align-items-end">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#user_account_info">Account</button>
                    </div>
                </div>
                <div id="showTime" class="row justify-content-center fw-bold fs-4 text-light bg-primary"></div>
            </header>
        </div>
        <main class="mt-3 mb-5 pb-3">
            <!-- Alert user for succesful form upload -->
            <?php if (isset($insert_succesfully)) { echo($insert_succesfully);}  if(isset($fields_required)){echo($fields_required);} if(isset($wrong_input)){echo($wrong_input);} if(isset($user_exists)){echo($user_exists);} if(isset($user_added)){echo($user_added);} if(isset($numemeric_val)){echo($numemeric_val);} if(isset($roll_above_twelve)){echo($roll_above_twelve);} if(isset($roll_less_12)){echo($roll_less_12);} if(isset($wrong_tel_num)){echo($wrong_tel_num);} if(isset($l_tel_num)){echo($l_tel_num);} ?>

            <div class="container">
                <button type="submit" onclick="return printPage();" class="btn btn-outline-dark">Print Full page</button>
                <button type="submit" onclick="return printForm();" id="printFormId" class="btn btn-secondary">Download Form</button>
                <button data-bs-toggle="modal" data-bs-target="#add_new_lecturer" class="btn btn-primary">Add Lecturer</button>
                <button data-bs-toggle="modal" data-bs-target="#add_new_student_modal" class="btn btn-info me-2">Add Student</button>
                <button data-bs-toggle="modal" data-bs-target="#fill_form_once" class="btn btn-warning me-2">Attendance Form</button>
            </div>
            
            
            <div id="form_table" class="mt-4">
                <table class="table table-hover flex">
                    <thead class="table-primary">
                        <th>#</th>
                        <!-- <th>Index</th> -->
                        <!-- <th>F Name</th> -->
                        <th>Course</th>
                        <th>Pupils</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Lecturer</th>
                        <th>Approval</th>
                        <th>Submitted By</th>
                        <th>Topic</th>
                    </thead>
                    <tbody>
                        <?php 
                        // Fetch querries from the database
                        $Data = mysqli_query($PDO, "SELECT * FROM attendance_data WHERE deleted = 'no' ORDER BY f_id DESC");
                        $count = 0;
                        
                        // Display database information in table
                        while($info = mysqli_fetch_array($Data)){
                            $count++;
                            $f_id = $info['f_id'];
                            $s_index = $info['student_index'];
                            $submitted_by = $info['submitted_by'];
                            $courses = $info['courses'];
                            $lecturers_name = $info['lecturers_name'];
                            $classdate = $info['date'];
                            $submission_time = $info['time'];
                            $status = $info['status'];
                            $c_number = $info['class_number'];
                            $topic = $info['topic'];
                            
                            // SELECT STUDENT BASED ON THE NAME FORM THE STUDENT DATABASE
                            $select_user = mysqli_query($PDO, "SELECT * FROM students_data WHERE deleted = 'no' OR email = '$l_email' OR email = '$email' ");
                            while($search = mysqli_fetch_array($select_user)){
                                $referal_id = $search['f_id'];
                                $ref_tel = $search['telephone'];
                            }
                        ?>
                        <tr>
                            <td><?=$count?>.</td>
                            <td><?=$courses?></td>
                            <td><?=$c_number?></a></td>
                            <td><?=$classdate?></td>
                            <td><?=$submission_time?></td>
                            <td><a class="text-decoration-none" href="./view/view_student_lecturer.php?details=<?=$referal_id?>"><?=$lecturers_name?></a></td>
                            <td><?=$status?></td>
                            <td><a class="text-decoration-none" href="./view/view_student_lecturer.php?details=<?=$referal_id?>"><?=$submitted_by?></a></td>
                            <td><?=$topic?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table> 
            </div>
        </main>

        <footer class="bg-primary text-white pt-3 pb-3">
            <div class="d-flex flex">
                <a class="px-4" href="https://github.com/john-BAPTIS?tab=repositories" target="_blank"><img width="40" height="40" src="https://avatars.githubusercontent.com/u/71665600?v=4" alt="Logo"></a>
                <p >Copyright  © <?php echo( date("Y")); ?>, Bluerest Go Club - <strong>Powered by: <a href="mailto:jamesakweter@gmail.com" class="text-light">Akweter</a></strong></p>
            </div>
        </footer>

        <!-- DISPLAY USER ACCOUNT INFO -->
        <div class="modal fade" id="user_account_info" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <i class="fs-3 align-center" id="fill_form_onceLabel"><l class="text-danger">Name:</l> <?=$student_f_name_dis?> <?=$student_last_name?></i>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <div class="card card-cover border-0"> -->
                            <div class="d-flex flex-column justify-content-center">
                                
                                <div style="text-align:center;" class=" overflow-hidden rounded-4">
                                    <img style="border-radius: 60px;" src="../shop/public/img/puma.jpg" alt="<?=$student_f_name_dis?>" width="130" height="130">
                                    <p><?=$student_status?></p>
                                </div>
                                <div style="text-align:center;">
                                    <ul class="d-flex list-unstyled">
                                        <li class="me-4 d-flex">
                                            <a href="./view/view_student_lecturer.php?details=<?=$referal_id?>" class="btn btn-info">Dashboard</a>
                                        </li>
                                        <li>
                                            <a href="./auth/logout.php" class="btn btn-danger">Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD NEW FORM MODAL -->
        <div class="modal fade" id="fill_form_once" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-3"  id="fill_form_onceLabel">Cannot Edit Once Submitted</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select required class="form-control" name="course_name" id="">
                                    <option value="" selected disabled class="option">Course</option>
                                    <option value="Advanced Object Oriented Programming" class="option">Advanced Object Oriented Programming</option>
                                    <option value="Data Commuication and Networks" class="option">Data Commuication and Networks</option>
                                    <option value="Human Computer Interface" class="option">Data Structures & Algorithms</option>
                                    <option value="Database Management Systems" class="option">Database Management Systems</option>
                                    <option value="Discrete Mathematics" class="option">Discrete Mathematics</option>
                                    <option value="Human Computer Interface" class="option">Human Computer Interface</option>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <select required class="form-control" name="lecturer_name" id="">
                                    <option value="" selected disabled class="option">Lecturer</option>
                                    <option value="Engr. Victoria Dansowaa" class="option">Engr. Victoria Dansowaa</option>
                                    <option value="Nana Kwame Mangortey" class="option">Nana Kwame Mangortey</option>
                                    <option value="Mr. Duodu Quarshie" class="option">Mr. Duodu Quarshie</option>
                                    <option value="Mr. Charles Saah" class="option">Mr. Charles Saah</option>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <input required type="number" name="roll_call" class="form-control rounded-3">
                                <label>Pupils in class</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea required name="topic" class="form-control"></textarea>
                                <label>Topic</label>
                            </div>
                            <div class="form-floating mb-3">
                                <p>Lecturer approval</p>
                                <div class="">
                                    <input type="radio" value="Yes" class="form-check-input" name="l_approval">
                                    <label>Yes</label>
                                </div>
                                <div class="">
                                    <input type="radio" value="N    o" class="form-check-input" name="l_approval">
                                    <label>No</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div style="text-align:center">
                            <button class="mb-2 btn btn-lg w-50 rounded-3 btn-primary" data-bs-dismiss="modal" name="submit_form" type="submit">Submit Form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ADD NEW LECTURER MODAL -->
        <div class="modal fade" id="add_new_lecturer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header"><h1 class="modal-title fs-3">Add New Lecturer</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input required type="text" class="form-control rounded-3" id="l_first_name" name="l_first_name" placeholder="Frank">
                                <label for="l_first_name">First Name</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="text" class="form-control rounded-3" id="l_lastname" name="l_lastname" placeholder="Adu Gyamfi">
                                <label for="l_lastname">Last Name</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="email" class="form-control rounded-3" id="l_email" name="l_email" placeholder="example@bluecrest.edu.gh">
                                <label for="l_email">Email</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="tel" class="form-control rounded-3" id="l_telephone" name="l_telephone" placeholder="0540544760">
                                <label for="l_telephone">Telephone</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select required class="form-control" name="l_department" id="l_department">
                                    <option value="" selected disabled class="option">Department</option>
                                    <option value="School of Technology" class="option">School of Technology</option>
                                    <option value="School of Fashion" class="option">School of Fashion</option>
                                    <option value="Mass Media" class="option">Mass Media</option>
                                    <option value="Business And Communication" class="option">Business And Communication</option>
                                </select>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="tel" class="form-control rounded-3" id="l_specialisation" name="l_specialisation" placeholder="Specialisation">
                                <label for="l_specialisation">Specialisation</label>
                            </div>
                            <!-- <div class="form-floating mb-2">
                                <input required type="password" name="pass1" class="form-control rounded-3" id="pass1" placeholder="Password">
                                <label for="pass1">Temporal Password</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input required type="password" name="pass2" class="form-control rounded-3" id="pass2" placeholder="Comfirm Password">
                                    <label for="pass2">Repeat Password</label>
                                </div> -->
                            </div>
                            <hr>
                            <div>
                                <button class=" mb-1 btn btn-lg rounded-3 form-control btn-outline-primary" data-bs-dismiss="modal" name="submit_new_lecturer" type="submit">Submit</button>
                                <!-- or <a href="./login.php" class="btn btn-secondary btn-sm">Login</a> -->
                            <div>
                            <div style="text-align:center;">
                                <small class="text-muted" style="text-align:center;">By submitting this form, you agree to our <a class="text-decoration-none" href="#">terms</a> and <a class="text-decoration-none" href="#">conditions</a></small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ADD NEW STUDENT MODAL -->
        <div class="modal fade" id="add_new_student_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <div class="modal-header"><h1 class="modal-title fs-3">Add New Student</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
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
                                    <input required type="text" class="form-control rounded-3" id="lname" name="last_name" placeholder="Adu Gyamfi">
                                    <label for="last_name">Last Name</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input required type="tel" class="form-control rounded-3" id="telephone" name="telephone" placeholder="0540544760">
                                    <label for="telephone">Telephone</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input required type="tel" class="form-control rounded-3" id="country" name="country" placeholder="Country">
                                    <label for="country">Country</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input required type="password" name="pass1" class="form-control rounded-3" id="pass1" placeholder="Password">
                                    <label for="pass1">Temporal Password</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input required type="password" name="pass2" class="form-control rounded-3" id="pass2" placeholder="Comfirm Password">
                                    <label for="pass2">Repeat Password</label>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <button class=" mb-1 btn btn-lg rounded-3 form-control btn-outline-primary" data-bs-dismiss="modal" name="new_student_sub" type="submit">Submit</button>
                                <!-- or <a href="./login.php" class="btn btn-secondary btn-sm">Login</a> -->
                            <div>
                            <div style="text-align:center;">
                                <small class="text-muted" style="text-align:center;">By submitting this form, you agree to our <a class="text-decoration-none" href="#">terms</a> and <a class="text-decoration-none" href="#">conditions</a></small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // PRINT FULL PAGE
            function printPage() {
                window.print();
            }

            // DOWWLOAD TABLE DATA
            window.onload = function printForm() {
                document.getElementById('printFormId').addEventListener("click", ()=>{
                    const table = this.document.getElementById('form_table').innerHTML;
                    html2pdf().from(table).save();
                }); 
            }

            // OUTPUT DATE AND TIME
            function displayTime() {
                var span = document.getElementById('showTime');
                var am = JSON.stringify("AM"); var pm = JSON.stringify("PM");

                var d = new Date();
                var s = d.getSeconds();
                var m = d.getMinutes();
                var h = d.getHours();
                if (h <= 12) { var ampm = am }else{ var ampm = pm;}
                span.textContent = ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s) + " " + ampm;
            }
            setInterval(displayTime, 1000);
            
        </script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script> -->
        <script src="../../node_modules/bootstrap.min.js"></script>
    </body>
</html>
