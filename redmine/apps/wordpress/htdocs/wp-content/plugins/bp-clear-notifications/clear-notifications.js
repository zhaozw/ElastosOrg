jQuery(document).ready(function(){

    var jq=jQuery;
    jq("#clear-notifications,#wp-admin-bar-clear-notifications a").live('click',function(){
        var $this=jq(this);
        var nonce=get_var_in_url($this.attr('href'),'_wpnonce');
        $this.text('clearing...');
        jq.post(ajaxurl,{'action':'cn_clear_notifications',
                         '_wpnonce':nonce,
                         cookie:encodeURIComponent(document.cookie)

            
        },function(resp){
            if(resp=='1'){
                //remove notification count
                jq("#bp-adminbar-notifications-menu").find('span').remove();
                jq("#wp-admin-bar-bp-notifications").find('span').text('0');
                jq("#wp-admin-bar-bp-notifications").find('ul').remove();
                jq("#bp-adminbar-notifications-menu>ul").remove();
                //remove all notifications

                $this.remove();//incase someone has used it somewhere else

                
            }
        });
        return false;//prevent default action
    });

    //for individual notification item
    jq("#bp-adminbar-notifications-menu span.close-notification").live('click',function(){
        var $li=jq(this).parent();
        

      return false;
    });
function get_var_in_url(url,name){
    
    var urla=url.split("?");
    var qvars=urla[1].split("&");//so we hav an arry of name=val,name=val
    for(var i=0;i<qvars.length;i++){
        var qv=qvars[i].split("=");
        if(qv[0]==name)
            return qv[1];
      }
      return '';
}
});