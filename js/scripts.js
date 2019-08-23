window.onclick = function(event) {
    var classes = event.toElement.classList;
    console.log(classes);
    if (!event.toElement.classList.contains('tweetButton')) {
        if(!event.toElement.classList.contains('temp')){
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
}









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
    }, 50);
}
function like(tweetId){

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
           document.getElementById("like"+tweetId).innerHTML = xhttp.responseText;
        }
     }
    xhttp.open("GET", "yap.php?likeTweet=" + tweetId, true);
    xhttp.send();
}
function retweet(tweetId, retweetContent){
    var xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
           document.getElementById("retwt"+tweetId).innerHTML = xhttp.responseText;
        }
    }
    xhttp.open("POST", "yap.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("tweetId="+tweetId+"&retweet="+retweetContent);
}
function retweetLogic(tweetId){
    var promptText = prompt("Send a message with your retweet: ");
    if(promptText === ""){
        retweet(tweetId, promptText);
    }
    else if(promptText){
        retweet(tweetId, promptText);
    }
}
function showDropdown(id) {
    document.getElementById(id).classList.toggle("show");
}
function tagsAts(id, mentions, tags){
    if(mentions.search('.') != -1){
        var arrMentions = mentions.split('.');
        var menLen = arrMentions.length;
        for(var i = 0; i < menLen; i++){
            var doc = document.getElementById(id).innerHTML;
            var rep = doc.replace("@"+arrMentions[i], "<a href=\"search?keyword="+arrMentions[i]+"\" class=\"tweetLink\">@"+arrMentions[i]+"</a>");
            document.getElementById(id).innerHTML = rep;
        }        
    }
    if(tags.search('.') != -1){
        var arrTags = tags.split('.');
        var tagLen = arrTags.length;
        for(var j = 0; j < tagLen; j++){
            var doc = document.getElementById(id).innerHTML;
            var rep = doc.replace("#"+arrTags[j], "<a href=\"search?keyword=%23"+arrTags[j]+"\" class=\"tweetLink\">#"+arrTags[j]+"</a>");
            document.getElementById(id).innerHTML = rep;
        }        
    }
}
function delTweet(id){
    var xhttp = new XMLHttpRequest();    
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var response = xhttp.responseText;
            if(response == "wow"){
                location.reload(true);
            }
            else{
                document.getElementById("content"+id).innerHTML = response;
            }
        }
    }
    xhttp.open("POST", "yap.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("tweetId="+id+"&delTweet=true");
}