<?php
include_once('tbs_class.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/register.html');

$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "none";

if(isset($_SESSION['username']))
{
    header("location: home.php");
}
elseif($_SESSION['error'] == "Please log back in!"){
    $sessionERR = "none";
}
else{
    $_SESSION['error'] = "Please log back in!";
}
if(isset($_SESSION['error'])) { $sessionERR = $_SESSION['error']; }
else { $sessionERR = "none"; }

if(isset($_POST['usr']) && isset($_POST['pwd'])){
    $usr = $_POST['usrReg'];
    $pwd = $_POST['pwd'];
    $email = $_POST['usrEmail'];

    if($pwd == $_POST['pwdTwo'])
    {
        
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlquery = "SELECT title, email FROM users WHERE title='" . $usr . "'";
        $result = mysqli_query($mysqli, $sqlquery);
        if(mysqli_num_rows($result) > 0)
        {
            $data = mysqli_fetch_assoc($result);
            print_r($data);
            if($email != $data['email'])
            {
                if($usr != $data['title'])
                {
                    echo $usr;
                    $errors['UNKOWN'] = "ERROR: UNKOWN ERROR :(";
                    $_SESSION['errors'] = $errors;
                    header("location: index.php");
                }
                else {
                    $errors['title'] = "ERROR: This username is already taken :(";
                    $_SESSION['errors'] = $errors;
                    ("location: index.php");
                }
            }
            else {
                $errors['email'] = "ERROR: This email is already registered in our systems :(";
                $_SESSION['errors'] = $errors;
                header("location: index.php");
            }
        }
        else{
            $hash = password_hash($pwd, PASSWORD_DEFAULT);        
            $sql = "INSERT INTO users (title, email, pwd) VALUES ('" . $usr . "', '" . $email . "', '" . $hash . "');";
            mysqli_query($mysqli, $sql);
            $errors['success'] = "You are now Registered, please sign in :)";
            $_SESSION['errors'] = $errors;
            $_SESSION['username'] = $data['title'];
            header("location: index.php");
        }   
    }
    else {
        $errors['passwords'] = "ERROR: Your passwords didn't match :(";
        print_r($errors);
        $_SESSION['errors'] = $errors;
        //header("location: index.php");
    }
}
else{
    $errors['hahah'] = "You think I'm that dumb????";
    $_SESSION['errors'] = $errors;
    header("location: index.php");
}






?>