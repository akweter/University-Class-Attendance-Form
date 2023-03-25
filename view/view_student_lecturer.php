<?php
    session_start();
    // error_reporting(E_WARNING || E_ERROR || E_NOTICE);
    include_once("../security/config.php");

    if (empty($_SESSION['log_status'])) {
        header("location: ../");
    }
    else {
        // FETCH USER INFORMATION TO COMPLETE THE CHECOUT
        if (! empty(isset($_GET['details']))) { 
            $student_id = $_GET['details'];
            $log_status = $_SESSION['log_status'];

            // FETCH USER DETAILS PER THE USERNAME $f_id = $info['f_id']; 
            $sid = mysqli_query($PDO, "SELECT * FROM `students_data` WHERE f_id = '$student_id' OR student_index = '$student_id' AND deleted = 'no' ");
            while($Val = mysqli_fetch_array($sid)){
                $f_id = $Val['f_id'];
                $s_index = $Val['student_index'];
                $s_f_name = $Val['student_f_name'];
                $s_l_name = $Val['student_l_name'];
                $s_email = $Val['email'];
                $s_pass = $Val['PassWD'];
                $s_phone = $Val['telephone'];
                $s_country = $Val['country'];
                $s_courses = $Val['courses'];
                $avatar = $Val['avatar'];
            }
        }
        else {
            header("location: ../");
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
        <title> | Bluecrest Go Club</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <!-- Header -->
        <div class="bg-primary">
            <header class="blog-header lh-1 py-2 container fluid">
                <div class="row flex-nowrap justify-content-between align-items-center">
                    <div class="col-1 pt-1">
                        <a class="navbar-brand" href="../">
                        <img src="https://avatars.githubusercontent.com/u/71665600?v=4" width="45" height="40" class="d-inline-block align-top" alt="Bluecrest Go Club" loading="lazy">
                        </a>                
                    </div>
                    <div class="col-7 text-center justify-content-center">
                            <form method="post" action="../search/index.php" role="search" name="q" class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="I am looking for" aria-label="Search">
                                <button class="btn btn-light" type="submit"><i class="fa fa-search fa-lg"></i></button>
                            </form>
                    </div>
                    <div class="col-2 d-flex justify-content-end align-items-end">
                        <a class="btn btn-warning" href="#">Account</a>
                    </div>
                </div>
            </header>
        </div>
        
        <!-- align-items-center justify-content-center -->
        <main>
            <div class="container mt-2 mb-5">
                <div class="row">
                        <table class="table table-hover">
                            <tbody>
                                <tr style="text-align:center;" class="table-success"><td colspan="2"><h2> <?php if(empty($s_f_name)){echo("<div>User Account</div>");} else{echo($s_f_name. " " .$s_l_name);}?></h2></td></tr>
                                <tr><td>Roll NUmber</td><td> <?php if(empty($s_index)){echo("<div>Update your index number</div>");} else{echo($s_index);}?></td></tr>
                                <tr><td>First Name</td><td> <?php if(empty($s_f_name)){echo("<div>Update your First Name</div>");} else{echo($s_f_name);}?></td></tr>
                                <tr><td>Last Name</td><td> <?php if(empty($s_l_name)){echo("<div>Update your Last Name</div>");} else{echo($s_l_name);}?></td></tr>
                                <tr><td>Telephone</td><td> <?php if(empty($s_phone)){echo("<div>Update your Telephone number</div>");} else{echo("0".$s_phone);}?></td></tr>
                                <tr><td>Email</td><td> <?php if(empty($s_email)){echo("<div>Email missing</div>");} else{echo($s_email);}?></td></tr>
                                <tr><td>Country</td><td> <?php if(empty($s_country)){echo("<div>Update your Country</div>");} else{echo($s_country);}?></td></tr>
                                <tr><td>Courses</td><td><?php if(empty($s_courses)){echo("<div>Your courses here</div>");} else{echo($s_courses);}?></td></tr>
                                <!-- <tr><td>City</td><td><?php if(empty($cus_city)){echo("<div>City missing</div>");} else{echo($cus_city);}?></td></tr>
                                <tr><td>Town</td><td><?php if(empty($cus_town)){echo("<div>Town missing</div>");} else{echo($cus_town);}?></td></tr>
                                <tr><td>Area Code</td><td><?php if(empty($cus_gps)){echo("<div>Zip Code missing</div>");} else{echo($cus_gps);}?></td></tr> -->
                                <!-- <tr><td>Password</td><td><?=$c_Password?></td></tr> -->
                            </tbody>
                        </table>
                        <a href="./edit_details.php?editDetails=<?=$f_id?>" class="btn btn-outline-primary btn-lg form-control">Edit My Account</a>
                    
                </div>
            </div>
        </main>
        
        <footer class="bg-primary text-white pt-3 pb-3">
            <div class="d-flex flex">
                <a class="px-4" href="https://github.com/john-BAPTIS?tab=repositories" target="_blank"><img width="40" height="40" src="https://avatars.githubusercontent.com/u/71665600?v=4" alt="Logo"></a>
                <p >Copyright  © <?php echo( date("Y")); ?>, Bluerest Go Club - <strong>Powered by: <a href="mailto:jamesakweter@gmail.com" class="text-light">Akweter</a></strong></p>
            </div>
        </footer>
        
        <script src="../../../node_modules/bootstrap.min.js"></script>
    </body>
</html>