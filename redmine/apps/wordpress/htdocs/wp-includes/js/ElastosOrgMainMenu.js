/*
 * DEFAULT OPTIONS
 *
 options: {
 template:"yourMenuVoiceTemplate",--> the url that returns the menu voices via ajax. the data passed in the request is the "menu" attribute value as "menuId"
 additionalData:"",								--> if you need additional data to pass to the ajax call
 menuSelector:".menuContainer",		--> the css class for the menu container
 menuWidth:150,										--> min menu width
 openOnRight:false,								--> if the menu has to open on the right insted of bottom
 iconPath:"ico/",									--> the path for the icons on the left of the menu voice
 hasImages:true,									--> if the menuvoices have an icon (a space on the left is added for the icon)
 fadeInTime:100,									--> time in milliseconds to fade in the menu once you roll over the root voice
 fadeOutTime:200,									--> time in milliseconds to fade out the menu once you close the menu
 menuTop:0,												--> top space from the menu voice caller
 menuLeft:0,											--> left space from the menu voice caller
 submenuTop:0,										--> top space from the submenu voice caller
 submenuLeft:4,										--> left space from the submenu voice caller
 opacity:1,												--> opacity of the menu
 shadow:false,										--> if the menu has a shadow
 shadowColor:"black",							--> the color of the shadow
 shadowOpacity:.2,								--> the opacity of the shadow
 openOnClick:true,								--> if the menu has to be opened by a click event (otherwise is opened by a hover event)
 closeOnMouseOut:false,						--> if the menu has to be cloesed on mouse out
 closeAfter:500,									--> time in millisecond to whait befor closing menu once you mouse out
 minZindex:"auto", 								--> if set to "auto" the zIndex is automatically evaluate, otherwise it start from your settings ("auto" or int)
 hoverInted:0, 										--> if you use jquery.hoverinted.js set this to time in milliseconds to delay the hover event (0= false)
 onContextualMenu:function(o,e){} --> a function invoked once you call a contextual menu; it pass o (the menu you clicked on) and e (the event)
 },
 */
jQuery(function(){
var $=jQuery;
    $("#wp-admin-bar-wp-logo").buildMenu(
            {
                template:"menuVoices.html",
                additionalData:"pippo=1",
                menuWidth:200,
                openOnRight:false,
                menuSelector: ".menuContainer",
                iconPath:"ico/",
                hasImages:true,
                fadeInTime:100,
                fadeOutTime:300,
                minZindex:"auto",
                opacity:.95,
                shadow:false,
                shadowColor:"#ccc",
                hoverIntent:0,
                openOnClick:true,
                closeOnMouseOut:true,
                closeAfter:1000,
                submenuHoverIntent:200
            });

	jQuery("#box_menu").show();
});

//this function get the id of the element that fires the context menu.
function testForContextMenu(el){
    if (!el) el= $.mbMenu.lastContextMenuEl;
    showMessage("the ID of the element is:   "+$(el).attr("id"));
}

function loadFlash(){
    $.ajax({
        url:"testFlash/test.html",
        dataType:"html",
        success:function(html){
            $("#flashTest").html(html);
        }
    });
}

function showMessage(msg){
    var msgBox=$("<div>").addClass("msgBox");
    $("body").append(msgBox);
    msgBox.append(msg);
    setTimeout(function(){msgBox.fadeOut(500,function(){msgBox.remove();})},3000)
}
