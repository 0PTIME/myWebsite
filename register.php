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


$usr = $_POST['usrReg'];
$pwd = $_POST['pwd'];

$email = $_POST['usrEmail'];

if($pwd == $_POST['pwdTwo'])
{
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sqlquery = "SELECT title, email FROM users";
    $result = mysqli_query($mysqli, $sqlquery);
    if(mysqli_num_rows($result) > 0)
    {
        $data = mysqli_fetch_assoc($result);
        if($email != $data['email'])
        {
            if($usr != $data['title'])
            {
                $error = "ERROR: UNKOWN ERROR :(";
                $tbs->Show();
            }
            else {
                $error = "ERROR: This username is already taken :(";
                $tbs->Show();
            }
        }
        else {
            $error = "ERROR: This email is already registered in our systems :(";
            $tbs->Show();
        }
    }
    else{
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        //echo $hash;
        $sql = "INSERT INTO users (title, email, pwd) VALUES ('" . $usr . "', '" . $email . "', '" . $hash . "');";
        mysqli_query($mysqli, $sql);
        $error = "You are now Registered, please sign in :)";
        // $_SESSION['username'] = $data['title'];
        // header("location: index.php");
    }   
}
else {
    $error = "ERROR: Your passwords didn't match :(";
    $tbs->Show();
}

$sessionERR = "none";




?>