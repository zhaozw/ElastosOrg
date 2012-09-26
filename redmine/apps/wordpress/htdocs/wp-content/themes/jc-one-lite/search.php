<?php get_header(); ?>
<!-- tpl: search -->
	<?php if (have_posts()) { ?>
        <header class="page-header">
		    <h1 class="page-title"><?php printf(__('Search Results for: %s', 'jc-one-lite'), '<span>' . get_search_query() . '</span>'); ?></h1>
		</header>

		<?php while (have_posts()) {
            the_post(); ?>
            <?php get_template_part('loop'); ?>
		<?php } ?>
		<?php jc_one_content_nav('nav-below'); ?>

	<?php } else { 
	    jc_one_no_results(__('Apologies, but no results were found for the requested search. Perhaps searching will help find a related post.', 'jc-one-lite'));
	} ?>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
