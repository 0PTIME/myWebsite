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


if(isset($_GET['keyword'])){
    $search = $_GET['keyword'];
    $char = str_split($search);
    if($char[0] == '#'){
        $searchisuser = false;
        $search = "I haven't added the functionality of searching for tags";
    }
    else {
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlquery = "SELECT title, date_added, description, followers FROM users WHERE title='" . $search . "'";
        $result = mysqli_query($mysqli, $sqlquery);
        if(mysqli_num_rows($result) == 1)
        {
            $searchisuser = true;
            $data = mysqli_fetch_assoc($result);
            $searchedUser = $data['title'];
            $searchedDescription = $data['description'];
            if($searchedDescription == NULL){ $searchedDescription = ":)"; }
            $searchedFollowers = $data['followers'];
            $searchedDateAdded = $data['date_added'];
        }
        else{ $searchisuser = false; $search = "Your search '" . $search . "' didn't bring anything back"; }
        
    }
}
else{
    $search = "you didn't search anything";
    $searchisuser = false;
}
$description = $_SESSION['description'];
if($description == NULL){ $description = ":)"; }
$followers = $_SESSION['followers'];
$dateAdded = $_SESSION['datecreated'];

$tbs->Show();
?>