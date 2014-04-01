<?php /* This template is used by activity-loop.php and AJAX functions to show each activity */ ?>

<li class="<?php bp_activity_css_class() ?>" id="activity-<?php bp_activity_id() ?>" style="<?php echo $style; ?>">
<?php $style = 'display:none;'; ?>

	<div class="activity-avatar">
		<a href="<?php bp_activity_user_link() ?>">
			<?php bp_activity_avatar( 'type=full&width=40&height=40' ) ?>
		</a>
	</div>

	<div class="activity-content" >

		<div class="activity-header">
			<?php
			$str_bpaa = bp_get_activity_action();
			echo $str_bpaa;
			?>
		</div>
		<?php if ( bp_activity_has_content() ) :
			$len = mb_strlen(str_replace(PHP_EOL,'',strip_tags($str_bpaa)));
			if ( $len < 120 ) {
				echo '<div class="activity-inner">';
				echo mb_substr(str_replace(PHP_EOL,'',strip_tags(bp_get_activity_content_body())),0,120-$len);
				echo '</div>';
			}
		endif; ?>
	</div>

	<?php //do_action( 'bp_before_activity_entry_comments' ) ?>

	<?php if ( bp_activity_can_comment() ) : ?>
		<div class="activity-comments">
			<?php bp_activity_comments() ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'bp_after_activity_entry_comments' ) ?>
</li>

<?php do_action( 'bp_after_activity_entry' ) ?>

