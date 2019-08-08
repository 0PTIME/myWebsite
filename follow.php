<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();
/******** SCUFFED WAY OF KEEPING THE USER LOGGED IN ************/
if(!isset($_SESSION['username']))
{
    $errors['timeout'] = "Please log in...";
    $_SESSION['errors'] = $errors;
    header("location: index.php");
    exit();
}
$username = $_SESSION['username'];

// this is the double check feature to make sure that they are following an acctual something
if(isset($_GET['keyword'])){
    $search = $_GET['keyword'];
    // makes sure they aren't trying to follow themselves
    if($search != $username){
        /******* DB connection which errors out if it fails to connect *********/
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // defines 2 queries that get the people that the person logged in follows and gets the count of the person you are following
        $sqlqueryaddFollow = "SELECT follows FROM users WHERE title='" . $username . "'";
        $sqlqueryupdateCount = "SELECT followers FROM users WHERE title='" . $search . "'";
        $resultFollow = mysqli_query($mysqli, $sqlqueryaddFollow);
        $resultUpdate = mysqli_query($mysqli, $sqlqueryupdateCount);
        // makes sure we only got one result for both queries
        if(mysqli_num_rows($resultFollow) == 1 && mysqli_num_rows($resultUpdate) == 1)
        {
            // takes the data from both queries and stores it into two arrays
            $data = mysqli_fetch_assoc($resultFollow);
            $dataTwo = mysqli_fetch_assoc($resultUpdate);  
            // sets local variables to the values that are needed for this function      
            $currentFollows = $data['follows'];
            $currentFollowerCount = $dataTwo['followers'];
            // calls a function that returns true if it finds that the person you are trying to follow, you already follow and vice versa
            if(checkFollows($currentFollows, $search)){
                // calls a function to add the person you are trying to follow to your follows list and increments their follower count by one
                $currentFollows = addFollow($currentFollows, $search);
                $currentFollowerCount += 1;
            }
            else{
                // calls the remove follow function which removes a given follow from a list of follows and decrements their follower count
                $currentFollows = remFollow($currentFollows, $search);
                $currentFollowerCount -= 1;
            }
            // queries to push the changes to the database
            $queryFollow = "UPDATE users SET follows='" . $currentFollows . "' WHERE title='" . $username . "'";
            $queryUpdate = "UPDATE users SET followers='" . $currentFollowerCount . "' WHERE title='" . $search . "'";
            mysqli_query($mysqli, $queryFollow);
            mysqli_query($mysqli, $queryUpdate);
        }       
        header("location: search?keyword=" . $search); // redirects you back to where you should have come from
        exit();
    }
    else{
        header("location: search?keyword=" . $search); // redirects you back to where you should have come from
        exit();
    }
}
header("location: home"); // if all if doesn't pass the if statement redirects you to your homepage
?>