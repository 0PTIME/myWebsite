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
$myFollows = $_SESSION['myFollows'];
$myNotifications = $_SESSION['myNotifications'];

/****** logic for pulling your twitter notifications and displaying multiple tweets *******/
$mysqli = mysqli_connect("localhost", "website", "data", "website_users");
if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
$myNotifications = explode(' ', $myNotifications);
$queryNotification = implode("', '", $myFollows);
$now = date('Y-m-d G:i:s');
$monthago = date('Y-m-d G:i:s', strtotime("-1 months"));
$queryTweets = "SELECT ID, content, tags, ats, time, likes, uniqueid FROM users WHERE uniqueid IN ('" . $queryNotification . "')";
$queryResults = mysqli_query($mysqli, $queryTweets);
$tweet = mysqli_fetch_assoc($queryResults);
// if(mysqli_num_rows($queryResults) > 0){
//     while($tweet = mysqli_fetch_assoc($queryResults)){
//         // $tbs->MergeBlock('blk1', $tweet);
//         print_r($tweet);
//     }
// }

$tbs->Show();
