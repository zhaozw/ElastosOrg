(function(self){
   var _adapter = self.adapter;
   var adapter = function(){};
   self.adapter = adapter;
   adapter.path = getAdapterPath()+"/";
   adapter.log = log;
   adapter.getText = getText;
   adapter.loadjs = loadjs;
   adapter.isdebug = false;
   adapter.noadapter = false;
   if(isBrowser()) {
      adapter.path="http://"+parseURL(document.location.href).host+"/adapter/";
   }
   init();
   function init(){
       adapter.config = JSON.parse(getText(adapter.path+"config.json"));
       adapter.isdebug = adapter.config.isdebug;
       adapter.noadapter = adapter.config.noadapter;
       if(isNodejs()){
         var cmdArray = process.argv.slice(2,process.argv.length);
         for(var i=0;i<cmdArray.length;i++){
           if(cmdArray[i]=="noadapter=1")
             adapter.noadapter=true;
           else if(cmdArray[i]=="isdebug=1")
             adapter.isdebug=true;
         }
         if(adapter.noadapter)
           return;
         loadjs(adapter.path+"adapter_server.js");
       }else if(isBrowser()){
         if(window.location.href.match("noadapter=1")||document.cookie.match("noadapter=1"))
          adapter.noadapter=true;
         if(window.location.href.match("isdebug=1")||document.cookie.match("isdebug=1"))
           adapter.isdebug=true;
         if(adapter.noadapter)
           return;
         loadjs(adapter.path+"adapter_client.js");
       }
       adapter.toString=function(){
         var protoArray=[];
         for(var proto in adapter){
            protoArray.push(proto);
         }
         return protoArray.toString();
       }
   }
   function isNodejs() {
        return typeof exports !== 'undefined' && typeof module !== 'undefined' && typeof module.exports !== 'undefined';
    }
    
    function isBrowser() {
        return typeof window !== 'undefined' && typeof document !== 'undefined';
    }
    function isChrome() {
        return isBrowser() && window.chrome && window.chrome.extension;
    }
    function log(){
      if(adapter.isdebug&&console&&console.log)
        console.log.apply(console,arguments);
    }
    function getAdapterPath() {
        if (isNodejs())
            return __dirname.replace(/\\/g, "/");
        else if (isBrowser()) {
            return getAdapterPathFromScriptNode();
        }
        else if (isChrome()) {
            return getAdapterPathFromChromeExtension();
        }
        function getAdapterPathFromScriptNode() {
            var scriptNodes, path, pathObj;
            scriptNodes = document.getElementsByTagName("script");
            for (var i = 0; i < scriptNodes.length; i++) {
                if (scriptNodes[i].getAttribute("src") && scriptNodes[i].getAttribute("src").match("adapter.js")) {
                    pathObj = parseURL(scriptNodes[i].getAttribute("src"));
					adapter.pathObj = pathObj;
                    path = pathObj.protocol + "://" + pathObj.host+((pathObj.port==80||pathObj.port=="")?"":(":"+pathObj.port)) + pathObj.path.replace("/adapter.js", "");
                }
            }
            return path;
        }
        function getAdapterPathFromChromeExtension() {
            var pathObj, path;
            pathObj = parseURL(chrome.extension.getURL("adapter.js"));
            return pathObj.protocol + "://" + pathObj.host + ((pathObj.port==80||pathObj.port=="")?"":(":"+pathObj.port)) + pathObj.path.replace("I.js", "");
        }

    }
    function getText(url, callback, async) {
        if (isBrowser() && XMLHttpRequest) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", url, async);
            if (!async) {
                xhr.send(null);
                if (typeof callback == "function")
                    callback(xhr.responseText);
                return xhr.responseText;
            } else {
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304 || xhr.status == 0/*file:///*/) {
                            if (typeof callback == "function")
                                callback(xhr.responseText);
                        }
                    }
                }
                xhr.send(null);
            }
        } else if (isNodejs()) {
            if (url.match("http://")) {
                var data = "";
                var isOK = false;
                require("http").get(require('url').parse(url, true), function (response) {
                    response.on("data", function (d) {
                        data += d;
                    });
                    response.on("end", function (d) {
                        isOK = true;
                        callback(data);
                    })
                });
                if (async == false) {
                    while (true) {
                        ;
                        if (isOK)
                            break;
                    }
                }
                return;
            }
            var fs = require('fs');
            if (!async) {
                var data = fs.readFileSync(url, 'utf8').toString();
                if (typeof callback == "function")
                    callback(data);
                return data;
            } else {
                fs.readFile(url, function (err, data) {
                    if (err) throw err;
                    if (typeof callback == "function")
                        callback(data.toString());
                });
            }
        }
    }
    function _eval(codeStr) {
        try {
            return eval(codeStr);
        } catch (e) {
            try {
                return eval("(" + codeStr + ")");
            } catch (ee) {
                throw new Error("eval error" + e + ee + ">>>" + codeStr);
            }
        }
    }
    function loadjs(url, callback, async) {
        if (async) {
            getText(url, function (codeStr) { callback(_eval(codeStr)) }, async);
        } else {
            var resulte = _eval(getText(url));
            if (typeof callback == "function") {
                callback(resulte);
            }
            return resulte;
        }
    }
    function parseURL(url) {
        if(isNodejs()){
          return require('url').parse(url, true);
        }
        else if(isBrowser()){
            var a = document.createElement('a');
            a.href = url;
            return {
                source: url,
                protocol: a.protocol.replace(':', ''),
                host: a.hostname,
                port: a.port,
                query: a.search,
                params: (function () {
                    var ret = {},
                            seg = a.search.replace(/^\?/, '').split('&'),
                            len = seg.length, i = 0, s;
                    for (; i < len; i++) {
                        if (!seg[i]) { continue; }
                        s = seg[i].split('=');
                        ret[s[0]] = s[1];
                    }
                    return ret;
                })(),
                file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
                hash: a.hash.replace('#', ''),
                path: a.pathname.replace(/^([^\/])/, '/$1'),
                relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ''])[1],
                segments: a.pathname.replace(/^\//, '').split('/')
            };
        }
    }
     function domReady() {
        return !!document.body;
    }
    function wait(isOK, fn, option) {
        var argArray;
        var _option = {};
        _option.content = option && option.content ? option.content : this;
        _option.timeout = option && option.timeout ? option.timeout : 100;
        _option.argArray = option && option.argArray ? option.argArray : [];
        if (isOK()) {
            fn.apply(_option.content, _option.argArray);
        }
        else {
            argArray = Array.prototype.slice.call(arguments);
            setTimeout(function () {
                wait.apply(_option.content, argArray);
            }, _option.timeout);
        }
    }
})(this)