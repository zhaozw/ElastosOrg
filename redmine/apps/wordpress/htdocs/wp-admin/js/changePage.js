if(document.getElementById("post-2")&&document.getElementById("post-544")){
	var curIndex = 0;
	var timeInterval = 3000;
	var change = setInterval("changePage()",timeInterval);
	var post_home = document.getElementById("post-2");
	var post_developers = document.getElementById("post-544");
	function changePage(){
		if (curIndex == 2) {
			post_developers.style.display = "none";
			post_home.style.display = "inline";
			document.getElementById("icondevelopers").style.opacity="0.2";
			document.getElementById("iconhome").style.opacity="1";
			curIndex = 0;
		} else if(curIndex == 0) {
			curIndex++;
		} else {
			post_home.style.display = "none";
			post_developers.style.display = "inline";
			document.getElementById("iconhome").style.opacity="0.2";
			document.getElementById("icondevelopers").style.opacity="1";
			curIndex++;
		}
	}
	post_home.onmouseover = function(){clearInterval(change);};
	post_home.onmouseout = function(){ change = setInterval("changePage()",timeInterval); };
	post_developers.onmouseover = function(){clearInterval(change);};
	post_developers.onmouseout = function(){ change = setInterval("changePage()",timeInterval); };
}
