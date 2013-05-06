<!-- sidebar START -->
<div id="sidebar">

<!-- sidebar north START -->
<div id="northsidebar" class="sidebar">

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('north_sidebar') ) : ?>

	<!-- tag cloud -->
	<div id="tag_cloud" class="widget">
		<h3>Tag Cloud</h3>
		<?php wp_tag_cloud('smallest=8&largest=16'); ?>
	</div>

<?php endif; ?>
</div>
<!-- sidebar north END -->

</div>
<!-- sidebar END -->
