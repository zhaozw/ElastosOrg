/* Author: 

*/

jQuery(document).ready(function(){
    
    // Fancybox    
    //log("Adding Fancybox");
    var fb_IMG_select = 'a[href$=".jpg"]:not(.nofancybox),a[href$=".JPG"]:not(.nofancybox),a[href$=".gif"]:not(.nofancybox),a[href$=".GIF"]:not(.nofancybox),a[href$=".png"]:not(.nofancybox),a[href$=".PNG"]:not(.nofancybox)';
    jQuery(fb_IMG_select).addClass('fancybox').attr('rel', 'gallery');
    jQuery('a.fancybox').fancybox();
    
    
});






















