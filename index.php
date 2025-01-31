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

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


        session_start();
        $login = false;

        if(isset($_SESSION['loggedin'])){
            $login = true;
        }
        require "components/_navbar.php";
    ?>

    <div class="container">
        <h2 class = "text-center my-4 my-3">
            Catogories iDiscuss-Coding Forum
        </h2>
    </div>

    <div id="carouselExampleIndicators" class="carousel slide">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
        <img src="images/1.jpg" class="d-block w-100" alt = "Flex Image">
        </div>
        <div class="carousel-item">
        <img src="images/2.jpg" class="d-block w-100" alt = "Flex Image">
        </div>
        <div class="carousel-item">
        <img src="images/3.jpg" class="d-block w-100" alt = "Flex Image">
        </div>
        <div class="carousel-item">
        <img src="images/4.jpg" class="d-block w-100" alt = "Flex Image">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    </div>

    <div class="container my-3" id = "bottom">
        <div class="row">
            <!-- from here, the PHP starts -->
            <?php

            $sql = "SELECT * FROM `categories`";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);

            if($num > 0){
                while($rows = mysqli_fetch_assoc($result)){
                    $cat = $rows['category_name'];
                    $desc = $rows['category_description'];
                    $id = $rows['category_id'];

                    echo '
                    <div class="col-md-4 my-5">
                        <div class="card">
                            <img src="https://source.unsplash.com/1000x700/?coding,programming,'.$cat.'" class="card-img-top" alt = "The card image" >
                            <div class="card-body">
                                <h5 class="card-title"><a href = "threadlist.php?catid='.$id.'" class = "text-decoration-none ">'.$cat.'</a></h5>
                                <p class="card-text">'.substr($desc, 0, 280).'...</p>
                                <a href="threadlist.php?catid='.$id.'" class="btn btn-primary">Visit Thread</a>
                            </div>
                        </div>
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