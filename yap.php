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
    $ats = getAts($tweet);
    $likes = 0;
    $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "INSERT INTO tweets (ID, content, tags, ats, likes) VALUES ('" . $usr . "', '" . $tweet . "', '" . $tags . "', '" . $ats . "', '" . $likes . "');";
    mysqli_query($mysqli, $sql);
}



header("location: index.php");

