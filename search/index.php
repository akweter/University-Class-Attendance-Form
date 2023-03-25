<?php
    session_start();
    // error_reporting(E_WARNING || E_NOTICE || E_ERROR);
    include_once("../security/config.php");

    $index_session = $_SESSION['student_index_num'];
    $log_status = $_SESSION['log_status'];

    // Redirect user back to login if not logged in
    if (empty($log_status)) {
        header("location: ../auth/login.php");
    }
    
    // $search_product_name = mysqli_query($PDO, "SELECT * FROM `products` WHERE P_name = '' ");
    //     while($Val = mysqli_fetch_array($search_product_name)){
    //         $product_name = $Val['P_name'];
    //     }    
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
            <link rel="icon" sizes="180x180" href="../../public/img/glass.webp">
            <link rel="apple-touch-icon" sizes="180x180" href="../../public/img/glass.webp">
            <title>Search result | BLuecrest Go Club</title>
            <link rel="stylesheet" href="../../../node_modules/bootstrap.min.css">
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
                            <form method="post" role="search" name="q" class="d-flex">
                                <input class="form-control me-2" type="search" value="<?php if(isset($search_term)){echo($search_term);} ?>" aria-label="Search">
                                <button class="btn btn-light" type="submit"><i class="fa fa-search fa-lg"></i></button>
                            </form>
                    </div>
                    <div class="col-2 d-flex justify-content-end align-items-end">
                        <a class="btn btn-warning" href="#">Account</a>
                    </div>
                </div>
            </header>
        </div>
        
        <!-- Main Content -->
        <main class="container mt-5 mb-5">
            <?php   
                if (! empty($_POST['q'])) {
                    $search_term = $_POST['q'];

                    if (! empty($search_term)) {
                        $search = mysqli_query($PDO,"SELECT * FROM `students_data` WHERE CONCAT(student_index,student_f_name,student_l_name,email,telephone,country) LIKE '%$search_term%' OR deleted = 'no' ORDER BY student_index ASC");
                        $numbering = 0;
                        
                        if(mysqli_num_rows($search) > 0){
                            foreach($search as $data){
                                $numbering++;
                                $index_number = $data['student_index'];
                                $first_name = $data['student_f_name'];
                                $email = $data['email']; }?>
                                
                                <a href="../view/view_student_lecturer.php?details=<?=$index_number?>" class="list-group-item-action list-group-item-secondary"><?=$numbering?> - <?=$first_name?> - <?=$index_number?></a>
                            <?php 
                        }
                        else{
                            echo('<tr" colspan="6"><td colspan="6"><h2>No records found!</h2></td></tr><p></p><a href="../" class="btn btn-lg btn-warning">Vist Dashboard</a></p> ');
                        }
                    }
                    else {
                        header('location: ../');
                    }
                }
            ?>
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
