<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iDiscuss Coding-Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">  
</head>

<body>
    
    <?php
    $login = false;

    session_start();
    if(isset($_SESSION['loggedin'])){
        $login = true;
    }

    require "components/_navbar.php";
    require "components/_connect.php";


        
    // code for inserting records of threads in Database
    $categoryId = $_GET['catid'];
    $submitRecords = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['submitBtn'])){
            // for accessin the corresponding ID of user from the signup 
            $recordsInsert = false;
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $title = str_replace("<" , "&lt;", $title);         // securying our forum from XSS attack
            $title = str_replace(">" , "&gt;", $title);
            
            $desc = mysqli_real_escape_string($conn, $_POST['description']);
            $desc = str_replace("<" , "&lt;", $desc);
            $desc = str_replace(">" , "&gt;", $desc);   
            $userid = $_SESSION['userid'];

            $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `datetime`) VALUES ('$title', '$desc', '$categoryId', '$userid', current_timestamp())";

            $result = mysqli_query($conn, $sql);

            if($result){
                echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your Question have been acknowledged Successfully. Please Wait for community to respond
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            }
        }
    }
?>

    <!-- Used as an alternative of Jumbotron in Bootstrap 5 -->

<?php

    $sql = "SELECT * FROM `categories` WHERE `category_id` = '$categoryId'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if($num > 0){
        while($rows = mysqli_fetch_assoc($result)){
            $catname = $rows['category_name'];
            $catdesc = $rows['category_description'];
            
            echo '
            <div class="container">
                <div class="mt-4 p-5 bg-light text-dark rounded">
                    <h1 class = "pb-4">Welcome to '.$catname.' forum</h1>
                    <p>'.$catdesc.'</p>
                    
                    <p>This is peer to peer coding discussion forum. No Spam / Advertising / Self-promote in the forums.  Do not post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post questions. Remain respectful of other members at all times
                    </p>

                    <button class = "btn btn-outline-dark mx-2" data-bs-toggle="modal" data-bs-target="#QuestionModal">Ask Question</button>

               </div>
            </div>';
        }
    }   

?>

<div class="container my-5">
    <h1>Browse Questions</h1>
</div>

<div id="bottom">
<?php  

// Here, category_id is the id of category passed in URL by clicking on the visit thread or title of catogory

$noQuestions = true;
$sql = "SELECT * FROM `threads` WHERE `thread_cat_id` = '$categoryId'";         
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);

if($num > 0){
    $noQuestions = false;
    while($row = mysqli_fetch_assoc($result)){
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thread_id = $row['thread_id'];
        $datetime = $row['datetime'];
        $userid = $row['thread_user_id'];

        $sql2 = "SELECT `name` FROM `signup` WHERE `sno` = '$userid'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);


        echo ' <div class="d-flex my-4 container">  
                    <div class="flex-shrink-0">
                        <img src="images/profile.png" height="55px" width="55px" alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                    <strong><a href = "threads.php?threadid='.$thread_id.'" class = "text-dark">'.$title.'</a></strong>
                    <p>'.$desc.'</p>
                    <p class = "mt-0">Posted by <strong>'.$row2['name'].'</strong> at <strong>'.$datetime.'</strong></p>
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

</div>          <!--CLosing of bottom Id div-->


<!-- Modal for asking the Question from User-->
<div class="modal fade" id="QuestionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        if(isset($_SESSION['loggedin'])){
            echo '<form action = "'.$_SERVER['REQUEST_URI'].'" method = "POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name = "title" aria-describedby="emailHelp" placeholder = "Title must be short and crisp">
                </div>
                
                <label for="exampleInputEmail1" class="form-label">Description</label>
                <textarea class="form-control" placeholder="Describe Your Query" name = "description" id="floatingTextarea2" style="height: 100px"></textarea>

                <button type="submit" class="btn btn-primary my-4" name = "submitBtn">Submit Question</button>
            </form>';
        }
        else{
            echo "You are not logged in , You must have to loggedin for asking question from Community";
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