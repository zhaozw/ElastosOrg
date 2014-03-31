<?php /* Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_activity_loop() */ ?>
<?php do_action( 'bp_before_activity_loop' ) ?>
<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>
	<?php if ( empty( $_POST['page'] ) ) : ?>
	<li class="widget widget_BPjQueryActivityStream">
	<ul id="news" class="dunhakdis-activity-list">
	<?php endif; ?>
	<?php while ( bp_activities() ) : bp_the_activity(); ?>
		<?php include 'entry.php'; ?>
	<?php endwhile; ?>
	<?php if ( empty( $_POST['page'] ) ) : ?>
		</ul>
	<?php endif; ?>
	</li>
<?php endif; ?>
<?php do_action( 'bp_after_activity_loop' ) ?>
<form action="" name="activity-loop-form" id="activity-loop-form" method="post">
	<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ) ?>
</form>