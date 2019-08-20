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
function getTimespan($time){ // I found this online btw
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
            $out = $difference->format("%h hours") . " ago";
        }
        elseif ($difference->format("%a") < 25){
            $out = $difference->format("%a days") . " ago";
        }
        elseif ($difference->format("%a") < 35) {
            $out = "about a month ago";
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
function getPrefix($id){ // used to check what an identifier is referencing 
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
function getProfile($user){
    if(isset($user)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlquery = "SELECT title, date_added, description, follows, followers, numfollowers, likes FROM users WHERE title='" . $user . "'"; // query the user
        $result = mysqli_query($mysqli, $sqlquery);
        mysqli_close($mysqli);
        if(mysqli_num_rows($result) == 1){ // if the search for the user only brought back one result then return the Followers
            $data = mysqli_fetch_assoc($result);
            $myArray['title'] = $data['title'];
            $myArray['dateCreated'] = getTimespan($data['date_added']);
            if($data['description'] == null){ $myArray['description'] = ":)"; }
            else{ $myArray['description'] = $data['description']; }
            $myArray['follows'] = $data['follows'];
            $myArray['followers'] = $data['followers'];
            $myArray['numFollowers'] = $data['numfollowers'];
            $myArray['likes'] = $data['likes'];
            
        }
        else { return false; } // returns if the user doesn't exist
        return $myArray;
    }
    else { return false; } // returns if the user doesn't exist
}
// takes a tag as a string does a database query and returns all the unique ids in an array
function getTweetsWithTag($tag){
    $myArray = array();
    $sqlConnection = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection to 
    if (!$sqlConnection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $now = date('Y-m-d G:i:s');
    $monthago = date('Y-m-d G:i:s', strtotime("-1 months"));
    $queryTweetsWithTag = "SELECT tags, time, uniqueid FROM tweets WHERE time BETWEEN '" . $monthago . "' AND '" . $now . "' AND tags <> \"\" ORDER BY time DESC";
    $result = mysqli_query($sqlConnection, $queryTweetsWithTag);
    mysqli_close($sqlConnection);
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            $arrayTags = explode('.', $data['tags']);
            foreach($arrayTags as $checkTag){
                if($checkTag == $tag){
                    $myArray[count($myArray)] = $data['uniqueid'];
                }
            }
        }
    }
    return $myArray;
}
// takes a tag as a string does a database query and returns all the unique ids in an array
function getRepliesWithTag($tag){
    $myArray = array();
    $sqlConnection = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection to 
    if (!$sqlConnection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $now = date('Y-m-d G:i:s');
    $monthago = date('Y-m-d G:i:s', strtotime("-1 months"));
    $queryRepliesWithTag = "SELECT tags, time, uniqueid FROM replies WHERE time BETWEEN '" . $monthago . "' AND '" . $now . "' AND tags <> \"\" ORDER BY time DESC";
    $result = mysqli_query($sqlConnection, $queryRepliesWithTag);
    mysqli_close($sqlConnection);
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            $arrayTags = explode('.', $data['tags']);
            foreach($arrayTags as $checkTag){
                if($checkTag == $tag){
                    $myArray[count($myArray)] = $data['uniqueid'];
                }
            }
        }
    }
    return $myArray;
}
// takes a tag and returns an array of identifiers referencing all the retweets that has that tag
function getRetweetsWithTag($tag){
    $myArray = array();
    $sqlConnection = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection to 
    if (!$sqlConnection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $now = date('Y-m-d G:i:s');
    $monthago = date('Y-m-d G:i:s', strtotime("-1 months"));
    $queryRetweetsWithTag = "SELECT tags, time, uniqueid FROM retweets WHERE time BETWEEN '" . $monthago . "' AND '" . $now . "' AND tags <> \"\" ORDER BY time DESC";
    $result = mysqli_query($sqlConnection, $queryRetweetsWithTag);
    mysqli_close($sqlConnection);
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            $arrayTags = explode('.', $data['tags']);
            foreach($arrayTags as $checkTag){
                if($checkTag == $tag){
                    $myArray[count($myArray)] = $data['uniqueid'];
                }
            }
        }
    }
    return $myArray;
}
// function that takes a tweets unique ID and returns all the information for that tweet
function getTweet($tweetId){
    $sqlConnection = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection to 
    if (!$sqlConnection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $queryTweets = "SELECT ID, content, tags, ats, time, likes, numretweets, uniqueid FROM tweets WHERE uniqueid IN ('" . $tweetId . "')";
    $result = mysqli_query($sqlConnection, $queryTweets);
    mysqli_close($sqlConnection);
    if(mysqli_num_rows($result) == 1){
        $data = mysqli_fetch_assoc($result);
        $myArray['title'] = $data['ID'];
        $myArray['content'] = $data['content'];
        $myArray['tags'] = $data['tags'];
        $myArray['mentions'] = $data['ats'];
        $myArray['time'] = $data['time'];
        $myArray['likes'] = $data['likes'];
        $myArray['tweetId'] = $data['uniqueid'];
        $myArray['numRetweets'] = $data['numretweets'];
        return $myArray;
    }
    else{ return null; }

}
// functions that returns all the likes for a user
function getLikes($user){
    if(isset($user)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryLikes = "SELECT title, likes  FROM users WHERE title='" . $user . "'"; // query the user
        $result = mysqli_query($mysqli, $sqlqueryLikes);
        mysqli_close($mysqli);
        if(mysqli_num_rows($result) == 1){ // if the search for the user only brought back one result then return the likes
            $data = mysqli_fetch_assoc($result);
            $likes = $data['likes'];
            if($likes == NULL){ $likes = ""; }
            return $likes;
        }
        else { return false; } // returns if the user doesn't exist
    }
    else { return false; } // returns if the user doesn't exist
}
function setLikes($user, $likes){
    if(isset($user) && isset($likes)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryLikes = "UPDATE users SET likes='" . $likes . "' WHERE title='" . $user ."'"; // query for update
        mysqli_query($mysqli, $sqlqueryLikes);
        mysqli_close($mysqli);
    }
    else { return false; } // returns if the user doesn't exist
}
// returns true if the like is not part of the like list
function checkWStandard($needle, $haystack){
    if($haystack === null){ $haystack = ""; }
    if($haystack == ""){
        return true;
    }
    else{
        $arrayLikes = explode(" ", $haystack);
        foreach($arrayLikes as $like){
            if($needle == trim($like)){
                return false;
            }
        }
        return true;
    }
}
// takes two strings and removes one from the other
function addLike($like, $currLikes){
    if($currLikes == "" || !isset($currLikes)){
        $newlikes = trim($like);
    }
    else{
        $arrayLikes = explode(" ", $currLikes);
        $arrayLikes[count($arrayLikes)] = trim($like);
        $newlikes = implode(" ", $arrayLikes);
    }
    return $newlikes;
}
function remLike($like, $currLikes){
    $arrayLikes = explode(" ", $currLikes);
    $dump = array_search($like, $arrayLikes);
    unset($arrayLikes[$dump]);
    $newlikes = implode(" ", $arrayLikes);
    return $newlikes;
}
function updateLikes($tweetId, $num){
    if(isset($tweetId) && isset($num)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryLikes = "UPDATE tweets SET likes='" . $num . "' WHERE uniqueid='" . $tweetId ."'"; // query for update
        mysqli_query($mysqli, $sqlqueryLikes);
        mysqli_close($mysqli);
    }
    else { return false; } // returns if the user doesn't exist
}
function updateRetweets($tweetId, $num){
    if(isset($tweetId) && isset($num)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryLikes = "UPDATE tweets SET numretweets='" . $num . "' WHERE uniqueid='" . $tweetId ."'"; // query for update
        mysqli_query($mysqli, $sqlqueryLikes);
        mysqli_close($mysqli);
    }
    else { return false; } // returns if the user doesn't exist
}
function setRetweets($user, $retweets){
    if(isset($user) && isset($retweets)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryRetweets = "UPDATE users SET retweets='" . $retweets . "' WHERE title='" . $user ."'"; // query for update
        mysqli_query($mysqli, $sqlqueryRetweets);
        mysqli_close($mysqli);
    }
    else { return false; } // returns if the user doesn't exist
}
// takes two strings and adds one to the other
function addRetweet($retweet, $currRetweet){
    if($currRetweet == "" || !isset($currLikes)){
        $newRetweets = trim($retweet);
    }
    else{
        $arrayRetweet = explode(" ", $currRetweet);
        $arrayRetweet[count($arrayRetweet)] = trim($retweet);
        $newRetweets = implode(" ", $arrayRetweet);
    }
    return $newRetweets;
}
function remRetweet($retweet, $currRetweet){
    $arrayRetweet = explode(" ", $currRetweet);
    $dump = array_search($retweet, $arrayRetweet);
    unset($arrayRetweet[$dump]);
    $newRetweets = implode(" ", $arrayRetweet);
    return $newRetweets;
}
// takes an Identifier and determines what you are liking and sends out the like
function likeTweet($tweetId){
    if(isset($tweetId)){
        $prefix = getPrefix($tweetId);
        if($prefix == "tweet"){
            $currLikes = getLikes($_SESSION['username']);
            $tweet = getTweet($tweetId);
            if(checkWStandard($tweetId, $currLikes)){
                $newLikes = addLike($tweetId, $currLikes);
                setLikes($_SESSION['username'], $newLikes);
                $numLikes = $tweet['likes'] + 1;
            }
            else{
                $newLikes = remLike($tweetId, $currLikes);
                setLikes($_SESSION['username'], $newLikes);
                $numLikes = $tweet['likes'] - 1;
            }
            updateLikes($tweetId, $numLikes);
            return $numLikes;
        }
        if($prefix == "reply"){

        }
        if($prefix == "retwt"){

        }
    }
}
// $tweetId is the identifier of the tweet being retweeted and content is what they data they are attaching
function makeRetweet($tweetId, $content){
    $usr = $_SESSION['username'];
    $tags = getTags($content); // calls the function that returns all the tags in were in the tweet
    $ats = getAts($content); // calls the function that returns all the people that were mentioned in the tweet
    $identifier = uniqid("retwt", true); // creates a unique id based on the the current time in microseconds with the option to make it even more unique turned on
    $likes = 0;
    $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // creates a mysql query that is used to insert a new row with the information created when the user submits the tweet
    $sql = "INSERT INTO retweets (ID, tweetID, content, tags, ats, likes, uniqueid) VALUES ('" . $usr . "', '" . $tweetId . "', '" . $content . "', '" . $tags . "', '" . $ats . "', '" . $likes . "', '" . $identifier . "');";
    mysqli_query($mysqli, $sql); // executes the query
    mysqli_close($mysqli);
    if($ats != ""){ // if the get tweets function didn't return a string without any values in it calls the notifyMentions function and passes the tweet id
        notifyMentions($ats, $identifier);
    }
    return $identifier;
}
function delRetweet($identifier){
    $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    // deletes a retweet
    $sql = "DELETE FROM retweets where uniqueid='" . $identifier . "'";
    mysqli_query($mysqli, $sql); // executes the query
}
// handles all the logic for making a tweet
function retweetTweet($tweetId, $content){
    if(isset($tweetId) && isset($content)){
        $user = $_SESSION['username'];
        $currRetweets = getRetweets($_SESSION['username']);
        $tweet = getTweet($tweetId);
        echo $tweet['numretweets'];
        $mysqli = mysqli_connect("localhost", "tweets", "tweets", "YAPPER"); // DB connection
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT ID, tweetID, uniqueid FROM retweets WHERE ID='" . $user . "' AND tweetID='" . $tweetId . "'";
        $retweetResults = mysqli_query($mysqli, $sql); // executes the query
        mysqli_close($mysqli);
        if(mysqli_num_rows($retweetResults) == 1){
            $data = mysqli_fetch_assoc($retweetResults);
            delRetweet($data['uniqueid']);
            $newRetweets = remRetweet($data['uniqueid'], $currRetweets);
            setRetweets($_SESSION['username'], $newRetweets);
            $numRetweets = $tweet['numRetweets'] - 1;
        }
        if(mysqli_num_rows($retweetResults) == 0){
            $identifier = makeRetweet($tweetId, $content);
            $newRetweets = addRetweet($identifier, $currRetweets);
            setRetweets($_SESSION['username'], $newRetweets);
            $numRetweets = $tweet['numRetweets'] + 1;
        }
        updateRetweets($tweetId, $numRetweets);
        return $numRetweets;
    }
}
function getRetweets($user){
    if(isset($user)){ // makes sure that the they passed value, returns false if not
        $mysqli = mysqli_connect("localhost", "website", "data", "website_users"); // connect to the users db
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sqlqueryRetweets = "SELECT title, retweets  FROM users WHERE title='" . $user . "'"; // query the user
        $result = mysqli_query($mysqli, $sqlqueryRetweets);
        mysqli_close($mysqli);
        if(mysqli_num_rows($result) == 1){ // if the search for the user only brought back one result then return the likes
            $data = mysqli_fetch_assoc($result);
            $retweets = $data['retweets'];
            if($retweets == NULL){ $retweets = ""; }
            return $retweets;
        }
        else { return false; } // returns if the user doesn't exist
    }
    else { return false; } // returns if the user doesn't exist
}


























?>