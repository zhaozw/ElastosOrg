(function(){
	window.onload = function(){
	   var items = document.getElementById('items').children;
	   for(var i=0;i<items.length;i++){
		 items[i].onclick = function(){
		   var i=this.i;
		   for(var j=1;j<items.length-1;j++){
			 if(document.getElementById("content"+j))
			   document.getElementById("content"+j).style.display="none";
		   }
		   if(i==0)
				document.getElementById("content"+1).style.display="block";
		   else if(i==1||i==2||i==3)
				document.getElementById("content"+2).style.display="block";
		   else if(i==4||i==5||i==6)
				document.getElementById("content"+3).style.display="block";
		   else if(i==7||i==8||i==9)
				document.getElementById("content"+4).style.display="block";
		   else if(i>9)
				document.getElementById("content"+5).style.display="block";
		 }
		 items[i].i=i;
	   }
   }
})();