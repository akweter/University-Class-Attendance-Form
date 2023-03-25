
<?php
    //Include database connection file.
    include_once("./Database/config.php");

    //Check if form is submiited for update, then redirect to homepage after update
    if(isset($_POST['update'])){
        
        $id = $_POST['id'];

        $index = $_POST['index'];
        $name = $_POST['name'];
        $program = $_POST['program'];
        $semester = $_POST['semester'];
        $mobile = $_POST['mobile'];
        $classDate = $_POST['date'];
        $course = $_POST['course'];
        $lecturer = $_POST['lecturer'];

    //Update form
        $Data = mysqli_query($Dbase, "UPDATE `row` SET `index` = '$index', `name` = '$name', `program` = '$program', `semester` = '$semester', `mobile` = '$mobile', `classDate` = '$classDate', `course` = '$course', `lecturer` = '$lecturer' WHERE id=$id");

    //Redirect.
        header("Location: index.php");
    }
?>

<?php
    //Show selected user based on the chosen in url 
    $id = $_GET['id'];

    //Fetch user data based on the id
    $Data = mysqli_query($Dbase, "SELECT * FROM `row` WHERE id=$id");

    while($row_data = mysqli_fetch_array($Data)){
        $index = $row_data['index'];
        $name = $row_data['name'];
        $program = $row_data['program'];
        $semester = $row_data['semester'];
        $mobile = $row_data['mobile'];
        $classDate = $row_data['classDate'];
        $course = $row_data['course'];
        $lecturer = $row_data['lecturer'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./node_modules/bootstrap.min.css">
        <link rel="stylesheet" href="./node_modules/uikit.min.css">
    
    <style>
        .home-btn{text-align: center;}
        body{background: rgb(31, 80, 9);}
        main{margin:0 5%;}
        label{font-weight: bolder; font-size: large;}
    </style>

    <title>Edit Details</title>
</head>
<body>
    <main>
        <form action="./edit.php" method="post" name="row_update">
        <div  id="flex">
                <div class="form-group">
                    <label for="roll">Roll #</label>
                    <input type="text" value="<?= $index; ?>" class="form-control" id="roll" name="index">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control"  value="<?= $name; ?>" name="name">
                </div>
            </div>
            <div  id="flex">
                <div class="form-group">
                    <label for="program">Program</label>
                    <input type="text" value="<?= $program; ?>" class="form-control" id="program" name="program">
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <input type="number" class="form-control"  value="<?= $semester; ?>" id="semester" name="semester">
                </div>
            </div>
            </div>
            <div  id="flex">
                <div class="form-group">
                    <label for="mobile">Telephone</label>
                    <input type="tel"  class="form-control" value="<?= $mobile; ?>" id="mobile" name="mobile">
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date"  class="form-control" value="<?= $classDate; ?>" id="date" name="date">
                </div>
            </div>
            <div  id="flex">
                <div class="form-group">
                    <label for="lecturer">Lecturer</label>
                    <input type="text"  class="form-control" value="<?= $lecturer; ?>" name="lecturer">
                </div>
                <div class="form-group">
                    <label for="course">Favourite Course</label>
                    <input type="text" class="form-control"  value="<?= $course; ?>" id="course" name="course">
                </div>
            </div>
            <p/>
            <div class="form-group">
                <input type="hidden" name="id" value=" <?= $_GET['id']; ?>">
                <input type="submit" name="update" class="btn btn-primary" value="Update">
                <a href="./add.php"><input type="submit" name="update" class="btn btn-danger" value="Cancel"></a>
            </div>
    </form>
    </main>
    

    <footer style="background: #1A1110; color: white;" class="mt-5">
            <div class="row">
                <div class="col col-md-2"></div>
                <div style="display:flex; flex-direction:row;" class="col col-md-8">
                    <a href="https://github.com/john-BAPTIS?tab=repositories" target="_blank"><img style="border-radius: 50%; padding:50% 0" width="50px" height=}50ox" src="https://avatars.githubusercontent.com/u/71665600?v=4" alt="Logo"></a>
                    <p style=" padding:15px 0 15px 10px;">Copyright  © 2023 (Angel Development Team). <strong>Powered by: <a style="text-decoration:none" href="mailto:jamesakweter@gmail.com">Akweter</a></strong></p>
                </div>
                <div class="col col-md-2"></div>
            </div>
        </footer>
</body>
</html>
