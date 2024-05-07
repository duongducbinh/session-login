<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>welcome to mypage</h1>
    <form action="logout.php" method="POST">
        <input type="submit" value="logout" >
    </form>
    
</body>
<?php
session_start();
if ($_SESSION["IsLogin"] == false)
header("Location: login.htm");
?>
</html>
