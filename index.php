<?php

include_once('tbs_class.php');

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/login.html');

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";



$tbs->Show();
?>