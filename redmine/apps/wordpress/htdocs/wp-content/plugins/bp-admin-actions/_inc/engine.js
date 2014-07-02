function getClientWidth() { return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth; }
function getClientHeight() { return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight; }
jQuery(document).ready(function($) {
    //0 means disabled; 1 means enabled;
    var popupStatus = 0;
    function loadPopup(){
        //loads popup only if it is disabled
        if(popupStatus==0){
            $("#backgroundPopup").css("opacity", "0.7");
            $("#backgroundPopup").fadeIn("fast");
            $("#popupGroups").fadeIn("fast");
            popupStatus = 1;
        }
    }
    function disablePopup(){
        if(popupStatus==1){
            $("#backgroundPopup").fadeOut("fast");
            $("#popupGroups").fadeOut("fast");
            popupStatus = 0;
        }
    }
    function centerPopup(){
        //request data for centering
        var windowWidth = getClientWidth();
        var windowHeight = getClientHeight();
        var popupHeight = $("#popupGroups").height();
        var popupWidth = $("#popupGroups").width();
        //centering
        $("#popupGroups").css({
            "position": "absolute",
            "top": windowHeight/2-popupHeight/2,
            "left": windowWidth/2-popupWidth/2
        });
        $("#backgroundPopup").css("height", windowHeight);
    }
   
    $('div.members a.add_to_group').live('click', function(add) {
        add.preventDefault();
        var user_id = $(this).attr('rel');
        
        $.ajax({
            type: 'GET',
            url: ajaxurl,
            data: {
                user: user_id,
                action: 'bpaa_add_user_to_group_ajax'
            },
            success: function(data) {
                $('div#popupGroups').html(data);
            }
        });
        
        centerPopup();
        loadPopup();
        

    });
    
    $('a#add_to_selected').live('click', function(e) {
        e.preventDefault();
        var group_id = $('select#selected_group').val();
        var new_user_id = $(this).attr('rel');
        
        if(group_id == '0'){
            alert(bpaa_strings.please_select);
        }else{
            $.ajax({
                type: 'GET',
                url: ajaxurl,
                data: {
                    user: new_user_id,
                    group: group_id,
                    action: 'bpaa_add_user_to_group_silent_ajax'
                },
                success: function(data) {
                    if(data == '1'){
                        $('p.popupReport').fadeIn('fast').html('<span class="response1">'+bpaa_strings.user_added+'</span>');
                    }else{
                        $('p.popupReport').fadeIn('fast').html('<span class="response0">'+bpaa_strings.error_again+'</span>');
                    }
                    setTimeout("jQuery('p.popupReport span').fadeOut('fast')", 2500);
                    if (group_id == $('select#selected_group').val())
                        $('select#selected_group option[value='+group_id+']').remove();
                }
            });
        }
    });
    //CLOSING POPUP
    $("a#popupGroupsClose").live('click', function() { disablePopup(); });
    $("div#backgroundPopup").click(function(){ disablePopup(); });
    //Press Escape
    $(document).keypress(function(e){
        if(e.keyCode==27 && popupStatus==1) disablePopup();
    });
    
});
