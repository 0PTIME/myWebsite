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
    $username = $_SESSION['username'];
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sqlquery = "SELECT title, date_added, description, followers FROM users WHERE title='" . $username . "'";
    $result = mysqli_query($mysqli, $sqlquery);
    if(mysqli_num_rows($result) == 1){
        $data = mysqli_fetch_assoc($result);
        $_SESSION['description'] = $data['description'];
        if($description == NULL){ $description = ":)"; }
        $_SESSION['followers'] = $data['followers'];
        $_SESSION['datecreated'] = $data['date_added'];
        
    }
    $description = $_SESSION['description'];
    if($description == NULL){ $description = ":)"; }
    $followers = $_SESSION['followers'];
    $dateAdded = $_SESSION['datecreated'];

    
    $tweetOne = true;
    $oneUser = "me";
    $oneContent = "testing";    
}




$tbs->Show();
?>