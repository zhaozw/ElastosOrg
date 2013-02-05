/*global document, window, $*/
$(document).ready(function () {
    $("ul#nav li a").click(function () { //When trigger is clicked...
        //Following events are applied to the sub-nav itself (moving sub-nav up and down)
        $(this).parent().find("ul.sub-nav").slideDown('fast').show(); //Drop down the sub-nav on click
        $(this).parent().hover(function () {
        }, function () {  
            $(this).parent().find("ul.sub-nav").slideUp('slow'); //When the mouse hovers out of the sub-nav, move it back up
        });
    });
});

