<?php
include_once('tbs_class.php');
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/index.html');
$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
if(!isset($_SESSION['error'])) { $_SESSION['error'] = ""; }
$error = $_SESSION['error'];

// if(isset($_SESSION['username']))
// {
//     header("location: home.php");
// }

$tbs->Show();
?>