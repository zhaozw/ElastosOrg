(function(){
    adapter.log("wordpress client");
    var wordpress = {
        init: init
    };
    var $;
    var getCookie = adapter.utils.getCookie;
    var setCookie = adapter.utils.setCookie;
    var deleteCookie = adapter.utils.deleteCookie;
    adapter.utils.wait(adapter.utils.domReady,function(){
      if(window.jQuery)
        $ = window.jQuery;
      wordpress.init(adapter.utils.parseURL(window.location.href));
    });
    
    var domain = "http://" + adapter.config.wordpress.url;
    var urlMap = {
        default_: "/",
        index: "/index.php",
        register: "/register/",
        login: "/wp-login.php",
        openidSettingAdapter: "/wp-admin/options-general.php?page=openid",
        openidListAdapter: "/wp-admin/users.php?page=your_openids",
        openidTrusted_sites:"/wp-admin/users.php?page=openid_trusted_sites"
    };
    var adapterMap = {
        default_: index,
        index: index,
        register: register,
        login: login,
        openidSettingAdapter: openidSettingAdapter,
        openidListAdapter: openidListAdapter,
        openidTrusted_sites:openidTrusted_sites
    };
    return wordpress;
    function init(urlObj) {
        adapter.log("adapter wordpress init");
       /*
        $.ajax({
            url: domain + "/wp-includes/js/jquery/jquery.form.js",
            success: function (code) {
                $.globalEval(code);
            },
            async: false,
            dataType: "text"
        });
        */
        adapter.$("a[href='http://"+adapter.config.domain+"/project/']").attr("target","_blank");
        adapter.$("a[href='http://"+adapter.config.domain+"/review/']").attr("target","_blank");
        adapter.$("a[href='http://"+adapter.config.domain+"/wiki']").attr("target","_blank");
         adapter.$("a[href='http://"+adapter.config.domain+"/download/']").attr("target","_blank");
        for (var urlName in urlMap) {
            if (urlObj.relative == urlMap[urlName]) {
                if (urlName in adapterMap) {
                    adapterMap[urlName]();
                } 
            }

        }
        if (isLogin()){
            var openid_a,openid;
            /*
            openid_a = $("#bp-adminbar-blogs-menu>ul>li>a");
           
            if(openid_a[openid_a.length-2])
              openid=openid_a[openid_a.length-2].href;
               adapter.log("openid="+openid);
            if(openid)
                adapter.utils.setOpenID(openid);
            else 
              adapter.log("Can't find openid");
             openid_a.hide();
             $("#bp-adminbar-blogs-menu").hide();
             */
             if(jQuery("#wp-admin-bar-my-sites-list>li>.ab-sub-wrapper>.ab-submenu").length>0) {
                openid=jQuery(jQuery("#wp-admin-bar-my-sites-list>li>.ab-sub-wrapper>.ab-submenu")[0]).find("li>a")[3].href;
                adapter.utils.setOpenID(openid);
                jQuery("#wp-admin-bar-my-sites-list>li>.ab-sub-wrapper>.ab-submenu").hide();
                jQuery(jQuery("#wp-admin-bar-my-sites-list>li>.ab-sub-wrapper>.ab-submenu")[0]).show();
                console.log(jQuery("#wp-admin-bar-my-sites>a")[0].href);
                jQuery("#wp-admin-bar-my-sites>a")[0].href=openid;
             }
             else {
                adapter.log("Can't find openid");
             } 
             jQuery("#wp-admin-bar-site-name").hide();
        } else {
          adapter.utils.deleteCookie("openid");
        }
        if (isLogin() && (!getCookie("adapterInit")) && adapter.utils.getOpenID()) {
            adapter.log("get OpenID>>>>",adapter.utils.getOpenID());
            adapter.utils.iframeLoad(adapter.utils.getOpenID() + urlMap.openidSettingAdapter,function(){
               adapter.log("add openid setp1 success! set name");
               adapter.utils.iframeLoad(adapter.utils.getOpenID() + urlMap.openidListAdapter,function(){
                 adapter.log("add openid setp2 success! add openid");
                 
                 adapter.utils.iframeLoad(adapter.utils.getOpenID() + urlMap.openidTrusted_sites,function(){
                     adapter.log("add openid setp3 success! add trusted sites");
                     adapter.log("add openid success!");
                 });
               });
            });
            setCookie("adapterInit", 1);
        } else {
          adapter.log("adapterInit already! isLogin:",isLogin()," adapterInit:",getCookie("adapterInit")," openid:",adapter.utils.getOpenID());
        }
    }
    function index() {
        return;
        $("#header").css("background", "#f4f4f4");
        $("#logo>a").html("<img src='"+adapter.config.logo.wordpress+"'/>");
    }
    function register() {
        return;
        $("#signup_with_blog").attr("checked", true);
        $("#signup_with_blog").attr("disabled", true);
        $("#signup_with_blog")[0].parentNode.innerHTML += "(required)You can use it as your openid";
        $("#blog-details").css("display", "block");
        window.setTimeout(function () { $("#blog-details").css("display", "block"); }, 1000);
        $("#signup_email").val(adapter.config.email.wordpress);
        $("#signup_submit").click(function () {
            if (!$("#signup_email").val().match(adapter.config.email.wordpress)) {
                alert("Currently only supports E-mail form " + adapter.config.email.wordpress);
                return false;
            }
        });
    }
    function login() {
        return;
    }
    function openidListAdapter() {
        adapter.log(" openidListAdapter  add openid");
        if(!$(".widefat").text().match(adapter.utils.getOpenID())){
         $("#openid_identifier")[0].value=adapter.utils.getOpenID();
         $($("form")[1]).submit();
        }else {
          adapter.log(adapter.utils.getOpenID()," alread added!");
        }
    }
    function openidSettingAdapter() {
        adapter.log("set openid_blog_owner");
        $("input").attr("checked", true);
        $("#openid_blog_owner")[0].selectedIndex = 1;
        $("form").submit();
    }
    function openidTrusted_sites(){
       adapter.log("set openidTrusted_sites");
       var str = "";
       for(var proto in adapter.config){
         if(adapter.config[proto] && adapter.config[proto].url && !$(".widefat").text().match(adapter.config[proto].url))
           str+=adapter.config[proto].url+"\r\n";
       }
       if(!$(".widefat").text().match(adapter.utils.getOpenID())){
         str+=adapter.utils.getOpenID()+"\r\n";
       }
        if($("#sites")[0]&&str){       
           $("#sites")[0].value=str;
           $($("form")[1]).submit();
       }
    }
    function isLogin() {
        return !!getCookie("loggedin_user_fullname")|| !!jQuery("#wpadminbar").length;
    }
    function getUserFullname() {
        return getCookie("loggedin_user_fullname");
    }
    function resetCookie() {
        deleteCookie("adapterInit");
    }
})()