<?php
/*
Plugin Name: Buddypress Activity Anywhere
Plugin URI: http://geomywp.com
Description: Post update to activity stream from anywhere in the main site or any other subsite in a multisite installation.
Author: Eyal Fitoussi
Version: 99.1.0
Author URI: http://geomywp.com

*/
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

?>
<?php
function bpaa_init() {
	//if user logged out no need to load the plugin
	if (!is_user_logged_in()) return;

	// add link to toobar
	function bpaa_admin_bar_button($admin_bar){
		global $wp_admin_bar;
		$admin_bar->add_menu( array(
				'id'    => 'post-update',
				'title' => 'MicroBLOG',
				'href'  => '#',
				'meta'  => array(
				'title' => __('What\'s new'),
			),
		));
	}
	add_action('admin_bar_menu', 'bpaa_admin_bar_button', 100);

	function bpaa_form() { ?>
		<style>
			#wp-admin-bar-post-update {
				position: relative;
				width: 90px;
			}
			#bpaa-form-wrapper {
				position: absolute;
				width: 600px;
				padding: 10px;
				background: #E5F2F7;
				top: 29px;
				-webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
				-webkit-border-radius: 3px;
				border: 1px solid rgba(100, 100, 100, .4);
				-webkit-background-clip: padding-box;
				padding-bottom: 10px;
				-webkit-border-radius: 3px;
				-moz-border-radius: 3px;
				border-radius: 3px;
			}

			#bpaa-form-wrapper form textarea {
				float: left;
				width: 96% !important;
				color:#666;
				font-size:14px;
				line-height:normal;
				-moz-border-radius: 8px; -webkit-border-radius: 8px;
				margin:5px 0px 10px 0px;
				padding:10px;
				height:160px;
				border:#999 1px solid;
				transition: all 0.25s ease-in-out;
				-webkit-transition: all 0.25s ease-in-out;
				-moz-transition: all 0.25s ease-in-out;
				box-shadow: 0 0 5px rgba(81, 203, 238, 0);
				-webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 0);
				-moz-box-shadow: 0 0 5px rgba(81, 203, 238, 0);
			}

			#bpaa-form-wrapper form textarea:focus {
				color:#000;
				outline:none;
				border:#35a5e5 1px solid;
				line-height:normal;
				box-shadow: 0 0 5px rgba(81, 203, 238, 1);
				-webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
				-moz-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
			}

			#bpaa-buttons-wrapper {
				float: left;
				margin-top: 5px;
			}

			#bpaa-form-wrapper form input[type="button"], #bpaa-form-wrapper form input[type="submit"]  {
				font: normal 12px sans-serif;
				padding: 5px;
				color: #777;
				text-shadow: none;
			}

			.bpaa-warning {
				border: 3px solid rgb(233, 130, 130) !important;
			}

			[draggable=true] {
				cursor: move;
			}

			.title {
				background: #f2f2f2;
				height: 30px;
				line-height: 30px;
				font-weight: 700;
				padding: 0 0 0 20px;
				font-size: 12px;
				vertical-align: middle;
				cursor: move;
			}

			#bpaa-submit {
				color: red;
				border-color: red;
			}
		</style>

		<!-- form -->
		<div id="bpaa-form-wrapper" hidden draggable="true">
			<div class="title">
				<span style="color:#333;">Post MicroBLOG</span>
			</div>
			<form id="bpaa-form" action="" method="post">
				<?php wp_nonce_field('bpaa_submit_form','bpaa_update_activity'); ?>
				<div name="bpaa_reply" id="bpaa_reply" value="" style="background-color:green;height:0px;visibility:hidden;"></div>
				<textarea name="bpaa_textarea" id="bpaa-textarea" value="" class="bpaa-input" placeholder="Post something to activity..."></textarea>
				<input type="hidden" id="o_id" name="o_id" value="0" />
				<div id="bpaa-buttons-wrapper">
					<input type="button" class="post-button" id="bpaa-submit" value="Post MicroBLOG" name="bpaa_submit" style="font-weight:bold;background:#B7DBE9;color:red;border-width:2px;">
					<input type="button" class="button" id="bpaa-cancel" value="cancel" onclick="clear_bpaa_reply();"/>
				</div>
				<div id="group-id-box" style="float:right;color:#14A0CD;">
					<?php _e( 'Post in', 'buddypress' ); ?>
					<select id="group-id" name="group-id" style="color:#14A0CD;background:#F7F8FF;">
						<option selected="selected" value="0" style="color:#333;"><?php _e( 'My MicroBLOG', 'buddypress' ); ?></option>
						<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0' ) ) :
							while ( bp_groups() ) : bp_the_group(); ?>
								<option value="<?php bp_group_id(); ?>" style="color:#333;"><?php bp_group_name(); ?></option>
							<?php endwhile;
						endif; ?>
					</select>
				</div>
			</form>
		</div>
		<script>
			function clear_bpaa_reply() {
				jQuery('#bpaa_reply').css({'height':'0px','visibility':'hidden'});
				jQuery("#bpaa_reply").html("");
			}

			jQuery(document).ready(function($) {
				//append the form into our admin button
				$('#bpaa-form-wrapper').appendTo('#wp-admin-bar-post-update');

				$('li#wp-admin-bar-post-update a').addClass('bpaa-trigger');
				//remove warning when in input fields
				$('.bpaa-input').focus(function() {
					$('#bpaa-textarea').removeClass('bpaa-warning');
				});
				//when submit check if at least one filed as value and if so submit the form
				//otherwise add warning
				$("#bpaa-submit").click(function() {
					//if(!$.trim($('#bpaa-textarea').value).length) { // zero-length string AFTER a trim
					if ( $('#bpaa-textarea').val() ) {
						var reply = $("#bpaa_reply").html();
						if (reply!="")
							reply = "\n//"+$("#bpaa_reply").html();

						//$("#bpaa-form").submit();
					   	var data = {
							action: 'activity_add_anywhere',
							bpaa_textarea: $("#bpaa-textarea").val()+reply,
							bpaa_update_activity: $("#bpaa_update_activity").val(),
							o_id: $("#o_id").val()
						};

						$.post(ajaxurl, data, function(response) {
							$('#bpaa-form-wrapper').stop().fadeOut('fast');
							$("#bpaa-textarea").val("");
							$("#o_id").val("0");
							clear_bpaa_reply();
						});

					} else {
						$('#bpaa-textarea').addClass('bpaa-warning');
					}
				});
				// show and hide the form
				$(".bpaa-trigger").click(function(event) {
					event.preventDefault();
					$('#bpaa-form-wrapper').stop().fadeIn('fast');
				});
				$("#bpaa-cancel").click(function() {
					$('#bpaa-form-wrapper').stop().fadeOut('fast');
				});
			});
		</script>
	<?php
	}
	add_action('wp_footer','bpaa_form');
}
// load plugin
add_action('bp_init','bpaa_init');


function ajax_activity_add_anywhere() {
	//if user logged out no need to load the plugin
	if (!is_user_logged_in()) return;

	global $bp;
	// create the new activity and save to activity table

	$content = $_POST['bpaa_textarea'];

	if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['bpaa_update_activity'] ) && wp_verify_nonce($_POST['bpaa_update_activity'], 'bpaa_submit_form') && (!empty($content)) ) {

		$group_id = $_POST['group-id'];
		if (isset($group_id) && (intval($group_id) > 0)) {
			$activity_args = array(
				'action' => '<a href="' . $bp->loggedin_user->domain .'profile">' . $bp->loggedin_user->fullname .'</a> posted an update',
				'component' => 'groups',
				'content' => $content,
				'type' => 'activity_update',
				'primary_link' => $bp->loggedin_user->domain,
				'item_id' => $group_id,
				'secondary_item_id' => false,
			);
			$activity_id = groups_record_activity($activity_args);
			groups_update_groupmeta($group_id, 'last_activity', bp_core_current_time());
		} else {
			$activity_args = array(
				'action' => '<a href="' . $bp->loggedin_user->domain .'profile">' . $bp->loggedin_user->fullname .'</a> posted an update',
				'content' => $content,
				'component' => 'profile',
				'type' => 'activity_update',
				'primary_link' => '',
				'user_id' => $bp->loggedin_user->id,
				'item_id' => false,
				'secondary_item_id' => false,
				'recorded_time' => gmdate( "Y-m-d H:i:s" ),
				'hide_sitewide' => false
			);
			$activity_id = bp_activity_add($activity_args);
		}

		if ( intval($_POST['o_id']) > 0 ) {
			bp_activity_set_forward_count($_POST['o_id']);
		}
	}
}

add_action('wp_ajax_activity_add_anywhere', 'ajax_activity_add_anywhere');

?>