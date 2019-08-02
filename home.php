<?php
include_once('tbs_class.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/home.html');

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Go Home";
$font = "Play&display=swap";
$error = "";

if(!isset($_SESSION['username']))
{
    $_SESSION['error'] = "Please log back in!";
    header("location: index.php");
}

$username = $_SESSION['username'];

$tbs->Show();
?>