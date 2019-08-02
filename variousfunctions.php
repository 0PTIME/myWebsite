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

























?>