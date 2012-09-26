(function(){
  adapter.log("redmine client");
  adapter.utils.wait(adapter.utils.domReady,init);
  function init(){
    if(adapter.utils.findEl(".login")){
      openID = adapter.utils.getOpenID();
      if (openID&& adapter.utils.findEl("#openid_url")) {
         adapter.utils.findEl("#openid_url").value = openID;
          //adapter.utils.$tag("form")[0].submit();
      }
      if(adapter.utils.findEl("#username")){
        //adapter.$("#username").parent().parent().hide();
        adapter.utils.findEl("#username").disabled =true;
        adapter.$("#username").parent().parent().html("<td></td><td><p>http://***.elastos.org/</p></td>");
        
      }
      if(adapter.utils.findEl("#password")) {
         adapter.utils.findEl("#password").disabled =true;
         adapter.$("#password").parent().parent().hide();
      }
      if(adapter.utils.findEl(".register")) {
        adapter.utils.findEl(".register")[0].parentNode.style.display="none";
      }
      if(adapter.utils.findEl("#openid_url")){
        adapter.utils.findEl("#openid_url").size=42;
      }
      
    }
 }
})()