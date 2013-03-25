/*
 * jQuery @mentions plugin, v1.1
 *
 * Copyright 2011, Paul Gibbs <paul@byotos.com>.
 *
 * Licensed under GPL Version 2 and/or 3.
 * http://www.gnu.org/licenses/gpl-2.0.html
 * http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Requires jQuery 1.6+
 */
(function(a){a.fn.mentions=function(b){b=a.extend({},a.fn.mentions.defaults,b);var c={up:38,down:40,enter:13,esc:27,at:64};return this.each(function(){var q=a(this),l=false,i=null,p=false,f="",m="",d=0,e=a.meta?a.extend({},b,q.data()):b,g=new RegExp("@[\\w\\\\\\.\\-]+\\s?(?:[\\w\\\\\\.\\-]+\\s?){0,"+(e.max_name_parts-1)+"}(?:[\\w\\\\\\.\\-]w+)?$");function k(){var o='<ul id="mentions-autosuggest"></ul>';if(e.resultsbox){if("string"==typeof e.resultsbox){if("prepend"==e.resultsbox_position){a(e.resultsbox).prepend(o)}else{if("append"==e.resultsbox_position){a(e.resultsbox).append(o)}else{if("before"==e.resultsbox_position){a(e.resultsbox).before(o)}else{if("after"==e.resultsbox_position){a(e.resultsbox).after(o)}}}}}else{if(e.resultsbox){if("prepend"==e.resultsbox_position){e.resultsbox.prepend(o)}else{if("append"==e.resultsbox_position){e.resultsbox.append(o)}else{if("before"==e.resultsbox_position){e.resultsbox.before(o)}else{if("after"==e.resultsbox_position){e.resultsbox.after(o)}}}}}}}else{if("prepend"==e.resultsbox_position){q.parent().prepend(o)}else{if("append"==e.resultsbox_position){q.parent().append(o)}else{if("before"==e.resultsbox_position){q.parent().before(o)}else{if("after"==e.resultsbox_position){q.parent().after(o)}}}}}i=a("#mentions-autosuggest")}function r(){clearTimeout(d);i.slideUp("slow",function(){i.empty();q[0].setSelectionRange(q.val().length,q.val().length);q.focus()});l=p=false;f=""}function h(){if(!l){clearTimeout(d);return}m=g.exec(q.val());if(!m){f=m="";return}if(f==m){return}if(a.isFunction(e.start)){e.start.call(this,m)}if("whats-new-options"==a(e.resultsbox).attr("id")&&"inherit"!=a(e.resultsbox).css("overflow")){a(e.resultsbox).css("overflow","inherit").css("position","relative").css("z-index","100")}if(!p){i.css("width",q.outerWidth(false)-4).html('<li class="section ajaxloader"><p><span class="ajax-loader" style="display: inline"></span>'+BPMentions.searching+"</p></li>").show()}else{i.css("width",q.outerWidth(false)-4).prepend('<li class="section ajaxloader"><p><span class="ajax-loader" style="display: inline"></span>'+BPMentions.searching+"</p></li>").show()}var o=i.data("bpl_"+m);if(o){n(o,"success",null,m)}else{a.ajax({dataType:e.dataType,type:"POST",url:ajaxurl,data:{action:"activity_mention_autosuggest",cookie:encodeURIComponent(document.cookie),format:e.dataType,limit:e.max_suggestions,search:m},error:e.error,success:function(s,u,t){n(s,u,t,m)}})}f=m}function n(o,u,s,t){if("success"==u){i.data("bpl_"+t,o)}if(a.isFunction(e.success)){e.success.call(o,u,s)}if("html"==e.dataType){i.find("li.ajaxloader").fadeOut("fast",function(){a(this).remove();if("-1"==o){if(a.isFunction(e.error)){e.error.call(o,u,s)}i.html('<li class="section error"><p><span>'+BPMentions.error1+"</span> "+BPMentions.error2+"</p></li>");return}else{if("0"==o){i.html('<li class="section error"><p><span>'+BPMentions.noresults1+"</span> "+BPMentions.noresults2+"</p></li>")}else{i.css("width",q.outerWidth(false)-4);if(!p){i.slideUp("slow",function(){i.html(o).slideDown("slow")})}else{i.html(o)}}}p=true});if(a.isFunction(e.complete)){e.complete.call(o,u,s)}}}function j(o){q.val(q.val().substring(0,q.val().lastIndexOf("@"))+"@"+o+" ");r()}a(function(){k();q.bind("keypress.mentions",function(o){if(c.at==o.which&&!l){l=true}else{if(l){clearTimeout(d);if(p){d=setTimeout(h,800)}else{d=setTimeout(h,350)}}}});q.bind("keyup.mentions",function(o){if((c.esc==o.keyCode||c.enter==o.keyCode)&&p){o.preventDefault();r()}});q.bind("focusout.mentions",function(o){if(p){r()}});i.delegate("li:not(.section)","click.mentions",function(o){o.preventDefault();j(a(this).attr("class"))})})})};a.fn.mentions.defaults={resultsbox_position:"append",dataType:"html",max_name_parts:3,max_suggestions:6,resultsbox:"",start:null,success:null,complete:null,error:null}})(jQuery);