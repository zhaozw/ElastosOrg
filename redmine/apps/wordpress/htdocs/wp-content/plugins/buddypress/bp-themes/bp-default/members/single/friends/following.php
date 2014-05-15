<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( 'include=' . bp_get_following_ids() ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="item-list" role="main">

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li style="float: left;border-color:#e6e6e6;padding: 10px;border-width: 2px;border-style: solid;border-radius: 2px;box-shadow: 0px 1px 2px 0px #ededed;width: 268px;height: 100px;margin: 40px 20px 0 0;cursor: pointer;">
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div>

			<div class="item">
				<div class="item-title">
					<a href="<?php
						$usr = new WP_User(bp_get_member_user_id());
						//$url = get_blogaddress_by_id($usr->primary_blog);
						switch_to_blog($usr->primary_blog);
						$url = get_bloginfo( 'wpurl' );
						$blog_name = get_bloginfo('name');
						restore_current_blog();

						echo $url . '" title="BLOG: ' . $blog_name; ?>"><?php bp_member_name(); ?><img src="/elorg_common/img/blog.jpg" style="width:16px"></a>
				</div>

				<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>

				<?php do_action( 'bp_directory_members_item' ); ?>

				<?php
				 /***
				  * If you want to show specific profile fields here you can,
				  * but it'll add an extra query for each member in the loop
				  * (only one regardless of the number of fields you show):
				  *
				  * bp_member_profile_data( 'field=the field name' );
				  */
				?>
			</div>

			<div class="action" style="position:relative;bottom:5px;">

				<?php do_action( 'bp_directory_members_actions' ); ?>

			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>
	<script type="text/javascript">
	var $=jQuery;
	$(document).ready(function(){
	$("#members-list").find('li').mouseover(
			function (){
				$(this).css("border-width","2px");
				$(this).css("border-color","#fda316");
				});
	$("#members-list").find('li').mouseout(
			function(){
				$(this).css("border-width","2px");
				$(this).css("border-color","#e6e6e6");
				});
	});
	</script>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination" style="float:left;width:100%;">

		<div class="pag-count" id="member-dir-count-bottom" style="min-width:680px">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no following was found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
