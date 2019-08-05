function showButtons(){
    var buttons = document.getElementById("buttons");
    var register = document.getElementById("register");
    var login = document.getElementById("login");
    buttons.style.display = "block";
    register.style.display = "none";
    login.style.display = "none";
}
function login(){
    var buttons = document.getElementById("buttons");
    var register = document.getElementById("register");
    var login = document.getElementById("login");
    buttons.style.display = "none";
    register.style.display = "none";
    login.style.display = "block";
}
function register(){
    var buttons = document.getElementById("buttons");
    var register = document.getElementById("register");
    var login = document.getElementById("login");
    buttons.style.display = "none";
    register.style.display = "block";
    login.style.display = "none";
}
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
function checkerror(error){
    if(error == "")
    {
        var errorpwd = document.getElementById("error");
        errorpwd.style.display = "none";
    }
    if(error != ""){
        errorpwd.style.display = "block";
    }
}
function darkMode(){
    if(document.body.style.background == "#1da1f2"){
        document.body.style.background = "#1a1e2e";
        document.body.style.color = "#c7d2ff";
    }
    if(document.body.style.background == "#1a1e2e"){
        document.body.style.background = "#1da1f2";
        document.body.style.color = "black";
    }
}