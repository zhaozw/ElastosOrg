<?php if ( bp_group_has_members( 'exclude_admins_mods=0' ) ) : ?>

	<?php do_action( 'bp_before_group_members_content' ); ?>

	<div class="item-list-tabs" id="subnav" role="navigation">
		<ul>

			<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

			<?php
				$search_value = !empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : 'Filter users';
			?>
			<form action="" method="get" id="search-members-form">
			<input type="submit" id="members_search_submit" name="members_search_submit" value="<?php _e( 'Filter', 'buddypress' ) ?>" />
			<label><input type="text" name="s" id="members_search" placeholder="<?php echo esc_attr( $search_value ) ?>" /></label>
			</form>
		</ul>
	</div>

	<div id="pag-top" class="pagination no-ajax">

		<div class="pag-count" id="member-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_group_members_list' ); ?>

	<ul id="member-list" class="item-list" role="main">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<li>
				<a href="<?php bp_group_member_domain(); ?>">

					<?php bp_group_member_avatar_thumb(); ?>

				</a>

				<h5><a href="<?php
					$usr = new WP_User(bp_get_group_member_id());
					//$url = get_blogaddress_by_id($usr->primary_blog);
					switch_to_blog($usr->primary_blog);
					$url = get_bloginfo( 'wpurl' );
					$blog_name = get_bloginfo('name');
					restore_current_blog();
					echo $url . '" title="BLOG: ' . $blog_name; ?>"><?php bp_member_name(); ?><img src="/elorg_common/img/blog.jpg" style="width:16px"></a></h5>
				<span class="activity"><?php bp_group_member_joined_since(); ?></span>

				<?php do_action( 'bp_group_members_list_item' ); ?>

				<?php if ( bp_is_active( 'friends' ) ) : ?>

					<div class="action">

						<?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

						<?php do_action( 'bp_group_members_list_item_action' ); ?>

					</div>

				<?php endif; ?>
			</li>

		<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination no-ajax">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'This group has no members.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>
