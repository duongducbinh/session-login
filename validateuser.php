<?php
session_start();
if (!isset($_SESSION["ISLOGIN"])){
    $_SESSION["LOGIN"] = false;
}

if ($_SERVER["REQUEST_METHOD" ] == "POST"){
    $username = $_POST["username"]; 
    $password = $_POST["password"];
    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    $database = "user";

    $conn = new mysqli($servername, $usernameDB, $passwordDB, $database);

    if ($conn->connect_error) {
        die("". $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE name = '$username' AND password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION["ISLOGIN"] = true;
        header("location: upload.php");
        exit();
    }
    else {
        $_SESSION[""] = false;
        header("location: login.html");
        exit();
    }

}
session_destroy();
?>