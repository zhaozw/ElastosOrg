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
	
	global $bp;
	// create the new activity and save to activity table
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['bpaa_update_activity'] ) && wp_verify_nonce($_POST['bpaa_update_activity'], 'bpaa_submit_form') && ( !empty($_POST['bpaa_textarea']) || !empty($_POST['bpaa_video_link']) ) ) {	
		
		$activity_args = array(
			'action' => '<a href="' . $bp->loggedin_user->domain .'profile">' . $bp->loggedin_user->fullname .'</a> posted an update', 
			'content' => $_POST['bpaa_textarea'],
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
		
		//save video to activity meta
		if (isset($_POST['bpaa_video_link']) && !empty($_POST['bpaa_video_link']) ) bp_activity_update_meta( $activity_id, 'bpaa_video_link', $_POST['bpaa_video_link'] );
	}
	
	// display link as video in activity
	function bpaa_display_video_activity() {
		global $bp;	
		$bpaa_vl = bp_activity_get_meta( bp_get_activity_id(), 'bpaa_video_link' );
		if( isset( $bpaa_vl )  && !empty( $bpaa_vl ) ) {
			echo 	'<div class="activity-video-wrapper">'; 
			echo  	wp_oembed_get( $bpaa_vl );
			echo 	'</div>';
		}
	}
	add_action( 'bp_activity_entry_content', 'bpaa_display_video_activity' );
	
	// add link to toobar
	function bpaa_admin_bar_button($admin_bar){
		global $wp_admin_bar; 
		$admin_bar->add_menu( array(
				'id'    => 'post-update',
				'title' => 'What\'s new',
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
				width: 120px;
			}
			#bpaa-form-wrapper {
				position: absolute;
				width: 800px;
				padding: 10px;
				background: rgba(255,255,255,0.98);
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
				width: 98%% !important;
				color:#666;
				font-size:14px;
				-moz-border-radius: 8px; -webkit-border-radius: 8px;
				margin:5px 0px 10px 0px;
				padding:10px;
				height:75px;
				border:#999 1px solid;
				font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
				transition: all 0.25s ease-in-out;
				-webkit-transition: all 0.25s ease-in-out;
				-moz-transition: all 0.25s ease-in-out;
				box-shadow: 0 0 5px rgba(81, 203, 238, 0);
				-webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 0);
				-moz-box-shadow: 0 0 5px rgba(81, 203, 238, 0);
			}

			#bpaa-form-wrapper form textarea:focus{
				color:#000;
				outline:none;
				border:#35a5e5 1px solid;
				font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
				box-shadow: 0 0 5px rgba(81, 203, 238, 1);
				-webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
				-moz-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
			}

			#bpaa-buttons-wrapper {
				float: left;
				margin-top: 5px;
			}

			#bpaa-form-wrapper form input[type="text"] {
				float: left;
				width: 100% !important;
				font-family: Helvetica, Arial, sans-serif;
				font-size: 12px;
				line-height: 12px;
				padding: 7px;
				margin: 0px;
				color: #777;
				border: 1px solid rgb(238, 238, 238);
				background-color: white;
				-webkit-box-shadow: rgba(2, 2, 2, 0980392) 0px 0px 5px inset;
				box-shadow: rgba(0, 0, 0, 0.0980392) 0px 0px 5px inset;
				border-top-left-radius: 4px;
				border-top-right-radius: 4px;
				border-bottom-right-radius: 4px;
				border-bottom-left-radius: 4px;
				box-sizing: border-box;
				text-shadow: none !important;
				margin-top: 5px;
			}

			#bpaa-form-wrapper form input[type="button"], #bpaa-form-wrapper form input[type="submit"]  {
				font: normal 12px sans-serif;
				padding: 5px;
				color: #777;
				text-shadow: none;
			}

			.bpaa-warning {
				border: 1px solid rgb(233, 130, 130) !important;
			}

		</style>
		<!-- form -->
		<div id="bpaa-form-wrapper" hidden >
			<form id="bpaa-form" action="" method="post">
				<?php wp_nonce_field('bpaa_submit_form','bpaa_update_activity'); ?>
				<textarea name="bpaa_textarea" id="bpaa-textarea" value="" class="bpaa-input" placeholder="Post something to activity..."></textarea>
				<input type="text" name="bpaa_video_link" id="bpaa-video-link" class="bpaa-input" placeholder="http://myvideo.com" />
				<div id="bpaa-buttons-wrapper">
					<input type="button" class="button" id="bpaa-submit" value="submit" name="bpaa_submit">
					<input type="button" class="button" id="bpaa-cancel" value="cancel" />
				</div>
			</form>
		</div>
		<script>
			jQuery(document).ready(function($) {
				//append the form into our admin button
				$('#bpaa-form-wrapper').appendTo('#wp-admin-bar-post-update');
				
				$('li#wp-admin-bar-post-update a').addClass('bpaa-trigger');
				//remove warning when in input fields
				$('.bpaa-input').focus(function() {
					$('#bpaa-textarea').removeClass('bpaa-warning');
					$('#bpaa-video-link').removeClass('bpaa-warning');
				});
				//when submit check if at least one filed as value and if so submit the form
				//otherwise add warning
				$("#bpaa-submit").click(function() {
					//if(!$.trim($('#bpaa-textarea').value).length) { // zero-length string AFTER a trim
					if ( $('#bpaa-textarea').val() || $('#bpaa-video-link').val() ) {
						$("#bpaa-form").submit();
					} else {
						$('#bpaa-textarea').addClass('bpaa-warning');
						$('#bpaa-video-link').addClass('bpaa-warning');
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
?>