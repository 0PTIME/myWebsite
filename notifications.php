<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/notifications.html');
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
// bunch of variables that tbs uses to display your information wherever you go
$username = $_SESSION['username'];
$description = $_SESSION['description'];
if($description == NULL){ $description = ":)"; }
$followers = $_SESSION['followers'];
$dateAdded = $_SESSION['datecreated'];
$myFollows = trim($_SESSION['myFollows']);
$myNotifications = trim($_SESSION['myNotifications']);

/****** logic for pulling your twitter notifications and displaying multiple tweets *******/
$mysqli = mysqli_connect("localhost", "website", "data", "YAPPER");
if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
$myNotifications = explode(' ', $myNotifications);
$queryNotification = implode("', '", $myNotifications);
$queryTweets = "SELECT ID, content, tags, ats, time, likes, uniqueid FROM tweets WHERE uniqueid IN ('" . $queryNotification . "')";
$queryResults = mysqli_query($mysqli, $queryTweets);
$i = 0;
if(mysqli_num_rows($queryResults) > 0){
    $tweetsExist = true;
    while($tweet = mysqli_fetch_assoc($queryResults)){
        $tweet_block[$i]['title'] = $tweet['ID'];
        $tweet_block[$i]['content'] = $tweet['content'];
        $i++;
    }
    $tbs->MergeBlock('blk1', $tweet_block);
}
else { $tweetsExist = false; }

$tbs->Show();
