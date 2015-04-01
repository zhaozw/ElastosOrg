	//alart("fewsaf");
	var curIndex = 0;
	var timeInterval = 3000;
	setInterval("changePage()",timeInterval);
	function changePage(){
		if (curIndex == 2) {
			$("#elastospage").html($("#post-2").html());
			curIndex = 0;
		} else if(curIndex == 0) {
			$("#elastospage").html($("#post-2").html());
			curIndex++;
		} else {
			$("#elastospage").html($("#post-544").html());
			curIndex++;
		}
	}
