var curIndex = 0;
var timeInterval = 3000;
setInterval("changePage()",timeInterval);
document.getElementById("post-2").setAttribute("style","display:inline");
function changePage(){
	if (curIndex == 2) {
		document.getElementById("post-2").setAttribute("style","display:inline");
		curIndex = 0;
	} else if(curIndex == 0) {
		curIndex++;	
	} else {
		document.getElementById("post-2").setAttribute("style","display:none");
		document.getElementById("post-544").setAttribute("style","display:inline");
		curIndex++;
	}
}
