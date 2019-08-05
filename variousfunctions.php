<?php







function logout(){
    session_unset();
    session_destroy();
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
        $char = str_split($tweet);
        $len = count($char);    
        for($i = 0; $i < $len; $i++){
            if($char[$i] == '#'){
                while($char[$i] != " ")
                {
                    $tags = $tags . $char[$i];
                    $i++;
                }
            }
            $tags = $tags + ".";
        }

    }
    return $tags;

}

























?>