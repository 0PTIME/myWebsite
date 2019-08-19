<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();
/******** VARIABLES FOR TBS ************/
$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Go Home";
$font = "Play&display=swap";
$error = "none";
/******** SCUFFED WAY OF KEEPING THE USER LOGGED IN ************/
if(!isset($_SESSION['username']))
{
    $errors['timeout'] = "Please log in...";
    $_SESSION['errors'] = $errors;
    header("location: index");
    exit();
}

if(isset($_GET['tweetId'])){
    $tweetId = $_GET['tweetId'];
    $currLikes = getLikes($_SESSION['username']);
    $tweet = getTweet($tweetId);
    if(checkLikes($tweetId, $currLikes)){
        $newLikes = addLike($tweetId, $currLikes);
        setLikes($_SESSION['username'], $newLikes);
        $numLikes = $tweet['likes'] + 1;
    }
    else{
        $newLikes = remLike($tweetId, $currLikes);
        setLikes($_SESSION['username'], $newLikes);
        $numLikes = $tweet['likes'] - 1;
    }
    $currLikes = getLikes($_SESSION['username']);
    updateLikes($tweetId, $numLikes);
    echo "LIKE " . $numLikes;
    exit();
}






?>
