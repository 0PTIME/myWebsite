<?php
include_once('tbs_class.php');
session_set_cookie_params(7200);
session_start();

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "";

if(isset($_SESSION['username']))
{
    header("location: home.php");
}
else {
    $_SESSION['error'] = "You were timed out... please log back in!";
    header("location: index.php");
}

$usr = $_POST['usr'];
$pwd = $_POST['pwd'];
if(checkEmail($usr)){
    $email = $usr;
}

$mysqli = mysqli_connect("localhost", "website", "data", "website_users");
if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
$sqlquery = "SELECT title, email FROM users";
$result = mysqli_query($mysqli, $sqlquery);
if(mysqli_num_rows($result) == 1 )
{
    $data = mysqli_fetch_assoc($result);
    if($email == $data['email'])
    {
        if (password_verify($pwd, $data['pwd'])){
            $_SESSION['username'] = $usr;
            header("location: home.php");
        }
    }
    else if($usr == $data['title'])
    {
        if (password_verify($pwd, $data['pwd'])){
            $_SESSION['username'] = $usr;
            header("location: home.php");
        }       
    }
}
else{
    $_SESSION['error'] = "Huge fucking mistakes man";
    header("location: index.php");
}



function checkEmail($email) {
    $find1 = strpos($email, '@');
    $find2 = strpos($email, '.');
    if($find1 !== false && $find2 !== false && $find2 > $find1){
        return true;
    }
    else {
        return false;
    }
}
    
header("location: home.php");
?>
