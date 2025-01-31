<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Threads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">      
</head>

<body>

    <?php
    session_start();
    $login = false;

    if(isset($_SESSION['loggedin'])){
        $login = true;
    }

    require "components/_navbar.php";
    require "components/_connect.php";
?>

    <!-- Used as an alternative of Jumbotron in Bootstrap 5 -->

<?php
    $thread_id = $_GET['threadid'];
    
    // code for stoting the record in the database
    
    $comment = false;
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['commentBtn'])){
            $content = mysqli_real_escape_string($conn, $_POST['comment']);
            $content = str_replace(">", "&gt;", $content);          // securying our forum from XSS attack
            $content = str_replace("<", "&lt;", $content);
            $userid = $_SESSION['userid'];

            $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `user_id`, `datetime`) VALUES ('$content', '$thread_id', '$userid', current_timestamp())";

            $result = mysqli_query($conn, $sql);
            if($result){
                echo '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your Comment is Submitted Sucessfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
            }
            else{
                echo '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your Comment is not Submitted due to some technical issue.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
            }
        }
    }



    // code for fetching the records from categories corresponding specific ID


    require "components/_connect.php";
    $sql = "SELECT * FROM `threads` WHERE `thread_id` = '$thread_id'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if($num > 0){
        $noQuestions = false;
        while($rows = mysqli_fetch_assoc($result)){
            $title = $rows['thread_title'];
            $desc = $rows['thread_desc'];
            
            echo '
            <div class="container">
                <div class="mt-4 p-5 bg-light text-dark rounded">
                    <h1 class = "pb-4">'.$title.'</h1>
                    <p>'.$desc.'</p>
                    
                    <p class = "mt-4">This is peer to peer coding discussion forum. No Spam / Advertising / Self-promote in the forums.  Do not post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post questions. Remain respectful of other members at all times
                    </p>
                    <button class="btn btn-outline-dark mt-3" data-bs-toggle="modal" data-bs-target="#CommentModal">Comment</button>

               </div>
            </div>';
        }
    }  

?>

<div class="container my-5">
    <h1>Start a Discussion</h1>
</div>

<div id="bottom">

<?php

// Here, thread_id is the id of thread passed in URL by clicking on the visit thread


$noQuestions = true;
$sql = "SELECT * FROM `comments` WHERE `thread_id` = '$thread_id'";         
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);

if($num > 0){
    $noQuestions = false;
    while($row = mysqli_fetch_assoc($result)){
        $comment_id = $row['comment_id'];
        $content = $row['comment_content'];
        $time = $row['datetime'];
        $userid = $row['user_id'];


        $sql2 = "SELECT `name` FROM `signup` WHERE `sno` = '$userid'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);

        echo ' <div class="d-flex my-4 container">
                    <div class="flex-shrink-0">
                        <img src="images/profile.png" height="55px" width="55px" alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class = "my-2">Posted by <strong>'.$row2['name'].'</strong> at <strong>'.$time.'</strong></p>
                        <p class = "my-2">'.$content.'</p>
                    </div>
                </div>    
            ';
    }
}


if($noQuestions){
    echo '
    <div class="container mb-5">
        <div class="mt-4 p-5 bg-light text-dark rounded">
            <h4 class = "pb-4">There\'s no Question till Now</h4>
            <p>Be a first person to Ask Question</p>
            
            <p>Posted by : Kashif</p>

       </div>
    </div>';
}


?>

</div>



<!-- Modal for Comment of user from User-->
<div class="modal fade" id="CommentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        if(isset($_SESSION['loggedin'])){
            echo '<form action = "'. $_SERVER['REQUEST_URI'] .'" method = "POST">
                <label for="exampleInputEmail1" class="form-label">Comment</label>
                <textarea class="form-control" placeholder="Give your point of view" name = "comment" id="comment" style="height: 100px"></textarea>
                <button type="submit" class="btn btn-primary my-4" name = "commentBtn">Submit Comment</button>
            </form>';
        }

        else{
            echo "You are not logged in , You must have to loggedin to submit your point of view";
        }

        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<?php
    require "components/_footer.php";
?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>

</html>