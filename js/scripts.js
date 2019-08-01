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
function checkerror(){
    if([onshow.error] == "")
    {
        var errorpwd = document.getElementById("errorpwd");
        // var errortitle = document.getElementById("errortitle");
        // var erroremail = document.getElementById("erroremail")
        errorpwd.style.display = "none";
        // errortitle.style.display = "none";
        // erroremail.style.display = "none";
    }
    else{
        errorpwd.style.display = "block";
        // errortitle.style.display = "none";
        // erroremail.style.display = "none";
    }
}