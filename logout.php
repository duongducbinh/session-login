<?php
    session_start();
    $_SESSION["ISLOGIN"] = false;

    session_unset();

    session_destroy();

    session_destroy();
    header("location: login.html");
    exit();
?>