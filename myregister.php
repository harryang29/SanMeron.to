<?php

// define variables and set to empty values
$username = $password = $confirmpass = $email = $gov_id = "";
$usernameErr = $passwordErr = $confirmpassErr = $emailErr = $gov_idErr = "";
$registerresult = "";
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// REGISTRATION
if (isset($_POST['register'])) {
    $error = 0;

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmpass = $_POST['confirmpass'];
    $email = $_POST['email'];

    $gov_id = $_FILES['file']['name'];
    $target_file = "Gov_id/" . basename($_FILES["file"]["name"]);

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
    } else if (mysqli_num_rows($result) != 0) {
        $usernameErr = "Username is taken.";
        $error++;
    } else {
        $username = test_input($username);
    }

    if (empty($password)) {
        $passwordErr = "Password is required.";
        $error++;
    } else if (strlen($password) < 8) {
        $passwordErr = "Password must be at least 8 characters in length.";
        $error++;
    }

    if (empty($confirmpass)) {
        $confirmpassErr = "Confirm password is required.";
        $error++;
    } else if ($password != $confirmpass) {
        $confirmpassErr = "Password not match.";
        $error++;
    }

    //check if email is taken
    $sql = "SELECT email
	        FROM tbl_users
			WHERE email = '$email'";

    $result = mysqli_query($connection, $sql);

    if (empty($email)) {
        $emailErr = "Email is required.";
        $error++;
    } else if (mysqli_num_rows($result) != 0) {
        $emailErr = "Email is already used.";
        $error++;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format.";
        $error++;
    } else {
        $email = test_input($email);
    }

    if (empty($gov_id)) {
        $gov_idErr = "Government ID is required.";
        $error++;
    } elseif (!in_array($imageFileType, $extensions_arr)) {
        $gov_idErr = "Upload image file only.";
        $error++;
    }else{
        $gov_id = str_replace(' ', '_', $gov_id);;
    }

    if ($error == 0) {
        // MD5
        $MD5password = MD5($password);

        $sql = "INSERT INTO tbl_users(username, password, email, gov_id)
				VALUES('$username', '$MD5password', '$email', '$gov_id')";

        $result = mysqli_query($connection, $sql);

        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'], "Gov_id/" . $gov_id);

        if ($result) {
            echo "<script>alert('Your account is registered.')</script>";
            echo '<script>window.location="mylogin.php"</script>';
        } else {
            $registerresult = "<script>alert('Your account failed to register.')</script>";
        }
    }
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>SANMERON.TO | Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/myregister.css">
</head>

<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="Images/logo.png" class="brand_logo" alt="Logo">
                    </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="username" class="form-control input_user" value="<?php echo $username; ?>" placeholder="Username" required>
                        </div>
                        <span class="text-danger"><?php echo $usernameErr; ?></span>

                        <div class="input-group mt-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control input_pass" value="<?php echo $password; ?>" placeholder="Password" required>
                        </div>
                        <span class="text-danger"><?php echo $passwordErr; ?></span>

                        <div class="input-group mt-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="confirmpass" class="form-control input_pass" value="<?php echo $confirmpass; ?>" placeholder="Confirm Password" required>
                        </div>
                        <span class="text-danger"><?php echo $confirmpassErr; ?></span>

                        <div class="input-group mt-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" name="email" class="form-control input_user" value="<?php echo $email; ?>" placeholder="Email" required>
                        </div>
                        <span class="text-danger"><?php echo $emailErr; ?></span>

                        <div class="input-group mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01"><i class="far fa-id-card"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" required>
                                <label class="custom-file-label" for="inputGroupFile01">Upload government ID</label>
                            </div>
                        </div>
                        <span class="text-danger"><?php echo $gov_idErr; ?></span>
                        <script>
                            // Add the following code if you want the name of the file appear on select
                            $(".custom-file-input").on("change", function() {
                                var fileName = $(this).val().split("\\").pop();
                                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                            });
                        </script>

                        <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" name="register" class="btn login_btn">Register</button>
                        </div>
                    </form>
                </div>
                <div class="d-flex justify-content-center links">
                    Already have an account? <a href="mylogin.php">Login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>