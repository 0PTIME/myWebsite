<?php
include_once('tbs_class.php');
session_set_cookie_params(7200);
session_start();

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "";

unset($_SESSION['username']);
header("location: index.php");

?>