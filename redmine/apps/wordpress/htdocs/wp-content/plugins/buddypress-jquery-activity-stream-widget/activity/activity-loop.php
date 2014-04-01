<?php /* Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_activity_loop() */ ?>

<?php $style = ''; ?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<li class="widget widget_BPjQueryActivityStream">
	<ul id="news" class="dunhakdis-activity-list">
	<?php while ( bp_activities() ) : bp_the_activity(); ?>
		<?php include 'entry.php'; ?>
	<?php endwhile; ?>
	</ul>
	</li>
<?php endif; ?>
