<?php

session_start();

include "db_connection.php";

if (isset($_GET['post_id'])) {
    $sql = "SELECT p.*, u.username, u.userpicture
            FROM tbl_posts p, tbl_users u
            WHERE p.user_id = u.user_id
            AND post_id = ".$_GET['post_id']."";

    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($result);

    $mypost = array(
        "post_id" => $row['post_id'],
        "date_time" => $row['date_time'],
        "username" => $row['username'],
        "userpicture" => $row['userpicture'],
        "title" => $row['title'],
        "category" => $row['category'],
        "picture" => $row['picture'],
        "description" => $row['description']
    );
    $_SESSION['mypost'] = $mypost;
}

$post = $_SESSION['mypost'];
$post_id = $post['post_id'];

$replycontent = $picture = "";
$replycontentErr = $pictureErr = "";
$replyresult = "";

if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
    $user_id = $user['user_id'];
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['reply']) && isset($_SESSION['user']) && $_SESSION['id'] == 201710117) {
    $error = 0;

    $replycontent = $_POST['replycontent'];

    if (empty(test_input($replycontent))) {
        $replycontentErr = "<script>alert('Content is required')</script>
                       <script>window.history.go(-1);</script>";
        $replycontent = test_input($replycontent);
        $error++;
    } else {
        $replycontent = test_input($replycontent);
        $replycontentErr = "";
    }

    $picture = $_FILES['file']['name'];

    $target_file = "Picture/" . basename($_FILES["file"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // if pass validation 

    include "db_connection.php";

    // successful connection
    if (empty($picture)) {
    } else if (!in_array($imageFileType, $extensions_arr)) {
        $pictureErr = "<script>alert('Upload image file only.')</script>
                       <script>window.history.go(-1);</script>";
        $error++;
    } else {
        $picture =  str_replace(' ', '_', $picture);
    }
    if ($error == 0) {
        date_default_timezone_set('Asia/Hong_Kong');
        $date = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO tbl_replies(user_id, post_id, date_time,replycontent, replypicture) 
                VALUES ('$user_id', '$post_id', '$date','$replycontent', '$picture')";

        $result = mysqli_query($connection, $sql);

        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'], "Picture/" . $picture);

        if ($result) {
            $replycontent = $picture = "";
            $replycontentErr = $pictureErr = "";
            header("Refresh:0");
            echo "<script>
             window.history.go(-1);
            </script>";
        } else {
            $replyresult = "<p id='myMsg' class='alert alert-danger mt-3'>Failed to reply.</p>";
        }
    }
    mysqli_close($connection);
} else if (isset($_POST['reply'])){
    header("Refresh:0");
    echo "<script>alert('Login first to reply')</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        function timedMsg() {
            var t = setTimeout("document.getElementById('myMsg').style.display='none';", 3000);
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("button").click(function() {
                $("p").toggle(500);
            });
        });
    </script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/category.css">
    <title>SANMERON.TO | Forum</title>
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
                <input class="form-control mr-sm-2 mb-0" type="search" placeholder="Search" aria-label="Search">
            </form>
            <?php
            if (!(isset($_SESSION['user']) && $_SESSION['id'] == 201710117)) {
                echo '<a href="mylogin.php" class="btn btn-outline-danger col-4 col-sm-2 mr-2 mx-md-2 mt-2 mt-md-0" role="button" aria-pressed="true">Login</a>';
                echo '<a href="myregister.php" class="btn btn-danger col-4 col-sm-2 mt-2 mt-md-0" role="button" aria-pressed="true">Sign up</a>';
            } else{
            echo'
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="profile.php">Profile</a>
                </li>
                <li class="nav-item active">
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
            ';
            }
            
            ?>
        </div>
    </nav>
    <div class="container">
        <div class="row h1 p-3"></div>
        <div class="row mb-4">
            <?php
            if(!isset($_SESSION['user']))
                echo '<div class="col-sm-12 col-md-8">';
            else if ($user['userlevel'] == "premium")
                echo '<div class="col-12">';
            else
                echo '<div class="col-sm-12 col-md-8">';
            ?>
            <div class="row">
                <div class="col">
                    <div class="card shadow-lg p-2 bg-light text-dark">Posted: <?php echo date_format(new DateTime($post['date_time']), 'm/d/Y l g:ia') ?> </div>
                </div>
            </div>
            <div class='card-group mb-4'>
                <div class='card mb-0 col-sm-2 p-2'>
                    <div class='card-block text-center'>
                        <img class='userpicture img-fluid' src=Userpicture/<?php echo $post['userpicture'] ?>> <div class='medium'><strong> <?php echo $post['username'] ?> </strong></div>
                </div>
            </div>
            <div class='card col-sm-10 p-3'>
                <div class='card-block'>
                    <div class='border-bottom border-secondary'>
                        <h4><?php echo $post['title'] ?></h4>
                        <?php
                        if (!empty($post['picture'])) {
                            echo "<img class='picture mb-3 img-fluid' src=Picture/" . $post['picture'] . ">";
                        }
                        ?>
                    </div>
                    <pre> <?php echo $post['description'] ?> </pre>
                    <div class='text-right'>
                        <button class='btn btn-outline-danger col-5 col-sm-4' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
                            Reply
                        </button>
                    </div>
                    <div class="collapse" id="collapseExample">
                        <form autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                            <textarea name='replycontent' class='form-control mt-2' placeholder='Content' rows='4' required><?php echo $replycontent; ?></textarea>
                            <span class="text-danger"><?php echo $replycontentErr; ?></span>
                            <div class="input-group mt-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupFileAddon01"><i class="fas fa-image"></i></span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile01">Picture(optional)</label>
                                </div>
                            </div>
                            <span class="text-danger"><?php echo $pictureErr; ?></span>
                            <script>
                                // Add the following code if you want the name of the file appear on select
                                $(".custom-file-input").on("change", function() {
                                    var fileName = $(this).val().split("\\").pop();
                                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                                });
                            </script>
                            <div class="text-right">
                                <button type="submit" name="reply" class="btn btn-outline-danger mt-2 col-5 col-sm-4">Send</button>
                            </div>
                            <?php echo $replyresult; ?>
                            <script language="JavaScript" type="text/javascript">
                                timedMsg()
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php

        include "db_connection.php";

        $sql = "SELECT r.*, u.username, u.userpicture
            FROM tbl_users u, tbl_replies r
            WHERE r.post_id = $post[post_id]
            AND r.user_id = u.user_id
            ORDER BY date_time ASC";

        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_array($result)) {

            echo "<div class='row'>";
            echo "<div class='col'>";
            echo "<div class='card shadow-lg p-2 bg-secondary text-white'>Replied: " . date_format(new DateTime($row['date_time']), 'm/d/Y l g:ia') . "</div>";
            echo "</div>";
            echo "</div>";
            echo "<div class='card-group mb-4'>";
            echo "<div class='card mb-0 col-sm-2 p-2'>";
            echo "<div class='card-block text-center'>";
            echo "<img class='userpicture img-fluid' src=Userpicture/" . $row['userpicture'] . ">";
            echo "<div class='medium'><strong>" . $row['username'] . "</strong></div>";
            echo "</div>";
            echo "</div>";
            echo "<div class='card col-sm-10 p-3'>";
            echo "<div class='card-block'>";
            echo "<div class='border-bottom border-secondary'>";
            if (!empty($row['replypicture'])) {
                echo "<img class='picture mb-3 img-fluid' src=Picture/" . $row['replypicture'] . ">";
            }
            echo "</div>";
            echo "<pre>" . $row['replycontent'] . "</pre>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        mysqli_close($connection);
        ?>
    </div>

    <?php
    if(!isset($_SESSION['user']))
        echo '<div class="col-4 d-none d-md-block">';
    else if ($user['userlevel'] == "premium")
        echo '<div class="col-12 d-none">';
    else
        echo '<div class="col-4 d-none d-md-block">';
    ?>
    <div class="shadow-lg">
        <img src="https://chainlinkmarketing-closetheloopadve.netdna-ssl.com/wp-content/uploads/2019/07/Google-Display-Ad-Example-ActiveCampaign.jpg" alt="Google Display Ad Example ActiveCampaign" title="Google Display Ad Example ActiveCampaign">
    </div>
    </div>
    </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>