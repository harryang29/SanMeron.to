<?php
session_start();

if (!(isset($_SESSION['user']) && $_SESSION['id'] == 201710117)) {
    header("location: logout.php");
    exit();
}

$user = $_SESSION['user'];

$username = $userpicture = $password = $newpassword = $oldpassword = $conpassword = "";
$usernameErr = $userpictureErr = $passwordErr = $newpasswordErr = $oldpasswordErr = $conpasswordErr = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['save'])) {
    $error = 0;
    

    $username = $_POST['username'];
    $password = MD5($_POST['password']);

    $userpicture = $_FILES['file']['name'];
    $target_file = "Userpicture/" . basename($_FILES["file"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // if pass validation 

    include "db_connection.php";

    // successful connection

    //check if username is taken
    $sql = "SELECT username
	        FROM tbl_users
            WHERE username = '$username'";

    $result = mysqli_query($connection, $sql);

    if (empty($username)) {
        $usernameErr = "Username is required.";
        $error++;
    } else if ($username == $user['username']) {
        $username = test_input($username);
    } else if (mysqli_num_rows($result) != 0) {
        $usernameErr = "Username is taken.";
        $error++;
    } else {
        $username = test_input($username);
    }

    $sql = "SELECT password
	        FROM tbl_users
            WHERE user_id = '" . $user['user_id'] . "'
            AND password = '$password'";

    $result = mysqli_query($connection, $sql);

    if (empty($password)) {
        $passwordErr = "Password is required.";
        $error++;
    } else if (mysqli_num_rows($result) == 0) {
        $passwordErr = "Password is incorrect.";
        $error++;
    } else {
        
    }
    
    if (empty($userpicture)){
        
    } else if (!in_array($imageFileType, $extensions_arr)) {
        $userpictureErr = "Upload image file only.";
        $error++;
    } else {
        $userpicture = str_replace(' ', '_', $userpicture);;
    }

    if ($error == 0 && empty($userpicture)) {

        $sql = "UPDATE tbl_users
                SET username = '$username'
				WHERE user_id = '" . $user['user_id'] . "' ";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            
            $newuser = array(
            "user_id" => $user['user_id'],
            "userlevel" => $user['userlevel'],
            "username" => $username,
            "email" => $user['email'],
            "userpicture" => $user['userpicture']
            );
        
            $_SESSION['user'] = $newuser;  
            header("Refresh:0");
            echo '<script>alert("Save changes succuessful.")</script>';
            
    
        } else {
            echo '<script>alert("Failed to save changes.")</script>';
        }
    } else if ($error == 0) {

        $sql = "UPDATE tbl_users
                SET username = '$username',
                    userpicture = '$userpicture'
				WHERE user_id = '" . $user['user_id'] . "' ";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            move_uploaded_file($_FILES['file']['tmp_name'], "Userpicture/" . $userpicture);
            $newuser = array(
            "user_id" => $user['user_id'],
            "userlevel" => $user['userlevel'],
            "username" => $username,
            "email" => $user['email'],
            "userpicture" => $userpicture
            );
            $_SESSION['user'] = $newuser;
            
            header("Refresh:0");
            echo '<script>alert("Save changes succuessful.")</script>';
            
    
        } else {
            echo '<script>alert("Failed to save changes.")</script>';
        }
    }
    mysqli_close($connection);
}

if (isset($_POST['save2'])) {
    $error = 0;
    
    include "db_connection.php";
    
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];
    $conpassword = $_POST['conpassword'];

    $md5password = MD5($oldpassword);

    $sql = "SELECT password
	        FROM tbl_users
            WHERE user_id = '" . $user['user_id'] . "'
            AND password = '$md5password'";

    $result = mysqli_query($connection, $sql);

    if (empty($oldpassword)) {
        $oldpasswordErr = "Old password is required.";
        $error++;
    } else if (mysqli_num_rows($result) == 0) {
        $oldpasswordErr = "Old password is incorrect.";
        $error++;
    } 
    
    if (empty($newpassword)) {
        $newpasswordErr = "New password is required.";
        $error++;
    } else if (strlen($newpassword) < 8) {
        $newpasswordErr = "New password must be at least 8 characters in length.";
        $error++; 
    }
    
    if (empty($conpassword)) {
        $conpasswordErr = "Confirm password is required.";
        $error++;
    } else if ($newpassword != $conpassword) {
        $conpasswordErr = "Password does not match.";
        $error++; 
    }
    
    if ($error == 0) {
        $md5password = MD5($newpassword);
        $sql = "UPDATE tbl_users
                SET password = '$md5password'
				WHERE user_id = '" . $user['user_id'] . "' ";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            header("Refresh:0");
            echo '<script>alert("Password changed.")</script>';
    
        } else {
            echo '<script>alert("Failed to change password.")</script>';
        }
    } 
    mysqli_close($connection);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/index.css">
    <title>SANMERON.TO | Edit profile</title>
</head>

<body>
    <!-- NAVIGATION BAR-->
    <nav class="navbar navbar-expand-md navbar-light bg-white sticky-top">
        <a href="index.php" class="navbar-brand">
            <img class="logo img-responsive rounded-circle mr-2" src="Images\logo.png" alt="">
            SANMERON.TO
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="form col-xl-8">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            </form>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Forum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Message</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Notification</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="shadow-lg p-2 bg-danger text-white font-weight-bold">Edit profile</div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="createpost shadow-lg ">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

                        <div class="form-group row">
                            <label for="exampleFormControlInput1" class="col-md-3 col-form-label">Username</label>
                            <div class="col-md-9">
                                <input type="text" name="username" class="form-control" value="<?php echo $user['username'] ?>" placeholder="Username" required>
                            </div>
                        </div>
                        <span class="text-danger"><?php echo $usernameErr; ?></span>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-3 col-form-label">Email</label>
                            <div class="col-9">
                                <input type="text" name="email" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $user['email'] ?>">
                            </div>
                        </div>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01"><i class="far fa-id-card"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupFile01">Profile picture(optional)</label>
                            </div>
                        </div>
                        <span class="text-danger" required><?php echo $userpictureErr; ?></span>
                        <script>
                            // Add the following code if you want the name of the file appear on select
                            $(".custom-file-input").on("change", function() {
                                var fileName = $(this).val().split("\\").pop();
                                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                            });
                        </script>

                        <div class="form-group row mt-3">
                            <label for="inputPassword" class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" required>
                            </div>
                        </div>
                        <span class="text-danger"><?php echo $passwordErr; ?></span>
                        <div class="text-right">
                            <button type="submit" name="save" class="btn btn-outline-danger mt-2 col-5 col-sm-4">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="shadow-lg p-2 bg-danger text-white font-weight-bold">Change password</div>
            </div>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="createpost shadow-lg ">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="inputPassword" class="col-md-3 col-form-label">Old Password</label>
                            <div class="col-md-9">
                                <input type="password" name="oldpassword" value="<?php echo $oldpassword ?>" class="form-control" id="inputPassword" placeholder="Password" required>
                            </div>
                        </div>
                        <span class="text-danger"><?php echo $oldpasswordErr; ?></span>
    
                        <div class="form-group row">
                            <label for="inputPassword" class="col-md-3 col-form-label">New Password</label>
                            <div class="col-md-9">
                                <input type="password" name="newpassword" value="<?php echo $newpassword ?>" class="form-control" id="inputPassword" placeholder="Password" required>
                            </div>
                        </div>
                        <span class="text-danger"><?php echo $newpasswordErr; ?></span>
    
                        <div class="form-group row">
                            <label for="inputPassword" class="col-md-3 col-form-label">Confirm password</label>
                            <div class="col-md-9">
                                <input type="password" name="conpassword" value="<?php echo $conpassword ?>" class="form-control" id="inputPassword" placeholder="Password" required>
                            </div>
                        </div>
                        <span class="text-danger"><?php echo $conpasswordErr; ?></span>
    
                        <div class="text-right">
                            <button type="submit" name="save2" class="btn btn-outline-danger mt-2 col-5 col-sm-4">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>