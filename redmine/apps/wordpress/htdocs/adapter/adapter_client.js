(function(){
  var jQueryHave=false;
  var _Have = false;
  adapter.log("adapter_clinet.js");
  adapter.api = api;
  adapter.utils = {
        parseURL: parseURL,
        addEvent: addEvent,
        removeEvent: removeEvent,
        getCookie: getCookie,
        setCookie: setCookie,
        deleteCookie: deleteCookie,
        $id: getElementById,
        $tag: getElementsByTagName,
        $class: getElementsByClassName,
        findEl:findEl,
        fireEvent: fireEvent,
        setOpenID: setOpenID,
        getOpenID: getOpenID,
        iframeLoad:iframeLoad,
        domReady:domReady,
        wait:wait
    };
  //adapter.api("getNodeVersion",function(version){
  //  adapter.log(version);
  //});
  if(!window._) {
    adapter.loadjs(adapter.path+"js_libs/underscore.js");
    
  }
  else { 
    adapter._ = _; 
    _Have =true;
  }
  if(!window.jQuery) {
    adapter.loadjs(adapter.path+"js_libs/jquery.js");
  }
  else { 
    adapter.$ = window.jQuery;
    jQueryHave=true;
  }
  if(!jQuery.noty&&!window.noty){
    jQuery.getScript(adapter.path+"js_libs/noty/jquery.noty.js", function(data, textStatus, jqxhr) {
        jQuery.getScript(adapter.path+"js_libs/noty/layouts/all.js", function(data, textStatus, jqxhr) {
            jQuery.getScript(adapter.path+"js_libs/noty/themes/default.js", function(data, textStatus, jqxhr) {
               adapter.n = adapter.noty = window.noty;
               delete window.noty;
            });
        });
    }); 
  } else {
    adapter.n = adapter.noty = window.noty;
  }
  if(!jQueryHave){
       adapter.$=$.noConflict();
  }
  if(!_Have){
    adapter._=_.noConflict();
  }
  switch(adapter.pathObj.params.for){
     case "wordpress":
	  adapter.loadjs(adapter.path+"adapter/wordpress/client.js");
	  break;
	case "redmine":
	  adapter.loadjs(adapter.path+"adapter/redmine/client.js");
	  break;
     case "gerrit":
	  adapter.loadjs(adapter.path+"adapter/gerrit/client.js");
	  break;
      default:
        if(window.location.href.match("/gerrit/"))
          adapter.loadjs(adapter.path+"adapter/gerrit/client.js");
        if(window.location.href.match("/review/"))
          adapter.loadjs(adapter.path+"adapter/gerrit/client.js");
  }
  function api(name,callback){
    adapter.getText(adapter.config.adapterapi.url+name,function(data){
     callback(data);
    });
  }
  function setNoAdapter(flag){
    if(!flag){
      adapter.utils.setCookie("noadapter",1);
    }
    else {
      adapter.utils.setCookie("noadapter",0);
    }
  }
   //=====================Tool function=====================//
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
    function parseURL(url) {
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
    //------------------------------------
    // heavily based on the Quirksmode addEvent contest winner, John Resig
    // addEvent
    function addEvent(obj, type, fn) {
        if (obj.addEventListener) obj.addEventListener(type, fn, false);
        else if (obj.attachEvent) {
            obj["e" + type + fn] = fn;
            obj[type + fn] = function () { obj["e" + type + fn](window.event); }
            obj.attachEvent("on" + type, obj[type + fn]);
        }
    }
    //------------------------------------
    // removeEvent
    function removeEvent(obj, type, fn) {
        if (obj.removeEventListener) obj.removeEventListener(type, fn, false);
        else if (obj.detachEvent) {
            obj.detachEvent("on" + type, obj[type + fn]);
            obj[type + fn] = null;
            obj["e" + type + fn] = null;
        }
    }
    function getCookie(name) {
        var start = document.cookie.indexOf(name + "=");
        var len = start + name.length + 1;
        if ((!start) && (name != document.cookie.substring(0, name.length))) {
            return null;
        }
        if (start == -1) {
            return null;
        }
        var end = document.cookie.indexOf(";", len);
        if (end == -1) {
            end = document.cookie.length;
        }
        return unescape(document.cookie.substring(len, end));
    }

    function setCookie(name, value, expires, path, domain, secure) {
        var today = new Date();
        today.setTime(today.getTime());
        if (expires) {
            expires = expires * 1000 * 60 * 60 * 24;
        }
        var expires_date = new Date(today.getTime() + (expires));
        document.cookie = name + "=" + escape(value) + ((expires) ? ";expires=" +
            expires_date.toGMTString() : "") + ((path) ? ";path=" +
            path : "") + ";domain="+((domain) ? domain : "") +
            ((secure) ? ";secure" : "");
    }

    function deleteCookie(name, path, domain) {
        if (getCookie(name)) {
            document.cookie = name + "=" + ((path) ? ";path=" + path : "") +
                ((domain) ? ";domain=" + domain : "") +
                ";expires=Thu,01-Jan-1970 00:00:01 GMT";
        }
    }
    function getElementById(id) {
        return document.getElementById(id)
    };
    function getElementsByTagName(tag) {
        return document.getElementsByTagName(tag)
    };
    //http://www.cnblogs.com/rubylouvre/archive/2009/07/24/1529640.html
    function getElementsByClassName(searchClass, node, tag) {
        if (document.getElementsByClassName) {
            return document.getElementsByClassName(searchClass)
        } else {
            node = node || document;
            tag = tag || '*';
            var returnElements = []
            var els = (tag === "*" && node.all) ? node.all : node.getElementsByTagName(tag);
            var i = els.length;
            searchClass = searchClass.replace(/\-/g, "\\-");
            var pattern = new RegExp("(^|\\s)" + searchClass + "(\\s|$)");
            while (--i >= 0) {
                if (pattern.test(els[i].className)) {
                    returnElements.push(els[i]);
                }
            }
            return returnElements;
        }
    }
    //1kjs
    function findEl(a, b) { a = a.match(/^(\W)?(.*)/); return (b || document)["getElement" + (a[1] ? a[1] == "#" ? "ById" : "sByClassName" : "sByTagName")](a[2]) }
    function fireEvent(el, type) {
        if (document.all) {
            // For IE 
            el[type]();
        } else if (document.createEvent) {
            //FOR DOM2 
            var ev = document.createEvent('HTMLEvents');
            ev.initEvent(type, false, true);
            el.dispatchEvent(ev);
        }
    }
    function setOpenID(openid){
      setCookie("openid", openid);//,"/","","."+adapter.config.wordpress.url);
    }
    function getOpenID() {
        var openID;
        openID = getCookie("ElastosID");
        return openID;
    }
    function iframeLoad(url,callback){ 
       url = url.replace(/\/\//g,"/").replace("http:/","http://");
       adapter.log("iframeLoad ",url);
       window._callback_=callback;
       adapter.$(document.body).append("<iframe src=" + url +" onload='window._callback_();' style='width:0px;height:0px;overflow:hidden;' scrolling='no'></iframe>");
    }
})()