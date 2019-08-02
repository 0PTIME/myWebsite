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
$error = "none";

if(isset($_SESSION['username']))
{
    header("location: home.php");
}
else {
    $_SESSION['error'] = "Please log back in!";
    header("location: index.php");
}
if(isset($_SESSION['error'])) { $sessionERR = $_SESSION['error']; }
else { $sessionERR = "none"; }

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
    echo "the query works :)";
    $data = mysqli_fetch_assoc($result);
    print_r($data);
    if($email == $data['email'])
    {
        if (password_verify($pwd, $data['pwd'])){
            $_SESSION['username'] = $data['title'];
            echo "You are now logged in via email";
            //header("location: home.php");
        }
    }
    else if($usr == $data['title'])
    {
        if (password_verify($pwd, $data['pwd'])){
            $_SESSION['username'] = $data['title'];
            echo "You are now logged in via username";
            //header("location: home.php");
        }       
    }
    else{
        $error = "Invalid Credentials...";
        $tbs->Show();
    }
}
else{
    $error = "Huge fucking mistakes man";
    $tbs->Show();
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
    
$sessionERR = "none";
?>
