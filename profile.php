<?php
session_start();

if (!(isset($_SESSION['user']) && $_SESSION['id'] == 201710117)) {
    header("location: logout.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['user_id'];

include "db_connection.php";

$sql = "SELECT p.*, u.username, u.userpicture
        FROM tbl_posts p, tbl_users u
        WHERE p.user_id = u.user_id
        AND p.user_id = $user_id
        ORDER BY date_time DESC";

$result = mysqli_query($connection, $sql);

while ($row = mysqli_fetch_array($result)) {

    $reply = $row['post_id']."v";

    if (isset($_POST[$reply])) {
        $post = array(
            "post_id" => $row['post_id'],
            "date_time" => $row['date_time'],
            "username" => $row['username'],
            "userpicture" => $row['userpicture'],
            "title" => $row['title'],
            "category" => $row['category'],
            "picture" => $row['picture'],
            "description" => $row['description']
        );

        $_SESSION['post'] = $post;

        mysqli_close($connection);

        header("location: post.php");
    }
}
mysqli_free_result($result);
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
    <link rel="stylesheet" href="CSS/category.css">
    <title>SANMERON.TO | Profile</title>
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
    
    <div class="container-fluid bg-danger text-white p-4 mb-4">
        <div class="row ">
            <div class="col-6 col-md-3 col-lg-2 text-right">
                <?php echo "<img class='profilepicture img-fluid bg-danger' src='Userpicture/". $user['userpicture']."'>" ?>
            </div>
            <div class="col-6 col-md-9 col-lg-10 mt-5">
                <h3 class="font-weight-light" style="line-height:1em"> <?php echo $user['username'] ?></h3>
                <a href="edit_profile.php" class="text-white">
                    <i class="fa-sm fas fa-pencil-alt" aria-hidden="true"></i> 
                    Edit Profile 
                </a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row mb-4">
                <?php
                if($user['userlevel'] == "premium")
                    echo '<div class="col-12">';
                else
                    echo '<div class="col-sm-12 col-md-8">';

                $sql = "SELECT p.*, u.username, u.userpicture
                        FROM tbl_posts p, tbl_users u
                        WHERE p.user_id = u.user_id
                        AND p.user_id = $user_id
                        ORDER BY date_time DESC";

                $result = mysqli_query($connection, $sql);

                while ($row = mysqli_fetch_array($result)) {

                    echo "<div class='row'>";
                    echo     "<div class='col'>";
                    echo         "<div class='shadow-lg p-2 bg-light text-dark'>Posted: ";
                    echo             date_format(new DateTime($row['date_time']), 'm/d/Y l g:ia'); 
                    echo         "</div>";
                    echo     "</div>";
                    echo "</div>";
                    echo "<div class='card-group mb-4'>";
                    echo     "<div class='card mb-0 col-sm-2 p-2'>";
                    echo         "<div class='card-block text-center'>";
                    echo             "<img class='userpicture img-fluid' src=Userpicture/" . $row['userpicture'] . ">";
                    echo             "<div class='medium'><strong>" . $row['username'] . "</strong></div>";
                    echo         "</div>";
                    echo     "</div>";
                    echo     "<div class='card col-sm-10 p-3'>";
                    echo         "<div class='card-block'>";
                    echo            "<div class='border-bottom border-secondary'>";
                    echo                "<h4>" . $row['title'] . "</h4>";
                    if (!empty($row['picture'])) {
                        echo "<img class='picture mb-3 img-fluid' src=Picture/" . $row['picture'] . ">";
                    }
                    echo            "</div>";
                    echo            "<pre>" . $row['description'] . "</pre>";
                    echo            "<form method='post'>";
                    echo                "<div class='text-right'>";

                    $sql2 = "SELECT r.*
                            FROM tbl_replies r
                            WHERE r.post_id = ".$row['post_id']."";

                    $result2 = mysqli_query($connection, $sql2);

                    if ($result2) {
                        // it return number of rows in the table. 
                        $numrows = mysqli_num_rows($result2);

                        echo "<h5>Reply: " . $numrows . "</h5>";

                        // close the result. 
                        mysqli_free_result($result2);
                    }
                    
                    echo                    "<button type='submit' name='" . $row['post_id'] . "v' class='btn btn-outline-danger col-5 col-sm-4'>View replies</button>";
                    echo                "</div>";
                    echo            "</form>";
                    echo         "</div>";
                    echo     "</div>";
                    echo "</div>";
                    
                }
                mysqli_close($connection);
                ?>
            </div>
            
            <?php
                if($user['userlevel'] == "premium")
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