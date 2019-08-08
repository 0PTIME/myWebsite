<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/index.html');
/******** VARIABLES FOR TBS ************/
$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$errors = $_SESSION['errors'];
$error = "none";
/******** SCUFFED WAY OF KEEPING THE USER LOGGED IN ************/
if(isset($_SESSION['username']))
{
    header("location: home");
    exit();
}
// else{
//     array_push($errors['sessionERR'], "Please log in...");
// }

// run a loop that checks if there are any errors and if there are any set it to a local variable
foreach($errors as $key => $value)
{
    if(isset($errors[$key])) { $error = $errors[$key]; logout(); }
}

if($error != 'none')
{
    $iserror = true;
}
else
{
    $iserror = false;
}
$tbs->Show();
?>