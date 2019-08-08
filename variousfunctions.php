<?php







function logout(){
    session_unset();
    session_destroy();
}
function unLog(){
    session_unset();
    session_destroy();
    header("location: index");
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
function getTags($tweet){
    if(strpos($tweet, '#') === false) { return null; }
    else{
        $tags = "";
        $words = explode(' ', $tweet);
        $len = count($words);    
        for($i = 0; $i < $len; $i++){
            $char = str_split($words[$i]);
            if($char[0] == '#'){
                $tag = $words[$i];
                $tag = preg_replace("/[^a-zA-Z0-9]+/", "", $tag);
                $tags = $tags . $tag . ".";
            }
        }
        return $tags;
    }
}
function getAts($tweet){
    if(strpos($tweet, '@') === false) { return null; }
    else{
        $tags = "";
        $words = explode(' ', $tweet);
        $len = count($words);   
        for($i = 0; $i < $len; $i++){
            $char = str_split($words[$i]);
            if($char[0] == '@'){
                $tag = $words[$i];
                $tag = preg_replace("/[^a-zA-Z0-9]+/", "", $tag);
                $tags = $tags . $tag . ".";
            }
        }
        return $tags;
    }
}
function addFollow($currentFollows, $adding){
    echo $currentFollows . "<br>";
    if(!isset($currentFollows)){
        $currentFollows = $adding . ".";
    }
    else{
        $currentFollows = $currentFollows . $adding . ".";
    }
    return $currentFollows;
}
function remFollow($currentFollows, $remmoving){
    $newFollows = "";
    if(strpos($currentFollows, $remmoving) !== false) {
        $arrayFollows = explode('.', $currentFollows);
        $dump = array_search($remmoving, $arrayFollows);
        unset($arrayFollows[$dump]);
        $newFollows = implode('.', $arrayFollows);
    }
    return $newFollows;
}
function checkFollows($currentFollows, $check){
    if(isset($currentFollows)){
        if(strpos($currentFollows, $check) !== false){ return false; }
        else { return true; }
    }
    else { return true; }
    
}
function notifyMentions($ats, $identifier){
    $mentions = explode('.', $ats);
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    foreach($mentions as $mention){
        $sqlqueryNotify = "SELECT title  FROM users WHERE title='" . $mention . "'";
        $result = mysqli_query($mysqli, $sqlqueryNotify);
        if(mysqli_num_rows($result) == 1){
            $notifications = getNotifications($mention);
            if($notifications != "error"){
                $notifications = $notifications . $identifier . " ";
                $queryAddNotification = "UPDATE users SET notifications='" . $notifications . "' WHERE title='" . $mention . "'";
                mysqli_query($mysqli, $queryAddNotification);
            }
        }
    }
}
function getNotifications($user){
    $mysqli = mysqli_connect("localhost", "website", "data", "website_users");
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sqlqueryNotify = "SELECT title, notifications  FROM users WHERE title='" . $user . "'";
    $result = mysqli_query($mysqli, $sqlqueryNotify);
    if(mysqli_num_rows($result) == 1){
        $data = mysqli_fetch_assoc($result);
        $notifications = $data['notifications'];
        if($notifications == NULL){ $notifications = ""; }
        return $notifications;
    }
    else { return "error"; }
}

























?>