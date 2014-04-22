<?php

/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_activity_entry' ); ?>

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">
	<div class="activity-avatar">
		<a href="<?php bp_activity_user_link(); ?>">

			<?php bp_activity_avatar(); ?>

		</a>
	</div>

	<div class="activity-content">

		<div class="activity-header">

			<?php bp_activity_action(); ?>

		</div>

		<?php if ( 'activity_comment' == bp_get_activity_type() ) : ?>

			<div class="activity-inreplyto">
				<strong><?php _e( 'In reply to: ', 'buddypress' ); ?></strong><?php bp_activity_parent_content(); ?> <a href="<?php bp_activity_thread_permalink(); ?>" class="view" title="<?php _e( 'View Thread / Permalink', 'buddypress' ); ?>"><?php _e( 'View', 'buddypress' ); ?></a>
			</div>

		<?php endif; ?>

		<?php if ( bp_activity_has_content() ) : ?>

			<div class="activity-inner">

				<?php bp_activity_content_body(); ?>

			</div>

		<?php endif; ?>

		<?php do_action( 'bp_activity_entry_content' ); ?>

		<?php if ( is_user_logged_in() ) : ?>

			<div class="activity-meta">

				<?php if ( bp_activity_can_comment() ) : ?>

					<a href="<?php bp_get_activity_comment_link(); ?>" class="button acomment-reply bp-primary-action" id="acomment-comment-<?php bp_activity_id(); ?>"><i class="fa fa-reply" style="color:#14A0CD;"></i> <?php printf( __( 'Reply <span>%s</span>', 'buddypress' ), bp_activity_get_comment_count() ); ?></a>

				<?php endif; ?>

				<?php if ( bp_activity_can_favorite() ) : ?>

					<?php if ( !bp_get_activity_is_favorite() ) : ?>

						<a href="<?php bp_activity_favorite_link(); ?>" class="button fav bp-secondary-action" title="<?php esc_attr_e( 'Mark as Favorite', 'buddypress' ); ?>"><i class="fa fa-link" style="color:#14A0CD;"></i> <?php _e( 'Favorite', 'buddypress' ); ?></a>

					<?php else : ?>

						<a href="<?php bp_activity_unfavorite_link(); ?>" class="button unfav bp-secondary-action" title="<?php esc_attr_e( 'Remove Favorite', 'buddypress' ); ?>"><i class="fa fa-chain-broken" style="color:#14A0CD;"></i> <?php _e( 'Remove Favorite', 'buddypress' ); ?></a>

					<?php endif; ?>

				<?php endif; ?>
<?php
	global $activities_template;

    $msg = '//<a href=\\"' . bp_get_activity_user_link() . '\\">@' . $activities_template->activity->user_login . '</a> ';
    //$msg .= '<a href=\\\'' . bp_get_activity_user_link() . '?ids=' . bp_get_activity_id() . '\\\'>' . addslashes(mb_substr(bp_get_activity_content_body(),0,30)) . '</a>';
    $msg .= '<a href=\\"' . bp_get_activity_user_link() . '?ids=' . bp_get_activity_id() . '\\">' . 'addslashes' . '</a>';

   $user_link = bp_get_activity_user_link();
   $user_login = $activities_template->activity->user_login;
   $activity_url = bp_get_activity_user_link() . '?ids=' . bp_get_activity_id();
   $activity_content = addslashes(mb_substr(str_replace(PHP_EOL,'',strip_tags(bp_get_activity_content_body())),0,50)) . __('&hellip;', 'buddypress');
   $activity_content = str_replace('@', ' ', $activity_content);

   $msg = $user_link . '\',\'' . $user_login . '\',\'' . $activity_url . '\',\'' . $activity_content . '\',\'' . bp_get_activity_id();
?>
				<a class="button forward-trigger bp-secondary-action" id="acomment-forward-<?php bp_activity_id(); ?>"  onclick="forward_it('<?php echo $msg; ?>');"><i class="fa fa-share" style="color:#14A0CD;"></i> <?php printf( __( 'Forward <span>%s</span>', 'buddypress' ), bp_activity_get_forward_count() ); ?></a>

				<?php if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>

				<?php do_action( 'bp_activity_entry_meta' ); ?>

			</div>

		<?php endif; ?>

	</div>

	<?php do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( is_user_logged_in() && bp_activity_can_comment() ) || bp_activity_get_comment_count() ) : ?>

		<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() ) : ?>

				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
					<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="<?php _e( 'Post', 'buddypress' ); ?>" /> &nbsp; <?php _e( 'or press esc to cancel.', 'buddypress' ); ?>
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
					</div>

					<?php do_action( 'bp_activity_entry_comments' ); ?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_activity_entry_comments' ); ?>

</li>
<script>
function forward_it(user_link, user_login, activity_url, activity_content, activity_id) {
	jQuery('#bpaa-form-wrapper').stop().fadeIn('fast');
	jQuery("#bpaa_reply").html('<a href="' + user_link + '">@' + user_login + '</a> ' + '<a href="' + activity_url + '">' + activity_content + '</a>');
	jQuery("#bpaa_reply").css({'background-color':'green','height':'56px','visibility':'visible'});
	jQuery("#o_id").val(activity_id);
}
</script>


<?php do_action( 'bp_after_activity_entry' ); ?>
