<?php

$username = $loginresult = "";

// LOGIN
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = MD5($_POST['password']);



    //if pass validation 

    include "db_connection.php";

    // successful connection

    $sql = "SELECT *
            FROM tbl_users
            WHERE username = '$username'
            AND password = '$password'";

    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) == 0) {
        $loginresult = "<p id='myMsg' class='alert alert-danger mt-3'>Invalid username or password.</p>";
    } else {
        $loginresult = "<p id='myMsg' class='alert alert-success mt-3'>.</p>";
        $row = mysqli_fetch_array($result);
        session_start();
        $user = array(
            "user_id" => $row['user_id'],
            "userlevel" => $row['userlevel'],
            "username" => $row['username'],
            "userpicture" => $row['userpicture'],
            "email" => $row['email'],
            "gov_id" => $row['gov_id']
        );
        $_SESSION['user'] = $user;
        $_SESSION['id'] = 201710117;

        mysqli_close($connection);
        header("location: index.php");
        exit;
    }
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>SANMERON.TO | Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/mylogin.css">
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
                    <form method="post">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="username" class="form-control input_user" value="<?php echo $username; ?>" placeholder="Username">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control input_pass" placeholder="Password">
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input" id="customControlInline">
                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" name="login" class="btn login_btn">Login</button>
                        </div>
                        <?php echo $loginresult; ?>
                    </form>
                </div>
                <div class="d-flex justify-content-center links">
                    Don't have an account? <a href="myregister.php" class="ml-2">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>