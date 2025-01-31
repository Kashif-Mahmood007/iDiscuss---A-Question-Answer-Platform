
<!-- Sign up and login logic -->
<?php

$signup = false;            // for generating alert if not signup
$error = "";                // the error generating in case if not signup (may password or username wrong)
$loginError = false;        // for generating alert if not login 
$login = false;             // the error generating in case if not login (may password or username wrong)

if(isset($_SESSION["loggedin"])){
    $login = true;
}

require "components/_connect.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['signupBtn'])){
        $name = mysqli_real_escape_string($conn ,$_POST['name']);
        $name = str_replace("<", "&lt;", $name);            // securying our forum from XSS attack
        $name = str_replace(">", "&gt;", $name);
        
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $password = str_replace("<", "&lt;", $password);
        $password = str_replace(">", "&gt;", $password);
        
        $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
        $cpassword = str_replace("<", "&lt;",$cpassword);
        $cpassword = str_replace(">", "&gt;",$cpassword);


        $sql = "SELECT * FROM `signup` WHERE `name` = '$name'";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_num_rows($result);

        if($rows == 0){
            if($password == $cpassword){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `signup` (`name`, `password`, `datetime`) VALUES ('$name', '$hash', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                $signup = true;
            }

            else{
                $signup = false;
                $error = "The Password and Confirm Password must be same";
            }
        }

        else{
            $signup = false;
            $error = "The username already exits";
        }

    }

    if(isset($_POST['loginBtn'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $name = str_replace("<", "&lt;", $name);            // securying our forum from XSS attack
        $name = str_replace(">", "&gt;", $name);
        
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $password = str_replace("<", "&lt;", $password);    // securying our forum from XSS attack
        $password = str_replace(">", "&gt;", $password);

        $sql = "SELECT * FROM `signup` WHERE `name` = '$name'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);

        if($num == 1){
            while($row = mysqli_fetch_assoc($result)){
                if(password_verify($password, $row['password'])){
                    $login = true;
                    // session_start();         // i comment this , bcz session is already started on top
                    $_SESSION['loggedin'] = true;
                    $_SESSION['userid'] = $row['sno'];
                    $_SESSION['username'] = $name;
                }

                else{
                    $loginError = "The Password not matched, Try to login again";
                    $login = false;
                }
            }
        }
        
        else{
            $loginError = "The Username not found, Try another";
            $login = false;
        }


    }
}

?>




<?php

echo '
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container-fluid">
    <a class="navbar-brand" href="/">iDiscuss</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Top Catogories
        </a>
        <ul class="dropdown-menu">';
        
        $sql = "SELECT `category_name`,`category_id` from `categories` LIMIT 4";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            echo '<li><a class="dropdown-item" href="threadlist.php?catid='.$row['category_id'].'">'.$row['category_name'].'</a></li>';
        }

        echo '
        </ul>
        </li>
        <li class="nav-item">
        <a class="nav-link" href = "#">Contact</a>
        </li>
    </ul>';

    // search starts from here

    echo '
    <form class="d-flex" action = "search.php" method = "get">
        <input class="form-control me-2" type="search" name = "query" placeholder="Search" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
    </form>';

    if(!$login){
      echo '
      <button class="btn btn-outline-success mx-2 my-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
      <button class="btn btn-outline-success ml-2 my-2" data-bs-toggle="modal" data-bs-target="#signupModal">Signup</button>
      ';
    }
    if($login){
    echo '
      <a href = "logout.php" class = "text-decoration-none btn btn-outline-success mx-2">Logout</a>';
    }
  echo'
    </div>
</div>
</nav>
';


?>


<!-- Alert for error or successfully signup -->

<?php
if(isset($_POST['signupBtn'])){    
    if($signup){
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You account is created Sucessfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }

    else{
        echo '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Failure!</strong> '. $error. '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }
}

if(isset($_POST['loginBtn'])){    
    if($login){
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You are Logged in Successfully. Welcome '. $_SESSION['username'] .'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }

    else{
        echo '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Failure!</strong> '. $loginError. '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }
}

?>


<!-- Signup and login modal -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal</title>
</head>
<body>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <!-- form -->
        <form action = "<?php $_SERVER['REQUEST_URI']?> " method = "post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" aria-describedby="emailHelp" name = "name" placeholder="Enter Your Name">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name = "password" placeholder="Enter Your Password">
        </div>
        <button type="submit" class="btn btn-primary" name = "loginBtn">Submit</button>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- Signup Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Signup</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action = "<?php $_SERVER['REQUEST_URI']?>" method = "post">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" aria-describedby="emailHelp" name = "name" placeholder="Enter Your Name">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name = "password" placeholder="Enter Your Password">
            </div>
            <div class="mb-3">
                <label for="cpassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="cpassword" name = "cpassword" placeholder="Confirm Your Password">
            </div>
            <button type="submit" class="btn btn-primary"  name = "signupBtn">Create Account</button>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



</body>
</html>