<?php
include_once('tbs_class.php');
session_set_cookie_params(7200);
session_start();

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "none";


session_unset();
session_destroy();
header("location: index.php");

?>