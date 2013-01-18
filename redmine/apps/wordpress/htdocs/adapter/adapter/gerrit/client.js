(function(){
  adapter.log("gerrit client");
  console.log(adapter.utils.getOpenID());
  adapter.utils.wait(adapter.utils.domReady,init);
  function init(){
    var register = document.getElementsByTagName("a")[8];
    var singin = document.getElementsByTagName("a")[9];

	if (!register || !singin) {
	  setTimeout(function(){init()},100);
	  return;
	}

    if (singin.innerHTML == "Sign In" || singin.innerHTML == "登录") {
		register.style.display="none";
		login();

    	adapter.utils.addEvent(singin,"click",function(){login();});
    }
  }
  function login() {
        var openID, input, siginButton;
        openID = adapter.utils.getOpenID();

        if (openID) {
	    	var singin = adapter.utils.$tag("a")[9];
            adapter.utils.fireEvent(singin, "click");
            input = adapter.utils.$class("GFE-PU4BIM")[0].style.display="none";
            input = adapter.utils.$class("GFE-PU4BIM")[1].style.display="none";
            input = adapter.utils.$class("GFE-PU4BKM")[0]; //openid input
            input.value = openID;
        } 
    }
})()
