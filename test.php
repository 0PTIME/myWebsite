<?php
include_once('variousfunctions.php');
session_set_cookie_params(7200);
session_start();


// tweet5d4b2b73d51aa5.88215472
// testing
// $response = retweetTweet("tweet5d4b45af301904.11947352", "testing");
// echo $response;


$response = getAts("testing @kappa

still testing @enoch @testing
wow");
echo $response;

?>