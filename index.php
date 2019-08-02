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
$errors = $_SESSION['errors'];
$error = "";

if(isset($_SESSION['username']))
{
    header("location: home.php");
}
elseif ($_SESSION['error'] == "Please log back in!"){
    $sessionERR = "none";
}
else{
    $_SESSION['error'] = "Please log back in!";
}
if(isset($_SESSION['error'])) { $sessionERR = $_SESSION['error']; }
else { $sessionERR = "none"; }

if(isset($errors['UNKOWN'])) $error = $errors['UNKOWN'];
if(isset($errors['title'])) $error = $errors['title'];
if(isset($errors['success']))$error = $errors['success'];
if(isset($errors['passwords']))$error = $errors['passwords'];
if(isset($errors['email']))$error = $errors['email'];
if(isset($errors['credentials']))$error = $errors['credentials'];
if(isset($errors['critical']))$error = $errors['critical'];
if(isset($errors['hahah']))$error = $errors['hahah'];

$tbs->Show();
?>