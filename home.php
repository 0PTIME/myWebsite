<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/home.html');
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
else {
    // sets the username and sets a bunch of their information to the session variable
    $username = $_SESSION['username'];
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sqlquery = "SELECT title, date_added, description, follows, followers FROM users WHERE title='" . $username . "'";
    $result = mysqli_query($mysqli, $sqlquery);
    if(mysqli_num_rows($result) == 1){
        $data = mysqli_fetch_assoc($result);
        $_SESSION['description'] = $data['description'];
        if($description == NULL){ $description = ":)"; }
        $_SESSION['followers'] = $data['followers'];
        $_SESSION['datecreated'] = $data['date_added'];
        $_SESSION['myFollows'] = $data['follows'];
        
    }
}
// bunch of variables that tbs uses to display your information wherever you go
$description = $_SESSION['description'];
if($description == NULL){ $description = ":)"; }
$followers = $_SESSION['followers'];
$dateAdded = $_SESSION['datecreated'];
$myFollows = $_SESSION['myFollows'];

/****** logic for pulling your twitter feed and displaying multiple tweets *******/
$myFollows = explode('.', $myFollows);
$queryFollows = implode("', '", $myFollows);
$now = date('Y-m-d G:i:s');
$monthago = date('Y-m-d G:i:s', strtotime("-1 months"));
$queryTweets = "SELECT ID, content, tags, ats, time, likes, uniqueid FROM tweets WHERE ID IN ('" . $queryFollows . "') AND time BETWEEN '" . $monthago . "' AND '" . $now . "'";
$queryResults = mysqli_query($mysqli, $queryTweets);
// $tweet = mysqli_fetch_assoc($queryResults);
// if(mysqli_num_rows($queryResults) > 0){
//     while($tweet = mysqli_fetch_assoc($queryResults)){
//         // $tbs->MergeBlock('blk1', $tweet);
//         print_r($tweet);
//     }
// }



$tweetOne = true;
$oneUser = "me";
$oneContent = "testing";    




$tbs->Show();
?>