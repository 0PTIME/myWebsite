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
if(!isset($_SESSION['username']))
{
    $errors['timeout'] = "Please log in...";
    $_SESSION['errors'] = $errors;
    header("location: index");
    exit();
}
$username = $_SESSION['username'];

/******** MAIN LOGIC FOR POSTING A TWEET ****************/
// makes sure that the field was filled out
// part that checks if you are trying to submit a reply or a tweet
if(isset($_POST['tweetId'])){
    if(isset($_POST['reply'])){
        // sets variables used for storing the tweet
        $tweetId = $_POST['tweetId'];
        $usr = $_SESSION['username'];
        $tweet = $_POST['reply'];
        $tags = getTags($tweet); // calls the function that returns all the tags in were in the tweet
        $ats = getAts($tweet); // calls the function that returns all the people that were mentioned in the tweet
        $identifier = uniqid("reply", true); // creates a unique id based on the the current time in microseconds with the option to make it even more unique turned on
        $likes = 0;
        $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // creates a mysql query that is used to insert a new row with the information created when the user submits the tweet
        $sql = "INSERT INTO replies (ID, tweetID, content, tags, ats, likes, uniqueid) VALUES ('" . $usr . "', '" . $tweetId . "', '" . $tweet . "', '" . $tags . "', '" . $ats . "', '" . $likes . "', '" . $identifier . "');";
        mysqli_query($mysqli, $sql); // executes the query
        if($ats != ""){ // if the get tweets function didn't return a string without any values in it calls the notifyMentions function and passes the tweet id
            notifyMentions($ats, $identifier);
        }
        header("location: tweet?id=" . $tweetId);
        exit();
    }
}
else{
    if(isset($_POST['yap'])){
        // sets variables used for storing the tweet
        $usr = $_SESSION['username'];
        $tweet = $_POST['yap'];
        $tags = getTags($tweet); // calls the function that returns all the tags in were in the tweet
        $ats = getAts($tweet); // calls the function that returns all the people that were mentioned in the tweet
        $identifier = uniqid("tweet", true); // creates a unique id based on the the current time in microseconds with the option to make it even more unique turned on
        $likes = 0;
        $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // creates a mysql query that is used to insert a new row with the information created when the user submits the tweet
        $sql = "INSERT INTO tweets (ID, content, tags, ats, likes, uniqueid) VALUES ('" . $usr . "', '" . $tweet . "', '" . $tags . "', '" . $ats . "', '" . $likes . "', '" . $identifier . "');";
        mysqli_query($mysqli, $sql); // executes the query
        if($ats != ""){ // if the get tweets function didn't return a string without any values in it calls the notifyMentions function and passes the tweet id
            notifyMentions($ats, $identifier);
        }
        header("location: home");
        exit();
    }
}


// redirects back to the homepage
header("location: index");

