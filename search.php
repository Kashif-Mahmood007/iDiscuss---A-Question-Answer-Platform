
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iDiscuss Coding-Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
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

        
        $search = mysqli_real_escape_string($conn, $_GET['query']);
    ?>

    <div style = "min-height : 605px">
        <div class="container my-4">
            <h1>Search results for <q><em><?php echo $search ?></em></q></h1>
            <?php
                if(isset($_GET['query'])){
                    $sql = "SELECT * FROM `threads` WHERE MATCH (`thread_title`, `thread_desc`) AGAINST ('$search')";
                    $result = mysqli_query($conn, $sql);
                    $num = mysqli_num_rows($result);
                
                    if($num > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $title = $row['thread_title'];
                            $desc = $row['thread_desc'];
                            $threadid = $row['thread_id'];   

                            echo '
                                <div class="result py-3">
                                    <strong><a href="threads.php?threadid='.$threadid.'" class="text-dark">'.$title.'</a></strong>
                                    <p>'.$desc.'</p>
                                </div>

                            ';
                        }
                    }   

                    else{
                        echo '
                        <div class="mt-4 p-5 bg-light text-dark rounded">
                            <h4 class="pb-4">Search Results not found</h4>
                            <p>Suggestions:

                            <li>Make sure that all words are spelled correctly.</li>
                            <li>Try different keywords.</li>
                            <li>Try more general keywords.</p></li>
                        </div>
                        ';
                    }
                }
            ?>
        </div>
    </div>
    
    <?php
        require "components/_footer.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>