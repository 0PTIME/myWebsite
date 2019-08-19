// function to show the buttons and hide the login and register forms
function showButtons(){
    var buttons = document.getElementById("buttons");
    var register = document.getElementById("register");
    var login = document.getElementById("login");
    buttons.style.display = "block";
    register.style.display = "none";
    login.style.display = "none";
}
// function to show the login form and hide the register form and buttons
function login(){
    var buttons = document.getElementById("buttons");
    var register = document.getElementById("register");
    var login = document.getElementById("login");
    buttons.style.display = "none";
    register.style.display = "none";
    login.style.display = "block";
}
// function to show the register form and hide the login form and buttons
function register(){
    var buttons = document.getElementById("buttons");
    var register = document.getElementById("register");
    var login = document.getElementById("login");
    buttons.style.display = "none";
    register.style.display = "block";
    login.style.display = "none";
}
// takes an html id and fades it into oblivion
function fadeTarget(target){
    var fadeElement = document.getElementById(target);
    var curve = .00001;
    var fadeEffect = setInterval(function () {
        curve = curve * 1.03;
        if (!fadeElement.style.opacity) {
            fadeElement.style.opacity = 1;
        }
        if (fadeElement.style.opacity > 0) {
            fadeElement.style.opacity -= curve;
        } else {
            clearInterval(fadeEffect);
        }
    }, 20);
}
function like(tweetId){

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
           document.getElementById(tweetId).innerHTML = xhttp.responseText;
        }
     }
    xhttp.open("GET", "like.php?tweetId=" + tweetId, true);
    xhttp.send();
}