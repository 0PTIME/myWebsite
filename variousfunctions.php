<?php






// function to unset and destroy the session effectivly loggin out the user
function logout(){
    session_unset();
    session_destroy();
}
// does the same as logout() but also redirects them to the login page
function unLog(){
    session_unset();
    session_destroy();
    header("location: index");
}
// returns true if the submitted string is in email form
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
// function to take a tweet and return a string of hashtags formatted in a standardized fashion
function getTags($tweet){
    $tags = array();
    if(strpos($tweet, '#') === false) { return ""; exit(); } // does a quick check to see if # is a part of the tweet
    else{
        $words = explode(' ', $tweet); // turns the tweet into an array of words
        if(count($words) > 1){
            foreach($words as $word){ // loops through all the words in the tweet
                $char = str_split($word);
                if($char[0] == '#'){ // checks the first character and tests if its a #
                    unset($char[0]);
                    $tag = implode("", $char);
                    $tags[count($tags)] = $tag;
                    
                }
            }
            foreach($tags as $value){
                $tagCheck = preg_replace("/[^a-zA-Z0-9]+/", "", $value);
                if($value != $tagCheck){
                    return "";
                    exit();
                }
            }
            $tags = implode(".", $tags);
        }
        elseif(count($words) == 1){
            $charArray = str_split($tweet);
            unset($charArray[0]);
            $tags = implode("", $charArray);
        }
        return $tags; // returns the formatted string of mentions
    }
}
// function to take a tweet and return a string of mentions formatted in a standardized fassion
function getAts($tweet){
    $tags = array();
    if(strpos($tweet, '@') === false) { return ""; } // does a quick check to see if @ is a part of the tweet
    else{
        $words = explode(' ', $tweet); // turns the tweet into an array of words
        if(count($words) > 1){
            foreach($words as $word){ // loops through all the words in the tweet
                $char = str_split($word);
                if($char[0] == '@'){ // checks the first character and tests if its a @
                    unset($char[0]);
                    $tag = implode("", $char);
                    $tags[count($tags)] = $tag;
                    
                }
            }
            foreach($tags as $value){
                $tagCheck = preg_replace("/[^a-zA-Z0-9]+/", "", $value);
                if($value != $tagCheck){
                    return "";
                    exit();
                }
            }
            $tags = implode(".", $tags);
        }
        elseif(count($words) == 1){
            $charArray = str_split($tweet);
            unset($charArray[0]);
            $tags = implode("", $charArray);
        }
        return $tags; // returns the formatted string of mentions
    }
}
// function that takes your current follows and someone you are trying to add and adds it then returns the current follows list
// these functions rely on the checkFollows function to determine which one is called
function addFollow($adding){
    $currentFollows = trim(getFollows($_SESSION['username']));
    if($currentFollows == "" || !isset($currentFollows)){ $newFollows = $adding;}
    else{
        $arrayFollows = explode('.', $currentFollows); // stores all of your follows in an array
        $arrayFollows[count($arrayFollows)] = $adding; // adds the new person to the array
        $newFollows = implode('.', $arrayFollows); // remakes the string based on the same array of followers now without the person you are trying to add
    }
    if($newFollows != $currentFollows && isset($newFollows) && getFollowers($adding) !== null){
        addFollower($adding);
        return $newFollows; 
    } // quick little test to make sure that the person was added
    else { return $currentFollows; }
}
// function that takes your current follows and someone you are trying to remove and removes them from the list then returns the current list of follows
function remFollow($remmoving){
    $currentFollows = trim(getFollows($_SESSION['username']));
    $currentFollows = trim($currentFollows);
    $arrayFollows = explode('.', $currentFollows); // stores all of your follows in an array
    $dump = array_search($remmoving, $arrayFollows); // finds the index of the person you are trying to follow
    unset($arrayFollows[$dump]); // drops out the person out of the array
    $newFollows = implode('.', $arrayFollows); // remakes the string based on the same array of followers now without the person you are trying to add
    if($newFollows != $currentFollows && isset($newFollows) && getFollowers($remmoving) !== null){ 
        remFollower($remmoving);
        return $newFollows; 
    } // quick little test to make sure that the person was added
    else { return $currentFollows; }
}
// functions to remove current logged in person from a list of someone elses followers
function remFollower($otp){
    $theirFollowers = trim(getFollowers($otp));// a string of the formatted followers
    $currentUser = $_SESSION['username']; // current logged in user
    if($currentUser == $theirFollowers){
        setFollowers("", $otp);
    }
    else{
        $arrayFollowers = explode('.', $theirFollowers); // stores all of your follows in an array
        $dump = array_search($currentUser, $arrayFollowers); // finds the index of the person you are trying to follow
        unset($arrayFollowers[$dump]); // drops out the person out of the array
        $newFollowers = implode('.', $arrayFollowers); // remakes the string based on the same array of followers now without the person you are trying to add

        if($newFollowers != $theirFollowers && isset($newFollowers)){
            echo $newFollowers;
            setFollowers($newFollowers, $otp); 
        } // quick little test to make sure that the person was added
    else{ setFollowers($theirFollowers, $otp); }
    }
}
// function to add a follower to a list of followers
function addFollower($otp){
    $theirFollowers = trim(getFollowers($otp));// a string of the formatted followers
    $currentUser = $_SESSION['username']; // current logged in user
    if($theirFollowers == "" || !isset($theirFollowers)){ $newFollowers = $currentUser;}
    else{
        $arrayFollowers = explode('.', $theirFollowers); // stores all of your follows in an array
        $arrayFollowers[count($arrayFollowers)] = $currentUser;
        $newFollowers = implode('.', $arrayFollowers); // remakes the string based on the same array of followers now without the person you are trying to add
    }
    setFollowers($newFollowers, $otp);
}
// takes a list of followers and a user and updates the database
function setFollowers($updatedFollowers, $user){    
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $queryUpdateFollowers = "UPDATE users SET followers='" . $updatedFollowers . "' WHERE title='" . $user . "'"; // query to update the table with the new notifications
    echo $queryUpdateFollowers;
    mysqli_query($mysqli, $queryUpdateFollowers); // executes the update
}
// function that returns true if the person you are trying to add is a part of your current follows list
function checkFollows($check){
    $currentFollows = trim(getFollows($_SESSION['username']));
    if($currentFollows !== null && isset($check)){
        $currentFollows = trim($currentFollows);
        $arrayFollows = explode('.', $currentFollows); // stores all of your follows in an array
        foreach($arrayFollows as $follow){
            if($check == trim($follow)){ // checks each follow to see if it matches the one your are trying to add
                return true;
            }
        }
        return false;
    }
    else { return false; }
    
}
// function that adds a notification to the mentions current notifications
function notifyMentions($ats, $identifier){
    $mentions = explode('.', $ats); // takes the string of mentions produced 
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    foreach($mentions as $mention){ // loops through all the mentions
        $sqlqueryNotify = "SELECT title  FROM users WHERE title='" . $mention . "'"; // query to find a user
        $result = mysqli_query($mysqli, $sqlqueryNotify);
        if(mysqli_num_rows($result) == 1){ // makes sure that the mentions are real accounts
            if($notifications = getNotifications($mention)){ // gets the current notifications
                $arrayNotifications = explode(' ', $notifications); // stores all of your notifications in an array
                $arrayNotifications[count($arrayNotifications)] = $identifier; // adds the new notifications to the array
                $newNotifications = implode(' ', $arrayNotifications); // remakes the string based on the same array of notifications now with the notificaition
                $queryAddNotification = "UPDATE users SET notifications='" . $newNotifications . "' WHERE title='" . $mention . "'"; // query to update the table with the new notifications
                mysqli_query($mysqli, $queryAddNotification); // executes the update
            }
            else{
                $newNotifications = $identifier;
                $queryAddNotification = "UPDATE users SET notifications='" . $newNotifications . "' WHERE title='" . $mention . "'"; // query to update the table with the new notifications
                mysqli_query($mysqli, $queryAddNotification); // executes the update
            }
        }
    }
    mysqli_close($mysqli);
}
// function that takes a user and returns their current list of notifications, returns false if the user dosen't exist
function getNotifications($user){
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sqlqueryNotify = "SELECT title, notifications  FROM users WHERE title='" . $user . "'"; // query the user
    $result = mysqli_query($mysqli, $sqlqueryNotify);
    mysqli_close($mysqli);
    if(mysqli_num_rows($result) == 1){ // if the search for the user only brought back one result then return the notifications
        $data = mysqli_fetch_assoc($result);
        $notifications = $data['notifications'];
        if($notifications == NULL){ $notifications = ""; }
        return $notifications;
    }
    else { return false; } // returns if the user doesn't exist
}
// function that takes a user and returns their current list of Follows, returns false if the user dosen't exist
function getFollows($user){
    if(isset($user)){
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryFollows = "SELECT title, follows  FROM users WHERE title='" . $user . "'"; // query the user
        $result = mysqli_query($mysqli, $sqlqueryFollows);
        mysqli_close($mysqli);
        if(mysqli_num_rows($result) == 1){ // if the search for the user only brought back one result then return the Follows
            $data = mysqli_fetch_assoc($result);
            $follows = $data['follows'];
            if($follows == NULL){ $follows = ""; }
            return $follows;
        }
        else { return false; } // returns if the user doesn't exist
    }
    else { return false; } // returns if the user doesn't exist
}
// function that takes a user and returns their current list of Followers, returns false if the user dosen't exist
function getFollowers($user){
    if(isset($user)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryFollowers = "SELECT title, followers  FROM users WHERE title='" . $user . "'"; // query the user
        $result = mysqli_query($mysqli, $sqlqueryFollowers);
        mysqli_close($mysqli);
        if(mysqli_num_rows($result) == 1){ // if the search for the user only brought back one result then return the Followers
            $data = mysqli_fetch_assoc($result);
            $followers = $data['followers'];
            if($followers == NULL){ $followers = ""; }
            return $followers;
        }
        else { return false; } // returns if the user doesn't exist
    }
    else { return false; } // returns if the user doesn't exist
}
//  function that will take a mysql timestamp and make it look more user friendly
function getTimespan($time){
    if(isset($time)){
        $ts = new DateTime();
        $ts->setTimestamp(strtotime($time));
        $cur = new DateTime();
        $difference = $cur->diff($ts);
        if ($difference->format("%i") == 0){
            $out = $difference->format("less than a minute") . " ago";
        }
        elseif ($difference->format("%h") == 0){
            $out = $difference->format("%i minutes") . " ago";
        }
        elseif ($difference->format("%a") == 0){
            $out = $difference->format("%h hours %i minutes") . " ago";
        }
        elseif ($difference->format("%a") < 7){
            $out = $difference->format("%a days") . " ago";
        }
        elseif ($difference->format("%m") == 0) {
            $days = $difference->format("%a");
            $out = sprintf("%d weeks %d days", floor($days / 7), $days % 7) . " ago";
        }
        elseif ($difference->format("%y") == 0){
           $out = $difference->format("%m months") . " ago";
        }
        else{
            $default = date('m/d/Y', strtotime($time));
            $out = "on ". $default;
        }        
        return $out;
    }
    else{
        return false;
    }
}
// little function that takes a string and returns a string of the first 5 characters
function getPrefix($id){
    $newCheck ="";
    $checkifTweet = str_split($id);
    for($i = 0; $i < 5; $i++){
        $newCheck = trim($newCheck . $checkifTweet[$i]);
    }
    return $newCheck;
}
// takes the tweet ID and returns how many replies that tweet has
function getNumComments($tweetId){
    $sqlConnection = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection to 
    if (!$sqlConnection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $queryReply = "SELECT tweetID FROM replies WHERE tweetID='" . $tweetId . "'";
    $replyResults = mysqli_query($sqlConnection, $queryReply);
    mysqli_close($sqlConnection);
    return mysqli_num_rows($replyResults);
}

























?>