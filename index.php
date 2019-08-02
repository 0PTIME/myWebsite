<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/index.html');
$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$errors = $_SESSION['errors'];
$error = "none";

if(isset($_SESSION['username']))
{
    header("location: home.php");
}
else{
    array_push($errors['sessionERR'], "Please log in...");
}


foreach($errors as $key => $value)
{
    if(isset($errors[$key])) { $error = $errors[$key]; logout(); }
}
// if(isset($errors['UNKOWN'])) { $error = $errors['UNKOWN']; logout(); }
// if(isset($errors['title'])) { $error = $errors['title']; logout(); }
// if(isset($errors['success'])) { $error = $errors['success']; logout(); }
// if(isset($errors['passwords'])) { $error = $errors['passwords']; logout(); }
// if(isset($errors['email'])) { $error = $errors['email']; logout(); }
// if(isset($errors['credentials'])) { $error = $errors['credentials']; logout(); }
// if(isset($errors['critical'])) { $error = $errors['critical']; logout(); }
// if(isset($errors['hahah'])) { $error = $errors['hahah']; logout(); }
// if(isset($errors['dberror'])) { $error = $errors['dberror']; logout(); }
echo $error;

$tbs->Show();
?>