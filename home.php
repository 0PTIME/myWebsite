<?php
include_once('tbs_class.php');
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/home.html');

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "";


$tbs->Show();
?>