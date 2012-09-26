(function () {
  var http=require("http");
  var url = require("url");
  var fs = require("fs");
  var _ = require("underscore");
  var urlParse = url.parse;
  adapter._ = _;
  adapter.getWebFile = getWebFile;
  adapter.log("adapter_server");
  //getWebFile("http://google.com","index.html",function(isOK){console.log("download "+isOK);},true);
  var argArray = process.argv.slice(2,process.argv.length);
  if(argArray.length==0) //run as:node adapter.js without any other arguments
    createWebServer();
  switch(argArray[0]){
    case "wordpress":
	  adapter.loadjs(adapter.path+"adapter/wordpress/server.js");
	  break;
	case "redmine":
	  adapter.loadjs(adapter.path+"adapter/redmine/server.js");
	  break;
  }
  if(argArray[0]=="init"){
    if(argArray[1]=="wordpress"){
	    addStrToFileBeforeSomeStr(
		  adapter.config.wordpress.path+'wp-includes/script-loader.php',
		  "if \( apply_filters\('print_head_scripts",
		  "echo \"<script src='"+adapter.config.adapter.url+"adapter.js?for=wordpress"+"'></script>\";"
		);
	}
	if(argArray[1]=="redmine"){
	    addStrToFileBeforeSomeStr(
			adapter.config.redmine.path+'app/views/layouts/base.html.erb',
			"</head>",
			"<script src='"+adapter.config.adapter.url+"adapter.js?for=redmine"+"'></script>"
	    );
	}
    if(argArray[1]=="gerrit"){
	    addStrToFileBeforeSomeStr(
		  adapter.config.gerrit.source+'gerrit-gwtui/src/main/java/com/google/gerrit/client/ui/Screen.java',
		  "package com.google.gerrit.client.ui;",
		  "import com.google.gwt.dom.client.Document;import com.google.gwt.dom.client.HeadElement;import com.google.gwt.dom.client.ScriptElement;import com.google.gwt.dom.client.Element;"
		);
        addStrToFileBeforeSomeStr(
		  adapter.config.gerrit.source+'gerrit-gwtui/src/main/java/com/google/gerrit/client/ui/Screen.java',
		  "if (header == null)",
		  "Element head = Document.get().getElementsByTagName('head').getItem(0);ScriptElement sce = Document.get().createScriptElement();sce.setType('text/javascript');sce.setSrc('./adapter/adapter.js?for=gerrit');head.appendChild(sce);"
		);
	}
  }
  function addStrToFileBeforeSomeStr(filePath,strBefore,strAdd,callback){
    fs.readFile(filePath, function (err, data) {
	  if (err) throw err;
		 var str = data.toString();
		 var index=str.indexOf(strBefore);
		 var str1= str.substring(0,index);
		 var str2= str.substring(index,str.length);
		 str3=str1+strAdd+str2;
		 fs.writeFile(filePath, str3, function (err) {
			  if (err) throw err;
			  console.log('Add str:',strAdd,"  to:",filePath," before:",strBefore);
			  if(typeof callback =="function")
			    callback(true);
			});
	});
  }
  function createWebServer() {
        http = require('http');
        http.createServer(webserverHandle).listen(adapter.config.adapterapi.port, adapter.config.adapterapi.ip);
  }
  function webserverHandle(request,response){
    var urlObj = urlParse(request.url, true);
    var action = urlObj.pathname;
    switch(action){
         case "/adapter/api/getNodeVersion":
           response.end(process.version);
           break;
         default:
           response.end("unknow api:"+action);
    }
  }
  function getWebFile(url,filepath,callback,iscover){
    fs.exists(filepath,function(exists){
      if(exists&&!iscover){
        adapter.log(filepath," exists");
      }
      else{
          var stream = fs.createWriteStream(filepath);
          var res=http.get(urlParse(url, true), function (res) {
            res.on("data", function (d) {
                stream.write(d);
            });
            res.on("end", function (d) {
               stream.end();
               callback(true);
            });
          });
          res.on("error",function(){
                  callback(false);
          });
      }
    });
  }
})()