<?php
    require_once('../security/config.php');

    if (empty($_SESSION['log_status'])) {
        header("location: ../");
    }
    // FETCH USER DETAILS AND DISPLAY IT
    if (! empty($_GET['editDetails'])) {
        $user_id = $_GET['editDetails'];

        $all_id = mysqli_query($PDO, "SELECT * FROM `students_data` WHERE student_f_name ='$user_id' OR student_index = '$user_id' OR deleted  = 'not' ");
        while($Val = mysqli_fetch_array($all_id)){
          $cus_fname = $Val['student_f_name'];
          $cus_lname = $Val['student_l_name']; 
          $cus_country = $Val['country'];
          $cus_id = $Val['f_id'];
          $cus_index = $Val['student_index'];
          $cus_courses = $Val['courses'];
          $cus_image = $Val['avatar'];
          $cus_email = $Val['email'];
          $cus_phone = $Val['telephone'];
          $cus_status = $Val['status'];
          $user_dept = $Val['department'];
          $cus_password = $Val['PassWD'];
          $user_deleted = $Val['deleted'];
        }
      }
      else {
        header("location: ./");
      }

    //   POST CUSTOMER DATA TO THE CUSTOMER DB
    if (isset($_POST['update_customer'])) {
        $user_id = $cus_id;
        $user_fn = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
        $user_ln = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
        $user_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
        $user_index = filter_var($_POST['index_number'], FILTER_SANITIZE_STRING);
        // $user_city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
        // $user_town = filter_var($_POST['town'], FILTER_SANITIZE_STRING);
        $user_country = filter_var($_POST['Country'], FILTER_SANITIZE_STRING);
        $user_status = $cus_status;
        $user_courses = filter_var($_POST['courses'], FILTER_SANITIZE_STRING);
        $user_phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
        // $user_region = filter_var($_POST['region'], FILTER_SANITIZE_STRING);
        $user_dept = filter_var($_POST['Department'], FILTER_SANITIZE_STRING);
        // $user_avatar = filter_var($_POST['avatar']);
    }

    // $update = mysqli_query($PDO, "UPDATE `students_data` SET `student_f_name`='$user_fn',`student_l_name`='$user_ln',`email`='$user_email',`telephone`='$user_phone',`courses`='$user_courses',`country`='$user_country',`department`='$user_dept',`deleted`='not',`status`='$user_status' WHERE f_id='$user_id'");    
    
    if (isset($update)) {
        $customer_update = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Updated successfully!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

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
        <link rel="icon" sizes="180x180" href="">
        <link rel="apple-touch-icon" sizes="180x180" href="">
        <title>Editing <?=$cus_fname?> <?=$cus_lname?></title>
        <link rel="stylesheet" href="../../node_modules/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            body{ background: #d0e3ff; } #go_back{ width: 10%;}
        </style>
    </head>
    <body>
        <header>
            <div class="row justify-content-center bg-primary p-3">
                <a id="go_back" class="btn btn-warning mb-2 mt-2 p-2" href="../">Go back</a>
            </div>
        </header>
        
        <main class="container mt-4 mb-5">
            <?php if(isset($customer_update)){echo($customer_update);} ?>
            <div class="row justify-content-center">
                <form method="post" class="w-50">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" name="fname" value="<?=$cus_fname?>">
                        <label for="username">First Name</label>
                    </div>
                                    <div class="form-floating mb-3">
                                        <input  value="<?=$cus_lname?>" type="text" class="form-control rounded-3" name="lname">
                                        <label>Last Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input readonly type="number" class="form-control rounded-3" name="index_number"  value="<?=$cus_index?>">
                                        <label>Index Number</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input readonly type="email" class="form-control rounded-3" id="fname" name="email"  value="<?=$cus_email?>">
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input value="0<?=$cus_phone?>" type="number" class="form-control rounded-3" id="phone" name="phone" >
                                        <label for="phone">Telephone</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control rounded-3" id="courses" name="courses"  value="<?=$cus_courses?>">
                                        <label for="courses">Courses</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input readonly value="<?=$user_dept?>" type="text" class="form-control rounded-3" id="Department" name="Department" >
                                        <label for="Department">Department</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" readonly class="form-control rounded-3" id="Country" name="Country"  value="<?=$cus_country?>">
                                        <label for="Country">Country</label>
                                    </div>
                                    <div>
                                        <input type="hidden" name="cid" value="<?php echo $centralID?>">
                                    </div>
                                <hr>
                                <div>
                                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-warning" data-bs-dismiss="modal" name="update_customer" type="submit">Update <?=$cus_fname?> <?=$cus_lname?></button>
                                    <small class="text-muted">By clicking Sign up, you agree to our <a href="#">terms</a> and <a href="#">conditions</a></small>
                                <div>
                </form>
            </div>
        </main>
        
        <footer class="bg-primary text-white p-5 mt-5 pb-3">
            <div class="d-flex flex container">
                <a class="px-4" href="https://github.com/john-BAPTIS?tab=repositories" target="_blank"><img width="40" height="40" src="https://avatars.githubusercontent.com/u/71665600?v=4" alt="Logo"></a>
                <p >Copyright  © <?php echo( date("Y")); ?>, Bluerest Go Club - <strong>Powered by: <a href="mailto:jamesakweter@gmail.com" class="text-light">Akweter</a></strong></p>
            </div>
        </footer>

        <script src="../../node_modules/bootstrap.min.js"></script>
</body>
</html>