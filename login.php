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
    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    if(checkEmail($usr)){
        $email = $usr;
    }
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sqlquery = "SELECT title, email, pwd FROM users WHERE title='" . $usr . "'";
    $result = mysqli_query($mysqli, $sqlquery);
    if(mysqli_num_rows($result) > 1 )
    {
        $data = mysqli_fetch_assoc($result);
        print_r($data);
        if($email == $data['email'])
        {
            if (password_verify($pwd, $data['pwd'])){
                $_SESSION['username'] = $data['title'];
                header("location: home.php");
            }
        }
        else if($usr == $data['title'])
        {
            echo "user yaes";
            if (password_verify($pwd, $data['pwd'])){
                $_SESSION['username'] = $data['title'];
                header("location: home.php");
            }       
        }
        else{
            $errors['credentials'] = "Invalid Credentials...";
            $_SESSION['errors'] = $errors;
            header("location: index.php");
        }
    }
    else{
        $errors['critical'] = "Huge fucking mistakes man";
        $_SESSION['errors'] = $errors;
        header("location: index.php");
    }
}
else{
    $errors['hahah'] = "Not Logged in, please log in below";
    $_SESSION['errors'] = $errors;
    header("location: index.php");
}

    

?>
