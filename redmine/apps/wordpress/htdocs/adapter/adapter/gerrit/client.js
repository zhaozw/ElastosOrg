(function(){
  adapter.log("gerrit client");
  console.log(adapter.utils.getOpenID());
  adapter.utils.wait(adapter.utils.domReady,init);
  function init(){
    var register = document.getElementsByTagName("a")[8];
    var sign = document.getElementsByTagName("a")[9];

	if (!register || !sign) {
	  setTimeout(function(){init()},100);
	  return;
	}

    if (sign.innerHTML == "Sign In" || sign.innerHTML == "登录") {
		register.style.display="none";
		login();

    	adapter.utils.addEvent(sign,"click",function(){login();});
    }
  }

  function checkIt(y) {
	if(y.value.indexOf("elastos.org")<0) {
		openID = adapter.utils.getOpenID();
		if (openID)
			y.value=openID;
		else
			y.value="http://xxxxxxxx.elastos.org";
	}
  }

  function login() {
        var openID, input;
		var sign = adapter.utils.$tag("a")[9];

		adapter.utils.fireEvent(sign, "click");
		adapter.utils.$class("GFE-PU4BIM")[0].style.display="none";
		adapter.utils.$class("GFE-PU4BIM")[1].style.display="none";
		
		openID = adapter.utils.getOpenID();
		input = document.getElementsByClassName('GFE-PU4BKM')[0];
		if (openID) {
            input.value = openID;
        }

		if (!input.onchange) 
			input.onchange=function(){var x=document.getElementsByClassName('GFE-PU4BKM')[0]; checkIt(x);}

		function keyEnter(e) {
			var iKeyCode=window.event.keyCode;
			if(iKeyCode==13) return false;
		}
		document.onkeydown=keyEnter;
	}
  })()
