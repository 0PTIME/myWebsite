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
$error = "none";
/******** SCUFFED WAY OF KEEPING THE USER LOGGED IN ************/
if(isset($_SESSION['username']))
{
    header("location: home.php");
    exit();
}

// checks if all the fields were entered
if(isset($_POST['usr']) && isset($_POST['pwd'])){
    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    // calls a function to determine if the input was the username or an email
    if(checkEmail($usr)){
        $email = $usr;
    }
    // connection to the database
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // builds a query and querys the database
    $sqlquery = "SELECT title, email, pwd FROM users WHERE title='" . $usr . "' OR email='" . $email . "'";
    $result = mysqli_query($mysqli, $sqlquery);
    // if only one row is sent back proceeds with the logging in proccess
    if(mysqli_num_rows($result) == 1)
    {
        // takes the query and saves the data to an array
        $data = mysqli_fetch_assoc($result);
        // if the user used an email
        if(isset($email)){
            // checks the email against the database
            if($email == $data['email'])
            {
                // if the password passes the hash check, logs in the user else error out
                if (password_verify($pwd, $data['pwd'])){
                    $_SESSION['username'] = $data['title'];
                    header("location: home.php");
                }
                else{
                    $errors['credentials'] = "Invalid Credentials...";
                }
            }
        }
        // makes sure that you are not checking the user if the email is set
        if(!isset($email))
        {
            // if the username entered equals the username in the database checks the password and logs the user in
            if($usr == $data['title']){
                if (password_verify($pwd, $data['pwd'])){
                    $_SESSION['username'] = $data['title'];
                    header("location: home.php");
                }
                else{
                    $errors['credentials'] = "Invalid Credentials...";
                } 
            }

        }
    } //  error msg for if the query pulls no results meaning that the username and password is no in the system
    elseif(mysqli_num_rows($result) == 0){
        $errors['dberror'] = "There is no account with the entered credentials";
    } 
    else{ // error msg for if there is more than 1 row which there should never be
        $errors['critical'] = "Huge fucking mistakes man";
    }
}
else{
    $errors['hahah'] = "Please Login or Register below";
}

$_SESSION['errors'] = $errors;
header("location: index.php");

    

?>
