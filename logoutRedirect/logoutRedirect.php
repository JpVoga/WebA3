<?php
    session_start();

    unset($_SESSION["user"]);
    header("Location:../home/home.php");
    die(); // Stop the page
?>