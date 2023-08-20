<?php


$host = "localhost";
$dbname = "auth-sys";
$user = "root";
$pass = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname;", $user, $pass);

// if($conn == true){
//     echo"Connected successfully!";
// }
// else {
//     echo "Connection is wrong: err";
// }


?>