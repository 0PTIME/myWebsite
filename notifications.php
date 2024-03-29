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
$mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection that connects to the YAPPER database which houses the tweets and replies table
if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
// takes the string of notifications stored in the session when the user goes to their homepage and saves it to an array
$myNotifications = explode(' ', $myNotifications);
foreach($myNotifications as $checkNotify){ // takes each notification and adds it to a tweet or reply array respectively
    $newCheck = getPrefix($checkNotify);
    if($newCheck == "tweet"){
        $myTweetNotifications[count($myTweetNotifications)] = $checkNotify;
    }
    if($newCheck == "reply"){
        $myReplyNotifications[count($myReplyNotifications)] = $checkNotify;
    }
}

// makes two sql queries one to pull all the replies one to pull all the tweets
$queryNotificationTweet = implode("', '", $myTweetNotifications);
$queryNotificationReply = implode("', '", $myReplyNotifications);
$queryTweets = "SELECT ID, content, tags, ats, time, likes, uniqueid FROM tweets WHERE uniqueid IN ('" . $queryNotificationTweet . "') ORDER BY time DESC";
$queryReplies = "SELECT ID, tweetID, content, tags, ats, time, likes, uniqueid FROM replies WHERE uniqueid IN ('" . $queryNotificationReply . "') ORDER BY time DESC";
$queryTweetResults = mysqli_query($mysqli, $queryTweets);
$queryReplyResults = mysqli_query($mysqli, $queryReplies);
$i = 0; // counter to make a nicely made 2d array for tbs
if(mysqli_num_rows($queryReplyResults) > 0 || mysqli_num_rows($queryTweetResults) > 0){  // tests if there are any notifications at all
    $tweetsExist = true;
    while($reply = mysqli_fetch_assoc($queryReplyResults)){
        $tweet_block[$i]['torr'] = true; // this might be used later to determine what template to display a tweet or a reply
        $tweet_block[$i]['mentions'] = $reply['ats'];
        $tweet_block[$i]['tags'] = $reply['tags'];
        $tweet_block[$i]['likes'] = $reply['likes'];
        $tweet_block[$i]['identifier'] = $reply['uniqueid'];
        $tweet_block[$i]['tweetId'] = $reply['TweetID'];
        $tweet_block[$i]['title'] = $reply['ID'];
        $tweet_block[$i]['content'] = $reply['content'];
        $tweet_block[$i]['fullTimestamp'] = $reply['time'];
        if($tweet_block[$i]['timestamp'] = "replied " . getTimespan($reply['time']));
        $i++;
    }
    while($tweet = mysqli_fetch_assoc($queryTweetResults)){
        $tweet_block[$i]['torr'] = false;
        $tweet_block[$i]['mentions'] = $tweet['ats'];
        $tweet_block[$i]['tags'] = $tweet['tags'];
        $tweet_block[$i]['likes'] = $tweet['likes'];
        $tweet_block[$i]['identifier'] = $tweet['uniqueid'];
        $tweet_block[$i]['title'] = $tweet['ID'];
        $tweet_block[$i]['content'] = $tweet['content'];
        $tweet_block[$i]['fullTimestamp'] = $tweet['time'];
        if($tweet_block[$i]['timestamp'] = "tweeted " . getTimespan($tweet['time']));
        $i++;
    }
    usort($tweet_block, function($item1, $item2) { // sorts the array tweet_block by time in decending order
        $ts1 = strtotime($item1['fullTimestamp']);
        $ts2 = strtotime($item2['fullTimestamp']);
        return $ts2 - $ts1;
    });

    $tbs->MergeBlock('blk1', $tweet_block);
}
else{ $tweetsExist = false;}



$tbs->Show();
