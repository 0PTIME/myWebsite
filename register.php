<?php
include_once('tbs_class.php');
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/register.html');

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "";

// if(isset($_SESSION['username']))
// {
//     header("location: home.php");
// }

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
                $_SESSION['error'] = "ERROR: UNKOWN ERROR :(";
                header("location: index.php");
            }
            else {
                $_SESSION['error'] = "ERROR: This username is already taken :(";
                header("location: index.php");
            }
        }
        else {
            $_SESSION['error'] = "ERROR: This email is already registered in out systems :(";
            header("location: index.php");
        }
    }
    else{
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        //echo $hash;
        $sql = "INSERT INTO users (title, email, pwd) VALUES ('" . $usr . "', '" . $email . "', '" . $hash . "');";
        mysqli_query($mysqli, $sql);
        $_SESSION['error'] = "You are now Registered, please sign in :)";
        $_SESSION['username'] = $usr;
        header("location: index.php");
    }   
}
else {
    $_SESSION['error'] = "ERROR: Your passwords didn't match :(";
    header("location: index.php");
}





?>