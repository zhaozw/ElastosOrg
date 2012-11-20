(function(){
  adapter.log("gerrit client");
  console.log(adapter.utils.getOpenID());
  adapter.utils.wait(adapter.utils.domReady,init);
  function init(){
      var register = document.getElementsByTagName("a")[7];
            var singin = document.getElementsByTagName("a")[8];
            if (singin.innerHTML == "Sign In" || singin.innerHTML == "登录") {
                login();
           adapter.utils.addEvent(singin,"click",function(){
             //setTimeout(login,10);
             login();
           });
      }
  }
  function login() {
        var openID, input, siginButton;
        openID = adapter.utils.getOpenID();
        adapter.utils.$class("GJEA35ODJH")[2].style.display="none";
        if (openID) {
	    var singin = adapter.utils.$tag("a")[8];
            adapter.utils.fireEvent(singin, "click");
            input = adapter.utils.$class("GJEA35ODGM")[0].style.display="none";
            input = adapter.utils.$class("GJEA35ODGM")[1].style.display="none";
            adapter.utils.$class("gwt-HTML")[0].style.display="none";
            input = adapter.utils.$class("GJEA35ODIM")[0]; //openid input
            singinButton = adapter.utils.$class("gwt-Button")[1]; //singin button
            input.value = openID;
            //adapter.utils.fireEvent(singinButton, "click");
        } else {
           ;// window.location.href = "http://" + adapter.config.wordpress.url + "/wp-login.php?action=login&redirect_to=http%3A%2F" + adapter.config.wordpress.url;
        }
    }
})()
