<?php
include_once('tbs_class.php');
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();

$tbs = new clsTinyButStrong;
$tbs->LoadTemplate('templates/register.html');
/******** VARIABLES FOR TBS ************/
$icon = "icon.png";
$style = "style.css";
$title = "Yapper. Speak, Shake, Fetch.";
$font = "Play&display=swap";
$error = "none";

/******** SCUFFED WAY OF KEEPING THE USER LOGGED IN ************/
if(isset($_SESSION['username']))
{
    header("location: home");
    exit();    
}

// checks if all the data from post has been gathered
if(isset($_POST['usrReg']) && isset($_POST['pwd']) && isset($_POST['usrEmail']) && isset($_POST['pwdTwo'])){
    // sets some local variables
    $usr = $_POST['usrReg'];
    $pwd = $_POST['pwd'];
    $pwdTwo = $_POST['pwdTwo'];
    $email = $_POST['usrEmail'];
    // first checks if the passwords match before proceeding
    if($pwd == $pwdTwo)
    {
        // connect to the database and fail if connection fails
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // builds a query and querys the database
        $sqlquery = "SELECT title, email FROM users WHERE title='" . $usr . "' OR email='" . $email . "'";
        $result = mysqli_query($mysqli, $sqlquery);
        // if there are no results proceeds to add the user to the database and log them in
        if(mysqli_num_rows($result) == 0)
        {
            $hash = password_hash($pwd, PASSWORD_DEFAULT);        
            $sql = "INSERT INTO users (title, email, pwd, follows, followers) VALUES ('" . $usr . "', '" . $email . "', '" . $hash . "', '" . $usr . "', '0');";
            mysqli_query($mysqli, $sql);
            $errors['success'] = "You are now Registered, please sign in :)";
            $_SESSION['username'] = $usr;
        }
        // if there is a result from the query check what error to return to the user
        elseif(mysqli_num_rows($result) == 1)
        {
            // puts the data from the query into an array
            $data = mysqli_fetch_assoc($result);
            // test if the email they entered is already in the database
            if($email == $data['email'])
            {
                $errors['email'] = "ERROR: This email is already registered in our systems :(";
            }
            // test if the username they added is in the database
            elseif($usr == $data['title'])
            {
                $errors['title'] = "ERROR: This username is already taken :(";
            }
            // if something else went wrong then fuck man
            elseif($usr != $data['title'] && $email != $data['email'])
            {
                $errors['UNKOWN'] = "ERROR: UNKNOWN ERROR :(";
            }
        }
        else // error msg for if the query had more that 1 row
        {
            $errors['badquery'] = "ERROR: Bad stuff be a happen'n :(";
        }
    }
    else { // error msg for if the passwords that they entered didn't match eachother
        $errors['pwderror'] = "ERROR: Your passwords didn't match :(";
    }
}
else { // error msg for is they either didn't enter all the field or they went to the wrong url
    $errors['hahah'] = "Please Login or Register below";
}

$_SESSION['errors'] = $errors;
header("location: index");

// debugging tools
// echo "the query result: ";
// print_r($data);
// echo "<br>the number of rows: ";
// echo mysqli_num_rows($result);
// echo "<br>the entered info: " . $usr . ", " . $email . ", " . $pwd; 
// foreach($errors as $key => $value)
// {
//     if(isset($errors[$key])) { $error = $errors[$key]; logout(); }
// }
// echo "<br>the error: " . $error;
// echo "<br>the number of errors: " . count($errors);


?>