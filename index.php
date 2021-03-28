<?php

session_start();

header('Cache-Control: no cache'); //no cache

$title = $category = $description = $picture = "";
$titleErr = $pictureErr = "";
$createresult = "";

$categories = array(
    "", "Clothing", "Shoes", "Electronics", "Books, movies, music and games",
    "Cosmetics and body care", "Bags and accessories", "Food and drinks",
    "Household appliances", "Furniture and household goods", "Sports and outdoor",
    "Toys and baby products", "Stationary and hobby supplies", "DIY, garden and pets"
);

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

include "db_connection.php";

$sql = "SELECT p.*, u.username, u.userpicture
        FROM tbl_posts p, tbl_users u
        WHERE p.user_id = u.user_id
        ORDER BY date_time DESC";

$result = mysqli_query($connection, $sql);

while ($row = mysqli_fetch_array($result)) {

    $reply = $row['post_id'];

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

if (isset($_POST['create']) && isset($_SESSION['user']) && $_SESSION['id'] == 201710117) {
    $error = 0;

    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    if (empty(test_input($title))) {
        $titleErr = "Title is required.";
        $title = test_input($title);
        $error++;
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
        $pictureErr = "Upload image file only.";
        $error++;
    } else {
        $picture =  str_replace(' ', '_', $picture);
    }
    if ($error == 0) {
        
        date_default_timezone_set('Asia/Hong_Kong');
        $date = date("Y-m-d H:i:s");

        $sql = "INSERT INTO tbl_posts(user_id, date_time, title, category, description, picture) 
                VALUES ('$user_id','$date','$title', '$category', '$description', '$picture')";

        $result = mysqli_query($connection, $sql);

        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'], "Picture/" . $picture);

        if ($result) {
            header("location: index.php");
        } else {
            $createresult = "<p id='myMsg' class='alert alert-danger mt-3'>Failed to create the thread.</p>";
        }
    }

    mysqli_close($connection);
} else if (isset($_POST['create'])){
    echo "<script>alert('Login to create thread')</script>";
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
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/index.css">
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
            <form class="form col-xl-7 mx-lg-5 p-0">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
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
        <?php 
            if(isset($_SESSION['user']))
            echo '<div class="row h3 p-3">Welcome, '.$user["username"].'! </div>';
            else
            echo '<div class="row h3 p-3">Login or Sign up now!</div>';
        ?>
        
        <div class="row mb-4">
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="shadow-lg p-2 bg-danger text-white font-weight-bold">Create a new thread</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="createpost shadow-lg ">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                <div class="input-group">
                                    <input type="text" name="title" class="form-control xl-2" value="<?php echo $title; ?>" placeholder="Title" required>
                                </div>
                                <span class="text-danger"><?php echo $titleErr; ?></span>

                                <textarea name="description" class="form-control mt-2" placeholder="Description" rows="4"><?php echo $description; ?></textarea>

                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupFileAddon01">Category</span>
                                    </div>
                                    <select name="category" class="form-control" id="exampleFormControlSelect1">
                                        <option <?php if (isset($category) && $category == "1") echo "selected"; ?> value="1">Clothing</option>
                                        <option <?php if (isset($category) && $category == "2") echo "selected"; ?> value="2">Shoes</option>
                                        <option <?php if (isset($category) && $category == "3") echo "selected"; ?> value="3">Electronics</option>
                                        <option <?php if (isset($category) && $category == "4") echo "selected"; ?> value="4">Books, movies, music & games</option>
                                        <option <?php if (isset($category) && $category == "5") echo "selected"; ?> value="5">Cosmetics & body care</option>
                                        <option <?php if (isset($category) && $category == "6") echo "selected"; ?> value="6">Bags & accessories</option>
                                        <option <?php if (isset($category) && $category == "7") echo "selected"; ?> value="7">Food & drinks</option>
                                        <option <?php if (isset($category) && $category == "8") echo "selected"; ?> value="8">Household appliances</option>
                                        <option <?php if (isset($category) && $category == "9") echo "selected"; ?> value="9">Furniture & household goods</option>
                                        <option <?php if (isset($category) && $category == "10") echo "selected"; ?> value="10">Sports & outdoor</option>
                                        <option <?php if (isset($category) && $category == "11") echo "selected"; ?> value="11">Toys & baby products</option>
                                        <option <?php if (isset($category) && $category == "12") echo "selected"; ?> value="12">Stationary & hobby supplies</option>
                                        <option <?php if (isset($category) && $category == "13") echo "selected"; ?> value="13">DIY, garden & pets</option>
                                    </select>
                                </div>
                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupFileAddon01"><i class="fas fa-image"></i></span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="inputGroupFile01">Picture(optional)</label>
                                    </div>
                                </div>
                                <span class="text-danger" required><?php echo $pictureErr; ?></span>
                                <script>
                                    // Add the following code if you want the name of the file appear on select
                                    $(".custom-file-input").on("change", function() {
                                        var fileName = $(this).val().split("\\").pop();
                                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                                    });
                                </script>

                                <div class="text-right">
                                    <button type="submit" name="create" class="btn btn-outline-danger mt-2 col-5 col-sm-4">Create</button>
                                </div>

                                <?php echo $createresult; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <div class="shadow-lg p-2 bg-danger text-white font-weight-bold">Category</div>
                    </div>
                </div>

                <?php
                include "db_connection.php";
                for ($i = 1; $i < 14; $i++) {
                    echo    "<div class='row no-gutters'>";
                    echo        "<div class='col-8 col-lg-9 category shadow-lg border-bottom border-secondary p-2'>";
                    echo                "<a class='text-dark' href='category.php?category=$i&categoryname=$categories[$i]'>$categories[$i]</a>";
                    echo        "</div>";
                    echo        "<div class='col-4 col-lg-3 category shadow-lg border-bottom border-secondary p-2'>";

                    $sql = "SELECT * 
                    FROM tbl_posts 
                    WHERE category = $i";

                    $result = mysqli_query($connection, $sql);

                    if ($result) {
                        // it return number of rows in the table. 
                        $row = mysqli_num_rows($result);

                        echo "Threads: " . $row;

                        // close the result. 
                        mysqli_free_result($result);
                    }
                    echo        "</div>";
                    echo    "</div>";
                }

                $sql = "SELECT p.*, u.username, u.userpicture
                        FROM tbl_posts p, tbl_users u
                        WHERE p.user_id = u.user_id
                        ORDER BY date_time DESC";

                $result = mysqli_query($connection, $sql);

                while ($row = mysqli_fetch_array($result)) {

                    echo "<div class='row'>";
                    echo     "<div class='col'>";
                    echo         "<div class='card shadow-lg p-2 bg-light text-dark mt-4'>Posted: " . date_format(new DateTime($row['date_time']), 'm/d/Y l g:ia') . "</div>";
                    echo     "</div>";
                    echo "</div>";
                    echo "<div class='card-group'>";
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
                            WHERE r.post_id = " . $row['post_id'] . "";

                    $result2 = mysqli_query($connection, $sql2);

                    if ($result2) {
                        // it return number of rows in the table. 
                        $numrows = mysqli_num_rows($result2);

                        echo "<h5>Reply: " . $numrows . "</h5>";

                        // close the result. 
                        mysqli_free_result($result2);
                    }
                    echo                    "<button type='submit' name='" . $row['post_id'] . "' class='btn btn-outline-danger col-5 col-sm-4'>View replies</button>";
                    echo                "</div>";
                    echo            "</form>";
                    echo         "</div>";
                    echo     "</div>";
                    echo "</div>";
                }
                mysqli_close($connection);
                ?>
            </div>

            <div class="col-4 d-none d-md-block">
                <div class="row">
                    <div class="col">
                        <div class="shadow-lg p-2 bg-danger text-white font-weight-bold">Trending</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <?php
                        include "db_connection.php";

                        $sql = "SELECT p.*,u.username,u.userpicture,r.post_id,COUNT(*) AS cnt 
                                FROM tbl_replies r, tbl_posts p, tbl_users u
                                WHERE p.post_id = r.post_id
                                AND p.user_id = u.user_id
                                GROUP BY r.post_id 
                                ORDER BY cnt 
                                DESC LIMIT 5";

                        $result = mysqli_query($connection, $sql);

                        while ($row = mysqli_fetch_array($result)) {
                            $trendpost = $row['title'];
                            $trendid = $row['post_id'];

                            echo    "<div class='row no-gutters'>";
                            echo        "<div class='col category shadow-lg border-bottom border-secondary p-2'>";
                            echo             "<a class='text-dark' href='trends.php?post_id=$trendid'>$trendpost</a>";
                            echo        "</div>";
                            echo    "</div>";
                        }
                        if (!isset($_SESSION['user']) || $user['userlevel'] == "regular") {
                            echo '<div class="shadow-lg mt-4">';
                            echo    '<img src="https://chainlinkmarketing-closetheloopadve.netdna-ssl.com/wp-content/uploads/2019/07/Google-Display-Ad-Example-ActiveCampaign.jpg" alt="Google Display Ad Example ActiveCampaign" title="Google Display Ad Example ActiveCampaign">';
                            echo '</div>';
                        }
                        mysqli_close($connection);
                        ?>
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
            </div>
        </div>    
    </div>
    
</body>

</html>