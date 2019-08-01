<?php
include_once('tbs_class.php');
session_start();

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "";

// if(isset($_SESSION['username']))
// {
//     header("location: home.php");
// }

$mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sqlquery = "SELECT title, email FROM users";
    $result = mysqli_query($mysqli, $sqlquery);
    $data = mysqli_fetch($result);
    if($email != $data['email'])
    {
        if($usr != $data['title'])
        {

        }
        else {
            $_SESSION['error'] = "ERROR: This username is already taken :(";
            header("location: index.php");
        }
    }
    else {
        $_SESSION['error'] = "ERROR: This email is already registered in out systems :(";
        header("location: index.php");
    }

header("location: home.php");
?>