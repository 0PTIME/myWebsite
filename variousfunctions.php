<?php







function logout(){
    session_unset();
    session_destroy();
}
function unLog(){
    session_unset();
    session_destroy();
    header("location: index.php");
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
    $id = strpos($tweet, '#');
    if($id == false) { return null; }
    else{
        $tags = "";
        $words = explode(' ', $tweet);
        $len = count($words);    
        for($i = 0; $i < $len; $i++){
            $char = str_split($words[$i]);
            if($char[0] == '#'){
                $tag = $words[$i];
                $tag = preg_replace("/[^a-zA-Z0-9]+/", "", $tag);
                if($tags != "") { $tags = $tags . "." . $tag; }                
                else { $tags = $tag; }
            }
        }
        return $tags;
    }
}
function getAts($tweet){
    $id = strpos($tweet, '@');
    if($id == false) { return null; }
    else{
        $tags = "";
        $words = explode(' ', $tweet);
        $len = count($words);    
        for($i = 0; $i < $len; $i++){
            $char = str_split($words[$i]);
            if($char[0] == '@'){
                $tag = $words[$i];
                $tag = preg_replace("/[^a-zA-Z0-9]+/", "", $tag);
                if($tags != "") { $tags = $tags . "." . $tag; }                
                else { $tags = $tag; }
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

























?>