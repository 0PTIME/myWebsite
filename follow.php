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


if(isset($_GET['keyword'])){
    $search = $_GET['keyword'];
    if($search != $username){
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryaddFollow = "SELECT follows FROM users WHERE title='" . $username . "'";
        $sqlqueryupdateCount = "SELECT followers FROM users WHERE title='" . $search . "'";
        $resultFollow = mysqli_query($mysqli, $sqlqueryaddFollow);
        $resultUpdate = mysqli_query($mysqli, $sqlqueryupdateCount);
        if(mysqli_num_rows($resultFollow) == 1 && mysqli_num_rows($resultUpdate))
        {
            $data = mysqli_fetch_assoc($resultFollow);
            $dataTwo = mysqli_fetch_assoc($resultUpdate);        
            $currentFollows = $data['follows'];
            $currentFollowerCount = $dataTwo['followers'];
            if(checkFollows($currentFollows, $search)){
                $currentFollows = addFollow($currentFollows, $search);
                $currentFollowerCount += 1;
                echo "it returned true";
            }
            else{
                $currentFollows = remFollow($currentFollows, $search);
                $currentFollowerCount -= 1;
                echo "it returned false";
            }
            $queryFollow = "UPDATE users SET follows='" . $currentFollows . "' WHERE title='" . $username . "'";
            $queryUpdate = "UPDATE users SET followers='" . $currentFollowerCount . "' WHERE title='" . $search . "'";
            mysqli_query($mysqli, $queryFollow);
            mysqli_query($mysqli, $queryUpdate);
        }       
        header("location: search.php?keyword=" . $search);
        exit();
    }
    else{
        header("location: search.php?keyword=" . $search);
        exit();
    }
}
header("location: home.php");
?>