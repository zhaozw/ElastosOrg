<?php get_header(); ?>
<!-- tpl: index -->
    <?php if (have_posts ()) { ?>
        <?php while (have_posts ()) {
            the_post(); ?>
            <?php get_template_part('loop'); ?>
        <?php } ?>
        <?php jc_one_content_nav('nav-below'); ?>
    <?php } else { 
	    jc_one_no_results(__('Apologies, but no results were found. Perhaps searching will help find a related post.', 'jc-one-lite'));
	} ?>
<?php get_footer(); ?>


