<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

/******** VARIABLES FOR TBS ************/
$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$errors = $_SESSION['errors'];
$error = "none";

if(isset($_POST['yap'])){
    $usr = $_SESSION['username'];
    $tweet = $_POST['yap'];
    $tags = getTags($tweet);
    $mysqli = mysqli_connect("localhost", "tweets", "tweets", "tweets");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "INSERT INTO tweets (ID, content, tags) VALUES ('" . $usr . "', '" . $tweet . "', '" . $tags . "');";
    mysqli_query($mysqli, $sql);
}



header("location: index.php");

