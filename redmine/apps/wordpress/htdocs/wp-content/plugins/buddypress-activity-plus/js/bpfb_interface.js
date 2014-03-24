var _bpfbActiveHandler = false;

(function($){
$(function() {

var $form;
var $text;
var $textContainer;

jQuery.fn.extend({
    getCurPos: function(){
    	var $= jQuery;
        var e=$(this).get(0);
        e.focus();
        if(e.selectionStart){    //FF
            return e.selectionStart;
        }
        if(document.selection){    //IE
            var r = document.selection.createRange();
            if (r == null) {
                return e.value.length;
            }
            var re = e.createTextRange();
            var rc = re.duplicate();
            re.moveToBookmark(r.getBookmark());
            rc.setEndPoint('EndToStart', re);
            return rc.text.length;
        }
        return e.value.length;
    },
    setCurPos: function(pos) {
    	var $= jQuery;
        var e=$(this).get(0);
        e.focus();
        if (e.setSelectionRange) {
            e.setSelectionRange(pos, pos);
        } else if (e.createTextRange) {
            var range = e.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    }        
});

/**
 * Video insertion/preview handler.
 */
var BpfbVideoHandler = function () {
	$container = $(".bpfb_controls_container");

	var resize = function () {
		$('#bpfb_video_url').width($container.width());
	};

	var createMarkup = function () {
		var html = '<input type="text" id="bpfb_video_url" name="bpfb_video_url" placeholder="' + l10nBpfb.paste_video_url + '" value="" />' +
			'<input type="button" id="bpfb_video_url_preview" value="' + l10nBpfb.preview + '" />';
		$container.empty().append(html);

		$(window).off("resize.bpfb").on("resize.bpfb", resize);
		resize();
		$('#bpfb_video_url').focus(function () {
			$(this)
				.select()
				.addClass('changed')
			;
		});

		$('#bpfb_video_url').keypress(function (e) {
			if (13 != e.which) return true;
			createVideoPreview();
			return false;
		});
		$('#bpfb_video_url').change(createVideoPreview);
		$('#bpfb_video_url_preview').click(createVideoPreview);
	};

	var createVideoPreview = function () {
		var url = $('#bpfb_video_url').val();
		if (!url) return false;
		$('.bpfb_preview_container').html('<div class="bpfb_waiting"></div>');
		$.post(ajaxurl, {"action":"bpfb_preview_video", "data":url}, function (data) {
			$('.bpfb_preview_container').empty().html(data);
                        //handle preview error
                        if(data.match('<iframe')){
			   $('.bpfb_action_container').html(
				'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_video + '" /> ' +
				'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		           );
                        }
			$("#bpfb_cancel_action").hide();
		});
	};

	var processForSave = function () {
		return {
			"bpfb_video_url": $("#bpfb_video_url").val()
		};
	};

	var init = function () {
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};

	var destroy = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').show();
		$(window).off("resize.bpfb");
	};

	init ();

	return {"destroy": destroy, "get": processForSave};
};


/**
 * Link insertion/preview handler.
 */
var BpfbLinkHandler = function () {
	$container = $(".bpfb_controls_container");

	var resize = function () {
		$('#bpfb_link_preview_url').width($container.width());
	};

	var createMarkup = function () {
		var html = '<input type="text" id="bpfb_link_preview_url" name="bpfb_link_preview_url" placeholder="' + l10nBpfb.paste_link_url + '" value="" />' +
			'<input type="button" id="bpfb_link_url_preview" value="' + l10nBpfb.preview + '" />';
		$container.empty().append(html);

		$(window).off("resize.bpfb").on("resize.bpfb", resize);
		resize();
		$('#bpfb_link_preview_url').focus(function () {
			$(this)
				.select()
				.addClass('changed')
			;
		});

		$('#bpfb_link_preview_url').keypress(function (e) {
			if (13 != e.which) return true;
			createLinkPreview();
			return false;
		});
		$('#bpfb_link_preview_url').change(createLinkPreview);
		$('#bpfb_link_url_preview').click(createLinkPreview);
	};

	var createPreviewMarkup = function (data) {
		if (!data.url) {
			$('.bpfb_preview_container').empty().html(data.title);
			return false;
		}
		var imgs = '';
		$.each(data.images, function(idx, img) {
			if (!img) return true;
			var url = img.match(/^http/) ? img : data.url + '/' + img;
			imgs += '<img class="bpfb_link_preview_image" src="' + url + '" />';
		});
		var html = '<table border="0">' +
			'<tr>' +
				'<td>' +
					'<div class="bpfb_link_preview_container">' +
						imgs +
						'<input type="hidden" name="bpfb_link_img" value="" />' +
					'</div>' +
				'</td>' +
				'<td>' +
					'<div class="bpfb_link_preview_title">' + data.title + '</div>' +
					'<input type="hidden" name="bpfb_link_title" value="' + data.title + '" />' +
					'<div class="bpfb_link_preview_url">' + data.url + '</div>' +
					'<input type="hidden" name="bpfb_link_url" value="' + data.url + '" />' +
					'<div class="bpfb_link_preview_body">' + data.text + '</div>' +
					'<input type="hidden" name="bpfb_link_body" value="' + data.text + '" />' +
					'<div class="bpfb_thumbnail_chooser">' +
						'<span class="bpfb_left"><img class="bpfb_thumbnail_chooser_left" src="' + _bpfb_data.root_url + '/img/system/left.gif" />&nbsp;</span>' +
						'<span class="bpfb_thumbnail_chooser_label">' + l10nBpfb.choose_thumbnail + '</span>' +
						'<span class="bpfb_right">&nbsp;<img class="bpfb_thumbnail_chooser_right" src="' + _bpfb_data.root_url + '/img/system/right.gif" /></span>' +
						'<br /><input type="checkbox" id="bpfb_link_no_thumbnail" /> <label for="bpfb_link_no_thumbnail">' + l10nBpfb.no_thumbnail + '</label>' +
					'</div>' +
				'</td>' +
			'</tr>' +
		'</table>';
		$('.bpfb_preview_container').empty().html(html);
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_link + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();

		$('img.bpfb_link_preview_image').hide();
		$('img.bpfb_link_preview_image').first().show();
		$('input[name="bpfb_link_img"]').val($('img.bpfb_link_preview_image').first().attr('src'));

		//$('.bpfb_thumbnail_chooser_left').click(function () {
		$('.bpfb_thumbnail_chooser .bpfb_left').click(function () {
			var $cur = $('img.bpfb_link_preview_image:visible');
			var $prev = $cur.prev('.bpfb_link_preview_image');
			if ($prev.length) {
				$cur.hide();
				$prev
					.width($('.bpfb_link_preview_container').width())
					.show();
				$('input[name="bpfb_link_img"]').val($prev.attr('src'));
			}
			return false;
		});
		//$('.bpfb_thumbnail_chooser_right').click(function () {
		$('.bpfb_thumbnail_chooser .bpfb_right').click(function () {
			var $cur = $('img.bpfb_link_preview_image:visible');
			var $next = $cur.next('.bpfb_link_preview_image');
			if ($next.length) {
				$cur.hide();
				$next
					.width($('.bpfb_link_preview_container').width())
					.show();
				$('input[name="bpfb_link_img"]').val($next.attr('src'));
			}
			return false;
		});
		$("#bpfb_link_no_thumbnail").click(function () {
			if ($("#bpfb_link_no_thumbnail").is(":checked")) {
				$('img.bpfb_link_preview_image:visible').hide();
				$('input[name="bpfb_link_img"]').val('');
				$(".bpfb_left, .bpfb_right, .bpfb_thumbnail_chooser_label").hide();
			} else {
				var $img = $('img.bpfb_link_preview_image:first');
				$img.show();
				$(".bpfb_left, .bpfb_right, .bpfb_thumbnail_chooser_label").show();
				$('input[name="bpfb_link_img"]').val($img.attr('src'));
			}

		});
	};

	var createLinkPreview = function () {
		var url = $('#bpfb_link_preview_url').val();
		if (!url) return false;
		$('.bpfb_preview_container').html('<div class="bpfb_waiting"></div>');
		$.post(ajaxurl, {"action":"bpfb_preview_link", "data":url}, function (data) {
			createPreviewMarkup(data);
		});
	};

	var processForSave = function () {
		return {
			"bpfb_link_url": $('input[name="bpfb_link_url"]').val(),
			"bpfb_link_image": $('input[name="bpfb_link_img"]').val(),
			"bpfb_link_title": $('input[name="bpfb_link_title"]').val(),
			"bpfb_link_body": $('input[name="bpfb_link_body"]').val()
		};
	};

	var init = function () {
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};

	var destroy = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').show();
		$(window).off("resize.bpfb");
	};

	init ();

	return {"destroy": destroy, "get": processForSave};
};

/**
 * Smile insertion/preview handler.
 */
var BpfbSmileHandler = function () {
	$container = $(".bpfb_controls_container");

	var resize = function () {
		$('#bpfb_smile_preview_url').width($container.width());
	};

	var createMarkup = function () {
		//var html = '<input type="text" id="bpfb_mile_preview_url" name="bpfb_smile_preview_url" placeholder="' + l10nBpfb.paste_smile_url + '" value="" />' +
		//	'<input type="button" id="bpfb_smile_url_preview" value="' + l10nBpfb.preview + '" />';
        var html=$('<ul>'+
		'<li><img src="/wp-includes/images/smilies/icon_question.gif" alt=":?:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_razz.gif" alt=":razz:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_sad.gif" alt=":sad:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_evil.gif" alt=":evil:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_exclaim.gif" alt=":!:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_smile.gif" alt=":smile:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_redface.gif" alt=":oops:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_biggrin.gif" alt=":grin:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_surprised.gif" alt=":eek:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_eek.gif" alt=":shock:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_confused.gif" alt=":???:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_cool.gif" alt=":cool:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_lol.gif" alt=":lol:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_mad.gif" alt=":mad:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_twisted.gif" alt=":twisted:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_rolleyes.gif" alt=":roll:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_wink.gif" alt=":wink:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_idea.gif" alt=":idea:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_arrow.gif" alt=":arrow:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_neutral.gif" alt=":neutral:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_cry.gif" alt=":cry:" /></li>'+
		'<li><img src="/wp-includes/images/smilies/icon_mrgreen.gif" alt=":mrgreen:" /></li>'+
		'</ul>');
		html.find("li").css({"float":"left","padding":"2px"});
		html.find("li").click(function(){
			value=$("#whats-new").val();
			value=value.replace(value.substring(0,jQuery("#whats-new").getCurPos()),value.substring(0,jQuery("#whats-new").getCurPos())+$(this).find('img').attr('alt')+" ");
			$("#whats-new").val(value);
		});
		$container.empty().append(html);

		$(window).off("resize.bpfb").on("resize.bpfb", resize);
		resize();
		$('#bpfb_smile_preview_url').focus(function () {
			$(this)
				.select()
				.addClass('changed')
			;
		});

		$('#bpfb_smile_preview_url').keypress(function (e) {
			if (13 != e.which) return true;
			createsmilePreview();
			return false;
		});
		$('#bpfb_smile_preview_url').change(createsmilePreview);
		$('#bpfb_smile_url_preview').click(createsmilePreview);
	};

	var createPreviewMarkup = function (data) {
		if (!data.url) {
			$('.bpfb_preview_container').empty().html(data.title);
			return false;
		}
		var imgs = '';
		$.each(data.images, function(idx, img) {
			if (!img) return true;
			var url = img.match(/^http/) ? img : data.url + '/' + img;
			imgs += '<img class="bpfb_smile_preview_image" src="' + url + '" />';
		});
		var html = '<table border="0">' +
			'<tr>' +
				'<td>' +
					'<div class="bpfb_smile_preview_container">' +
						imgs +
						'<input type="hidden" name="bpfb_smile_img" value="" />' +
					'</div>' +
				'</td>' +
				'<td>' +
					'<div class="bpfb_smile_preview_title">' + data.title + '</div>' +
					'<input type="hidden" name="bpfb_smile_title" value="' + data.title + '" />' +
					'<div class="bpfb_smile_preview_url">' + data.url + '</div>' +
					'<input type="hidden" name="bpfb_smile_url" value="' + data.url + '" />' +
					'<div class="bpfb_smile_preview_body">' + data.text + '</div>' +
					'<input type="hidden" name="bpfb_smile_body" value="' + data.text + '" />' +
					'<div class="bpfb_thumbnail_chooser">' +
						'<span class="bpfb_left"><img class="bpfb_thumbnail_chooser_left" src="' + _bpfb_data.root_url + '/img/system/left.gif" />&nbsp;</span>' +
						'<span class="bpfb_thumbnail_chooser_label">' + l10nBpfb.choose_thumbnail + '</span>' +
						'<span class="bpfb_right">&nbsp;<img class="bpfb_thumbnail_chooser_right" src="' + _bpfb_data.root_url + '/img/system/right.gif" /></span>' +
						'<br /><input type="checkbox" id="bpfb_smile_no_thumbnail" /> <label for="bpfb_smile_no_thumbnail">' + l10nBpfb.no_thumbnail + '</label>' +
					'</div>' +
				'</td>' +
			'</tr>' +
		'</table>';
		$('.bpfb_preview_container').empty().html(html);
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_smile + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();

		$('img.bpfb_smile_preview_image').hide();
		$('img.bpfb_smile_preview_image').first().show();
		$('input[name="bpfb_smile_img"]').val($('img.bpfb_smile_preview_image').first().attr('src'));

		//$('.bpfb_thumbnail_chooser_left').click(function () {
		$('.bpfb_thumbnail_chooser .bpfb_left').click(function () {
			var $cur = $('img.bpfb_smile_preview_image:visible');
			var $prev = $cur.prev('.bpfb_smile_preview_image');
			if ($prev.length) {
				$cur.hide();
				$prev
					.width($('.bpfb_smile_preview_container').width())
					.show();
				$('input[name="bpfb_smile_img"]').val($prev.attr('src'));
			}
			return false;
		});
		//$('.bpfb_thumbnail_chooser_right').click(function () {
		$('.bpfb_thumbnail_chooser .bpfb_right').click(function () {
			var $cur = $('img.bpfb_smile_preview_image:visible');
			var $next = $cur.next('.bpfb_smile_preview_image');
			if ($next.length) {
				$cur.hide();
				$next
					.width($('.bpfb_smile_preview_container').width())
					.show();
				$('input[name="bpfb_smile_img"]').val($next.attr('src'));
			}
			return false;
		});
		$("#bpfb_smile_no_thumbnail").click(function () {
			if ($("#bpfb_smile_no_thumbnail").is(":checked")) {
				$('img.bpfb_smile_preview_image:visible').hide();
				$('input[name="bpfb_smile_img"]').val('');
				$(".bpfb_left, .bpfb_right, .bpfb_thumbnail_chooser_label").hide();
			} else {
				var $img = $('img.bpfb_smile_preview_image:first');
				$img.show();
				$(".bpfb_left, .bpfb_right, .bpfb_thumbnail_chooser_label").show();
				$('input[name="bpfb_smile_img"]').val($img.attr('src'));
			}

		});
	};

	var createsmilePreview = function () {
		var url = $('#bpfb_smile_preview_url').val();
		if (!url) return false;
		$('.bpfb_preview_container').html('<div class="bpfb_waiting"></div>');
		$.post(ajaxurl, {"action":"bpfb_preview_smile", "data":url}, function (data) {
			createPreviewMarkup(data);
		});
	};

	var processForSave = function () {
		return {
			"bpfb_smile_url": $('input[name="bpfb_smile_url"]').val(),
			"bpfb_smile_image": $('input[name="bpfb_smile_img"]').val(),
			"bpfb_smile_title": $('input[name="bpfb_smile_title"]').val(),
			"bpfb_smile_body": $('input[name="bpfb_smile_body"]').val()
		};
	};

	var init = function () {
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};

	var destroy = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').show();
		$(window).off("resize.bpfb");
	};

	init ();

	return {"destroy": destroy, "get": processForSave};
};

/**
 * Photos insertion/preview handler.
 */
var BpfbPhotoHandler = function () {
	$container = $(".bpfb_controls_container");

	var createMarkup = function () {
		var html = '<div id="bpfb_tmp_photo"> </div>' +
			'<ul id="bpfb_tmp_photo_list"></ul>' +
			'<input type="button" id="bpfb_add_remote_image" value="' + l10nBpfb.add_remote_image + '" /><div id="bpfb_remote_image_container"></div>' +
			'<input type="button" id="bpfb_remote_image_preview" value="' + l10nBpfb.preview + '" />';
		$container.append(html);

		var uploader = new qq.FileUploader({
			"element": $('#bpfb_tmp_photo')[0],
			"listElement": $('#bpfb_tmp_photo_list')[0],
			"allowedExtensions": ['jpg', 'jpeg', 'png', 'gif'],
			"action": ajaxurl,
			"params": {
				"action": "bpfb_preview_photo"
			},
			"onSubmit": function (id) {
				if (!parseInt(l10nBpfb._max_images, 10)) return true; // Skip check
				id = parseInt(id, 10);
				if (!id) id = $("img.bpfb_preview_photo_item").length;
				if (!id) return true;
				if (id < parseInt(l10nBpfb._max_images, 10)) return true;
				if (!$("#bpfb-too_many_photos").length) $("#bpfb_tmp_photo").append(
					'<p id="bpfb-too_many_photos">' + l10nBpfb.images_limit_exceeded + '</p>'
				);
				return false;
			},
			"onComplete": createPhotoPreview,
			template: '<div class="qq-uploader">' +
                '<div class="qq-upload-drop-area"><span>' + l10nBpfb.drop_files + '</span></div>' +
                '<div class="qq-upload-button">' + l10nBpfb.upload_file + '</div>' +
                '<ul class="qq-upload-list"></ul>' +
             '</div>'
		});

		$("#bpfb_remote_image_preview").hide();
		$("#bpfb_tmp_photo").click(function () {
			if ($("#bpfb_add_remote_image").is(":visible")) $("#bpfb_add_remote_image").hide();
		});
		$("#bpfb_add_remote_image").click(function () {
			if (!$("#bpfb_remote_image_preview").is(":visible")) $("#bpfb_remote_image_preview").show();
			if ($("#bpfb_tmp_photo").is(":visible")) $("#bpfb_tmp_photo").hide();
			$("#bpfb_add_remote_image").val(l10nBpfb.add_another_remote_image);
			$("#bpfb_remote_image_container").append(
				'<input type="text" class="bpfb_remote_image" size="64" value="" /><br />'
			);
			$("#bpfb_remote_image_container .bpfb_remote_image").width($container.width());
		});
		$(document).on('change', "#bpfb_remote_image_container .bpfb_remote_image", createRemoteImagePreview);
		$("#bpfb_remote_image_preview").click(createRemoteImagePreview);
	};

	var createRemoteImagePreview = function () {
		var imgs = [];
		$("#bpfb_remote_image_container .bpfb_remote_image").each(function () {
			imgs[imgs.length] = $(this).val();
		});
		$.post(ajaxurl, {"action":"bpfb_preview_remote_image", "data":imgs}, function (data) {
			var html = '';
			$.each(data, function() {
				html += '<img class="bpfb_preview_photo_item" src="' + this + '" width="80px" />' +
				'<input type="hidden" class="bpfb_photos_to_add" name="bpfb_photos[]" value="' + this + '" />';
			});
			$('.bpfb_preview_container').html(html);
		});
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_photos + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();
	};

	var createPhotoPreview = function (id, fileName, resp) {
		if ("error" in resp) return false;
		var html = '<img class="bpfb_preview_photo_item" src="' + _bpfb_data.temp_img_url + resp.file + '" width="80px" />' +
			'<input type="hidden" class="bpfb_photos_to_add" name="bpfb_photos[]" value="' + resp.file + '" />';
		$('.bpfb_preview_container').append(html);
		$('.bpfb_action_container').html(
			'<p><input type="button" class="button-primary bpfb_primary_button" id="bpfb_submit" value="' + l10nBpfb.add_photos + '" /> ' +
			'<input type="button" class="button" id="bpfb_cancel" value="' + l10nBpfb.cancel + '" /></p>'
		);
		$("#bpfb_cancel_action").hide();
	};

	var removeTempImages = function (rti_callback) {
		var $imgs = $('input.bpfb_photos_to_add');
		if (!$imgs.length) return rti_callback();
		$.post(ajaxurl, {"action":"bpfb_remove_temp_images", "data": $imgs.serialize().replace(/%5B%5D/g, '[]')}, function (data) {
			rti_callback();
		});
	};

	var processForSave = function () {
		var $imgs = $('input.bpfb_photos_to_add');
		var imgArr = [];
		$imgs.each(function () {
			imgArr[imgArr.length] = $(this).val();
		});
		return {
			"bpfb_photos": imgArr//$imgs.serialize().replace(/%5B%5D/g, '[]')
		};
	};

	var init = function () {
		$container.empty();
		$('.bpfb_preview_container').empty();
		$('.bpfb_action_container').empty();
		$('#aw-whats-new-submit').hide();
		createMarkup();
	};

	var destroy = function () {
		removeTempImages(function() {
			$container.empty();
			$('.bpfb_preview_container').empty();
			$('.bpfb_action_container').empty();
			$('#aw-whats-new-submit').show();
		});
	};

	removeTempImages(init);

	return {"destroy": destroy, "get": processForSave};
};


/* === End handlers  === */


/**
 * Main interface markup creation.
 */
function createMarkup () {
	var html = '<div class="bpfb_actions_container bpfb-theme-' + _bpfb_data.theme.replace(/[^-_a-z0-9]/ig, '') + ' bpfb-alignment-' + _bpfb_data.alignment.replace(/[^-_a-z0-9]/ig, '') + '">' +
		'<div class="bpfb_toolbar_container">' +
			'<a href="#photos" class="bpfb_toolbarItem" title="' + l10nBpfb.add_photos + '" id="bpfb_addPhotos"><span>' + l10nBpfb.add_photos + '</span></a>' +
			'&nbsp;' +
			'<a href="#videos" class="bpfb_toolbarItem" title="' + l10nBpfb.add_videos + '" id="bpfb_addVideos"><span>' + l10nBpfb.add_videos + '</span></a>' +
			'&nbsp;' +
			'<a href="#links" class="bpfb_toolbarItem" title="' + l10nBpfb.add_links + '" id="bpfb_addLinks"><span>' + l10nBpfb.add_links + '</span></a>' +
			'&nbsp;' +
			'<a href="#smiles" class="" title="' + 'smiles' + '" id="bpfb_addSmiles"><img src="/wp-includes/images/smilies/icon_smile.gif" alt=":smile:" /></a>' +
		'</div>' +
		'<div class="bpfb_controls_container">' +
		'</div>' +
		'<div class="bpfb_preview_container">' +
		'</div>' +
		'<div class="bpfb_action_container">' +
		'</div>' +
		'<input type="button" id="bpfb_cancel_action" value="' + l10nBpfb.cancel + '" style="display:none" />' +
	'</div>';
	$form.wrap('<div class="bpfb_form_container" />');
	$textContainer.after(html);
}


/**
 * Initializes the main interface.
 */
function init () {
	$form = $("#whats-new-form");
	$text = $form.find('textarea[name="whats-new"]');
	$textContainer = $form.find('#whats-new-textarea');
	createMarkup();
	$('#bpfb_addPhotos').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbPhotoHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	$('#bpfb_addLinks').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbLinkHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	$('#bpfb_addSmiles').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbSmileHandler();
		//$("#bpfb_cancel_action").show();
		$("#aw-whats-new-submit").show();
		return false;
	});
	$('#bpfb_addVideos').click(function () {
		if (_bpfbActiveHandler) _bpfbActiveHandler.destroy();
		_bpfbActiveHandler = new BpfbVideoHandler();
		$("#bpfb_cancel_action").show();
		return false;
	});
	$('#bpfb_cancel_action').click(function () {
		$(".bpfb_toolbarItem.bpfb_active").removeClass("bpfb_active");
		_bpfbActiveHandler.destroy();
		$("#bpfb_cancel_action").hide();
		return false;
	});
	$(".bpfb_toolbarItem").click(function () {
		$(".bpfb_toolbarItem.bpfb_active").removeClass("bpfb_active");
		$(this).addClass("bpfb_active");
	});
	$(document).on('click', '#bpfb_submit', function () {
		var params = _bpfbActiveHandler.get();
		var group_id = $('#whats-new-post-in').length ? $('#whats-new-post-in').val() : 0;
		$.post(ajaxurl, {
			"action": "bpfb_update_activity_contents",
			"data": params,
			"content": $text.val(),
			"group_id": group_id
		}, function (data) {
			_bpfbActiveHandler.destroy();
			$text.val('');
			$('#activity-stream').prepend(data.activity);
			/**
			 * Handle image scaling in previews.
			 */
			$(".bpfb_final_link img").each(function () {
				$(this).width($(this).parents('div').width());
			});
		});
	});
	$(document).on('click', '#bpfb_cancel', function () {
		$(".bpfb_toolbarItem.bpfb_active").removeClass("bpfb_active");
		_bpfbActiveHandler.destroy();
	});
}

// Only initialize if we're supposed to.
/*
if (
	!('ontouchstart' in document.documentElement)
	||
	('ontouchstart' in document.documentElement && (/iPhone|iPod|iPad/i).test(navigator.userAgent))
	) {
	if ($("#whats-new-form").is(":visible")) init();
}
*/
// Meh, just do it - newish Droids seem to work fine.
if ($("#whats-new-form").is(":visible")) init();

/**
 * Handle image scaling in previews.
 */
$(".bpfb_final_link img").each(function () {
	$(this).width($(this).parents('div').width());
});


jQuery(".bpfb_actions_container").css("height","52px");
jQuery("#whats-new-options").css({position: "static",height:"50px",width:"300px",float:"right",marginTop:"-50px"});
jQuery("#whats-new-content").css({height:"auto"});

setTimeout(function(){
jQuery("#whats-new-textarea").append(jQuery("#mentions-autosuggest"));
},1000);

});
})(jQuery);
