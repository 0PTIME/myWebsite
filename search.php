<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/search.html');
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
$username = $_SESSION['username'];

/******* MAIN LOGIC FOR HOW TO HANDLE A SEARCH **************/
//  makes sure that the keyword is set
if(isset($_GET['keyword'])){
    $searchisfollow = false;
    $search = $_GET['keyword'];
    $char = str_split($search);
    // tests if the user is searching for a hashtag
    if($char[0] == '#'){
        $searchisuser = false;
        $search = "I haven't added the functionality of searching for tags";
    }
    // currently is then assumes that you are searching for a user and tries to do that function
    else {
        // DB connection
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // creates a query that searches the database for users matching what was searched
        $sqlquery = "SELECT title, date_added, description, numfollowers FROM users WHERE title='" . $search . "'";
        $result = mysqli_query($mysqli, $sqlquery);
        // if there is only one result display the users information
        if(mysqli_num_rows($result) == 1)
        {
            $searchisuser = true;
            $data = mysqli_fetch_assoc($result);
            $searchedUser = $data['title'];
            $searchedDescription = $data['description'];
            if($searchedDescription == NULL){ $searchedDescription = ":)"; }
            $searchedFollowers = $data['numfollowers'];
            $searchedDateAdded = $data['date_added'];


            $now = date('Y-m-d G:i:s');
            $monthago = date('Y-m-d G:i:s', strtotime("-1 months"));
            $sqlConnection = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
            if (!$mysqli) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $queryTweets = "SELECT ID, content, tags, ats, time, likes, uniqueid FROM tweets WHERE ID IN ('" . $searchedUser . "') AND time BETWEEN '" . $monthago . "' AND '" . $now . "'  ORDER BY time DESC";
            $queryResults = mysqli_query($sqlConnection, $queryTweets);
            $i = 0;
            if(mysqli_num_rows($queryResults) > 0){
                $tweetsExist = true;
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
            else { $tweetsExist = false; }
        }
        // if there was more than one result sets the search to not display a user and tells them that we didn't find anything
        else{ $searchisuser = false; $search = "Your search '" . $search . "' didn't bring anything back"; }
        
    }
}
elseif(isset($_GET['follow'])){
    $searchisuser = false;
    $searchisfollow = true;
    $search = $_GET['follow'];
    if($search == "follow"){
        
    }
    if($search == "myfollowers"){

    }

}
// message for is they didn't enter a value into the search bar
else{
    $search = "you didn't search anything";
    $searchisuser = false;
    $searchisfollow = false;

}
// variables containing the logged in users information
$description = $_SESSION['description'];
if($description == NULL){ $description = ":)"; }
$followers = $_SESSION['followers'];
$dateAdded = $_SESSION['datecreated'];

$tbs->Show();
?>