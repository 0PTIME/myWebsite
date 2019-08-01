<?php
include_once('tbs_class.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/index.html');
$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
if(!isset($_SESSION['error'])) { $_SESSION['error'] = ""; }
$error = $_SESSION['error'];

if(isset($_SESSION['username']))
{
    header("location: home.php");
}
else {
    $_SESSION['error'] = "You were timed out... please log back in!";
}

$tbs->Show();
?>