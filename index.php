<?php
    session_start();
    // error_reporting(E_WARNING || E_ERROR || E_NOTICE);
    include_once("./security/config.php");
    include_once("./tools/time_date.php");

    $index_number_from_session = $_SESSION['student_index_num'];
    $log_status = $_SESSION['log_status'];
    $user_tel = $_SESSION['user_tel'];

    // Redirect to login if not logged in
    if (! isset($log_status)) {
        header("location: ./auth/login.php");
    }
    
    // SELECT STUDENT ID BASED ON THE GIVEN ID
    if (! empty($_SESSION['student_index_num'])) {
        $user_data = mysqli_query($PDO, "SELECT * FROM students_data WHERE student_index = '$index_number_from_session' OR telephone = '$user_tel' OR deleted = 'not' ");
        while($scan_row = mysqli_fetch_array($user_data)){
            $student_f_name_dis = $scan_row['student_f_name'];
            $student_last_name = $scan_row['student_l_name'];
            $student_status = $scan_row['status'];
            $_user_id = $scan_row['f_id '];
        }
    }
    
    // POST FORM DATA TO THE ATTENDANCE DATABASE
    if (isset($_POST['submit_form'])) {
        $dating = new DateTime();
        
        $gmt = $dating->setTImezone(new DateTimeZone('GMT'));

        $form_id = '';
        $student_id = $index_number_from_session;
        $submmitted_by = $student_f_name_dis;
        $current_time;
        $current_date;
        $course_name = htmlspecialchars($_POST['course_name']);
        $lecturer_f_name = htmlspecialchars($_POST['lecturer_f_name']);
        $lecturer_l_name = htmlspecialchars($_POST['lecturer_l_name']);
        $roll_call = htmlspecialchars($_POST['roll_call']);
        $topic = htmlspecialchars($_POST['topic']);
        $l_approval = htmlspecialchars($_POST['l_approval']);
        
        $insert = mysqli_query($PDO, "INSERT INTO `attendance_data`(`f_id`, `student_index`, `submitted_by`, `courses`, `lecturers_f_name`, `lecturer_l_name`, `date`, `time`, `status`, `class_number`, `topic`, `deleted`) VALUES ('$form_id','$student_id','$submmitted_by','$course_name','$lecturer_f_name','$lecturer_l_name','$current_date','$current_time','$l_approval','$roll_call','$topic','not');");

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
            $Data = "SELECT * FROM `students_data` WHERE student_index = '$roll_number' OR telephone = '$student_phone' OR email = '$email' AND deleted = 'no' ";
            $Query = mysqli_query($PDO, $Data) or die("Error fetching password");

            if(mysqli_num_rows($Query) > 0){
                $user_exists = '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Roll, Email or Telephone exists!</strong>
                    </div>';
            }
            else{
                mysqli_query($PDO, "INSERT INTO `students_data`(`f_id`, `student_index`, `student_f_name`, `student_l_name`, `email`, `PassWD`, `telephone`, `courses`, `country`, `department`, `avatar`, `deleted`, `status`) VALUES ('','$roll_number','$student_first_name','$student_lastName','$email','$hash_student_pass','$student_phone','','$student_country', '','','not', 'Student')");

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
        $lecturer_pass = htmlspecialchars($_POST['lecturer_pass']);
        $hash_lecturer_pass = password_hash($lecturer_pass, PASSWORD_DEFAULT);
        $l_telephone = htmlspecialchars($_POST['l_telephone']);
        $l_email = htmlspecialchars($_POST['l_email']);
        $l_department = htmlspecialchars($_POST['l_department']);

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
            $Data = "SELECT * FROM `students_data` WHERE telephone = '$l_telephone' OR email = '$l_email' AND deleted = 'no' ";
            $Query = mysqli_query($PDO, $Data) or die("Error fetching password");

            if(mysqli_num_rows($Query) > 0){
                $user_exists = '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Email or Telephone exists!</strong>
                    </div>';
            }
            else{
                $user_added = mysqli_query($PDO, "INSERT INTO `students_data`(`f_id`, `student_index`, `student_f_name`, `student_l_name`, `email`, `PassWD`, `telephone`, `courses`, `country`, `department`, `avatar`, `deleted`, `status`) VALUES ('','$l_telephone','$l_first_name','$l_lastname','$l_email','$hash_lecturer_pass','$l_telephone','Not Available','Not Available','$l_department','','not','Lecturer')");

                $user_added = '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>New Lecturer added sucessfully</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
    }
?>

<html lang="en" onpageshow="displayTime();">
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
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
                <span style="display:flex;flex-direction:row;" class="justify-content-center fw-bold fs-4 text-light bg-primary"><div id="showTime"></div>' <?=$current_ampm?></span>
                
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
                        $Data = mysqli_query($PDO, "SELECT * FROM attendance_data WHERE deleted = 'not' ORDER BY f_id DESC");
                        $count = 0;
                        
                        // Display database information in table
                        while($info = mysqli_fetch_array($Data)){
                            $count++;
                            $f_id = $info['f_id'];
                            $s_index = $info['student_index'];
                            $submitted_by = $info['submitted_by'];
                            $courses = $info['courses'];
                            $lecturers_f_name = $info['lecturers_f_name'];
                            $lecturers_l_name = $info['lecturer_l_name'];
                            $classdate = $info['date'];
                            $submission_time = $info['time'];
                            $status = $info['status'];
                            $c_number = $info['class_number'];
                            $topic = $info['topic'];
                        ?>
                        <tr>
                            <td><?=$count?>.</td>
                            <td><?=$courses?></td>
                            <td><?=$c_number?></a></td>
                            <td><?=$classdate?></td>
                            <td><?=$submission_time?></td>
                            <td><a class="text-decoration-none" href="./view/view_student_lecturer.php?details=<?=$lecturers_f_name?>"><?=$lecturers_f_name," "?><?=$lecturers_l_name?></a></td>
                            <td><?=$status?></td>
                            <td><a class="text-decoration-none" href="./view/view_student_lecturer.php?details=<?=$s_index?>"><?=$submitted_by?></a></td>
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
                    <div class="modal-body row justify-content-center"">
                        <!-- <div class="card card-cover border-0"> -->
                            <div class="d-flex flex-column justify-content-center">
                                
                                <div style="text-align:center;" class=" overflow-hidden rounded-4">
                                    <img style="border-radius: 60px;" src="../shop/public/img/puma.jpg" alt="<?=$student_f_name_dis?>" width="130" height="130">
                                    <p><?=$student_status?></p>
                                </div>
                                <div style="text-align:center;">
                                    <ul class="d-flex list-unstyled">
                                        <li class="me-4 d-flex">
                                            <div>
                                                <a href="./view/view_student_lecturer.php?details=<?=$referal_id?>" class="btn btn-info">Dashboard</a>
                                            </div>
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
                                <select required class="form-control" name="course_name">
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
                                <select required class="form-control" name="lecturer_f_name">
                                    <option value="" selected disabled class="option">Lecturer First Name</option>
                                    <option value="Engr. Victoria Dansowaa" class="option">Eng. Victoria</option>
                                    <option value="Nana Kwame Mangortey" class="option">Nana Kwame</option>
                                    <option value="Mr. Duodu Quarshie" class="option">Mr. Duodu</option>
                                    <option value="Mr. Charles Saah" class="option">Mr. Charles</option>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <select required class="form-control" name="lecturer_l_name">
                                    <option value="" selected disabled class="option">Lecturer Last Name</option>
                                    <option value="Engr. Victoria Dansowaa" class="option">Dansowaa</option>
                                    <option value="Nana Kwame Mangortey" class="option">Mangortey</option>
                                    <option value="Mr. Duodu Quarshie" class="option">Quarshie</option>
                                    <option value="Mr. Charles Saah" class="option">Saah</option>
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
                            <span>Lecturer approval</span>
                            <div id="lecturer_approveal" class="mb-3">
                                <label>Yes</label>
                                <input type="radio" value="Yes" class="" name="l_approval">
                                <label>No</label>
                                <input type="radio" value="No" class="" name="l_approval">
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
                                <select required class="form-control rounded-3" name="country">
                                    <option value="" selected disabled class="option">Country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Åland Islands">Åland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="BN">Brunei Darussalam</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos (Keeling) Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CG">Congo</option>
                                    <option value="CD">Congo, The Democratic Republic of The</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="CI">Cote D'ivoire</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-bissau">Guinea-bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and Mcdonald Islands</option>
                                    <option value="VA">Holy See (Vatican City State)</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HK">Hong Kong</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IR">Iran, Islamic Republic of</option>
                                    <option value="IQ">Iraq</option>
                                    <option value="IE">Ireland</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                    <option value="KR">Korea, Republic of</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="KG">Kyrgyzstan</option>
                                    <option value="LA">Lao People's Democratic Republic</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libyan Arab Jamahiriya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MO">Macao</option>
                                    <option value="MK">Macedonia, The Former Yugoslav Republic of</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="MX">Mexico</option>
                                    <option value="FM">Micronesia, Federated States of</option>
                                    <option value="MD">Moldova, Republic of</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="AN">Netherlands Antilles</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="MP">Northern Mariana Islands</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PW">Palau</option>
                                    <option value="PS">Palestinian Territory, Occupied</option>
                                    <option value="PA">Panama</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RE">Reunion</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russian Federation</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="SH">Saint Helena</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and The Grenadines</option>
                                    <option value="WS">Samoa</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">Sao Tome and Principe</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="RS">Serbia</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia and The South Sandwich Islands</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-leste">Timor-leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="UG">Uganda</option>
                                    <option value="Uganda">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="VietNam">Viet Nam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.S">Virgin Islands, U.S.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="password" name="pass1" class="form-control rounded-3" id="pass1" placeholder="Password">
                                <label for="pass1">Temporal Password</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="password" name="pass2" class="form-control rounded-3" id="pass2" placeholder="Comfirm Password">
                                <label for="pass2">Repeat Password</label>
                            </div>
                            <hr>
                            <div>
                                <button class=" mb-1 btn btn-lg rounded-3 form-control btn-outline-primary" data-bs-dismiss="modal" name="new_student_sub" type="submit">Submit</button>
                            <div>
                            <div style="text-align:center;">
                                <small class="text-muted" style="text-align:center;">By submitting this form, you agree to our <a class="text-decoration-none" href="#">terms</a> and <a class="text-decoration-none" href="#">conditions</a></small>
                            </div>
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
                                <select required class="form-control" name="l_first_name">
                                    <option value="" selected disabled class="option">First Name</option>
                                    <option value="Engr. Victoria" class="option">Engr. Victoria</option>
                                    <option value="Nana Kwame" class="option">Nana Kwame</option>
                                    <option value="Mr. Duodu" class="option">Mr. Duodu</option>
                                    <option value="Mr. Charles" class="option">Mr. Charles</option>
                                </select>
                            </div>
                            <div class="form-floating mb-2">
                                <select required class="form-control" name="l_lastname">
                                    <option value="" selected disabled class="option">Last Name</option>
                                    <option value="Dansowaa" class="option"> Dansowaa</option>
                                    <option value="Mangortey" class="option">Mangortey</option>
                                    <option value="Quarshie" class="option">Quarshie</option>
                                    <option value="Saah" class="option">Saah</option>
                                </select>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="email" class="form-control rounded-3" id="l_email" name="l_email" placeholder="first.lastname@bluecrest.edu.gh">
                                <label for="l_email">Email</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input required type="tel" class="form-control rounded-3" id="l_telephone" name="l_telephone" placeholder="0540544780">
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
                                <input required type="password" class="form-control rounded-3" id="lecturer_pass" name="lecturer_pass" placeholder="Lecturer Password">
                                <label for="lecturer_pass">Temporal Password</label>
                            </div>
                            <hr>
                            <div>
                                <button  name="submit_new_lecturer" class="mb-1 btn btn-lg rounded-3 form-control btn-outline-primary" type="submit">Submit</button>
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

                var d = new Date();
                var s = d.getSeconds();
                var m = d.getMinutes();
                var h = d.getHours();
                span.textContent = ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s);
            }
            setInterval(displayTime, 1000);
            
        </script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script> -->
        <script src="../node_modules/bootstrap.min.js"></script>
    </body>
</html>
