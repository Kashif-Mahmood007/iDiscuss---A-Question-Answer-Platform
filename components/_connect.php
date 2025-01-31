<?php

$server = "localhost";
$username = "kashif";
$pass = "kashifphpmyadmin";
$database = "iDiscuss";

$conn = mysqli_connect($server, $username, $pass, $database);

if(!$conn){
    die("The Connection is not established due to ". mysqli_connect_error());
}


?>