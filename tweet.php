<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/tweet.html');
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
    $sqlquery = "SELECT title, date_added, description, follows, followers, notifications FROM users WHERE title='" . $username . "'";
    $result = mysqli_query($mysqli, $sqlquery);
    if(mysqli_num_rows($result) == 1){
        $data = mysqli_fetch_assoc($result);
        $_SESSION['description'] = $data['description'];
        if($description == NULL){ $description = ":)"; }
        $_SESSION['followers'] = $data['followers'];
        $default = $data['date_added'];
        $default = date('m/d/Y', strtotime($default));
        $_SESSION['datecreated'] = $default;
        $_SESSION['myFollows'] = $data['follows'];
        $_SESSION['myNotifications'] = $data['notifications'];
        
    }
}
// bunch of variables that tbs uses to display your information wherever you go
$description = $_SESSION['description'];
if($description == NULL){ $description = ":)"; }
$followers = $_SESSION['followers'];
$dateAdded = $_SESSION['datecreated'];
$myFollows = trim($_SESSION['myFollows']);
$myNotifications = trim($_SESSION['myNotifications']);

/****** logic for pulling your twitter feed and displaying multiple tweets *******/
if(isset($_GET['id'])){
    $tweetId = $_GET['id'];
    $sqlConnection = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $queryTweets = "SELECT ID, content, tags, ats, time, likes, uniqueid FROM tweets WHERE uniqueid='" . $tweetId . "'";
    $queryResults = mysqli_query($sqlConnection, $queryTweets);
    if(mysqli_num_rows($queryResults) == 1){
        $tweetInfo = mysqli_fetch_assoc($queryResults);
        $tweetUsername = $tweetInfo['ID'];
        if($timestamp = getTimespan($tweetInfo['time'])); 
        $content = $tweetInfo['content'];
        
    }
    

    $queryTweets = "SELECT ID, content, tags, ats, time, likes, uniqueid FROM replies WHERE tweetID='" . $tweetId . "' ORDER BY time DESC";
    $queryResults = mysqli_query($sqlConnection, $queryTweets);
    $i = 0;
    if(mysqli_num_rows($queryResults) > 0){
        $replyExist = true;
        while($tweet = mysqli_fetch_assoc($queryResults)){
            $tweet_block[$i]['mentions'] = $tweet['ats'];
            $tweet_block[$i]['tags'] = $tweet['tags'];
            $tweet_block[$i]['likes'] = $tweet['likes'];
            $tweet_block[$i]['identifier'] = $tweet['uniqueid'];
            $tweet_block[$i]['title'] = $tweet['ID'];
            $tweet_block[$i]['content'] = $tweet['content'];
            if($tweet_block[$i]['timestamp'] = getTimespan($tweet['time']));        
            $i++;
        }
        $tbs->MergeBlock('blk1', $tweet_block);
    }
    else { $replyExist = false; }
}
else{
    header("location: home");
    exit();
}
   




$tbs->Show();
?>