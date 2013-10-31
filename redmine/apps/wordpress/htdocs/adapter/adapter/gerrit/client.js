(function(){
  adapter.utils.wait(adapter.utils.domReady,init);
  function init(){
    var register = document.getElementsByTagName("a")[13];
    var openid = document.getElementById("f_openid");

	if (!register && !openid) {
	  setTimeout(function(){init()},100);
	  return;
	}

	if (!register ) {
	  setopenid();
	  return;
	}

    if (register.innerHTML == "Register" || register.innerHTML == "注册") {
		register.style.display="none";
    }
    addHover();
  }
 function addHover(){
	if(jQuery&&(jQuery('.changeTable').length>0)){
          jQuery(".changeTable").find("tr").live({
   mouseenter:
   function()
   {
      this.color=this.style.backgroundColor;jQuery(this).css({backgroundColor:"lightblue"});
   },
   mouseleave:
   function()
   {
     jQuery(this).css({backgroundColor:this.color});
   }
});
	}
        else {
          setTimeout(addHover,200);
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

  function setopenid() {
		document.getElementById("provider_google").style.display="none";
		document.getElementById("provider_yahoo").style.display="none";
		document.getElementsByTagName("a")[3].style.display="none";
		
		openID = adapter.utils.getOpenID();
		input = document.getElementById("f_openid");
		if (openID)
            input.value = openID;
        else
			input.value = "http://xxxxxxxx.elastos.org";

		if (!input.onchange) 
			input.onchange=function(){var x=document.getElementById("f_openid"); checkIt(x);}

		function keyEnter(e) {
			var iKeyCode=window.event.keyCode;
			if(iKeyCode==13) return false;
		}
		document.onkeydown=keyEnter;
	}
  })()
