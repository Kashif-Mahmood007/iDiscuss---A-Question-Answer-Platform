<?php

session_start();

if(isset($_POST['logoutBtn'])){
    header("location: index.php");
    session_unset();
    session_destroy();
}

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>
  <body>

    <?php
        $login = false;

        if(isset($_SESSION['loggedin'])){
            $login = true;
        }

        require "components/_navbar.php";
    ?>

    <div class="container my-5">
        <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Welcome - <?php echo $_SESSION['username'];?></h4>
        <p>You are Successfully logged Out. You have to logged in for Continue on Website</p>
        <p class="mb-0">You have to Signup for new account and have to login in any of account using Name and Password</p>
        <hr>
        <p class="mb-0">Click on login button to login</p>
        <form action="logout.php" method="post">
            <input type="submit" value="Logout" class = "mt-3 mb-2 btn btn-outline-dark" name = "logoutBtn">
        </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>