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
if(isset($_GET['likeTweet'])){
    $tweetId = $_GET['likeTweet'];
    $response = likeTweet($tweetId);
    echo "LIKE " . $response;
    exit();
}
if(isset($_POST['imgTP'])){
    $user = getProfile($_SESSION['username']);
    $uploadDir = '/var/www/html/localdev/mywebsite/media/userspf/' . $user['userId'] . $user['title'];
    echo $uploadDir;
    if(move_uploaded_file($_FILES['imgTP'], $uploadDir));
    exit();
}
if(isset($_POST['tweetId'])){
    if(isset($_POST['reply'])){
        // sets variables used for storing the tweet
        $tweetId = $_POST['tweetId'];
        $tweetOwner = $_POST['tweetOwner'];
        $sourceTweet = $_POST['sourceTweet'];
        if(!isset($sourceTweet)) { $sourceTweet = $tweetId; }
        $usr = $_SESSION['username'];
        $tweet = $_POST['reply'];
        $tags = getTags($tweet); // calls the function that returns all the tags in were in the tweet
        $ats = getAts($tweet . " @" . trim($tweetOwner)); // calls the function that returns all the people that were mentioned in the tweet and mentions the person that was replied to
        $identifier = uniqid("reply", true); // creates a unique id based on the the current time in microseconds with the option to make it even more unique turned on
        $likes = 0;
        $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $tweet = mysql_real_escape_string($mysqli, $tweet);
        // creates a mysql query that is used to insert a new row with the information created when the user submits the tweet
        $sql = "INSERT INTO replies (ID, tweetID, sourceID, content, tags, ats, likes, uniqueid) VALUES ('" . $usr . "', '" . $sourceTweet . "', '" . $tweetId . "', '" . $tweet . "', '" . $tags . "', '" . $ats . "', '" . $likes . "', '" . $identifier . "');";
        mysqli_query($mysqli, $sql); // executes the query
        if($ats != ""){ // if the get tweets function didn't return a string without any values in it calls the notifyMentions function and passes the tweet id
            notifyMentions($ats, $identifier);
        }
        header("location: tweet?id=" . $tweetId);
        exit();
    }
    if(isset($_POST['retweet'])){
        $tweetId = $_POST['tweetId'];
        $tweet = $_POST['retweet'];
        $response = retweetTweet($tweetId, $tweet);
        echo "RETWEET " . $response;
        exit();
    }
    if(isset($_POST['delTweet'])){
        $tweetId = $_POST['tweetId'];
        $tweet = getTweet($tweetId);
        if($tweet['title'] == $_SESSION['username']){
            $response = delTweet($tweetId);
            if($response == true){
                echo "<p class=\"delTweet\">This content is no longer available</p>";
            }
            else { echo "wow"; }
        }
        else{ echo "wow"; }
        exit();
    }
}
else{
    if(isset($_POST['yap'])){
        // sets variables used for storing the tweet
        $tweet = $_POST['yap'];
        $usr = $_SESSION['username'];
        $tags = getTags($tweet); // calls the function that returns all the tags in were in the tweet
        $ats = getAts($tweet); // calls the function that returns all the people that were mentioned in the tweet
        $identifier = uniqid("tweet", true); // creates a unique id based on the the current time in microseconds with the option to make it even more unique turned on
        $likes = 0;
        $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $tweet = mysqli_real_escape_string($mysqli, $_POST['yap']);
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

