<?php
get_header(); ?>
<!-- tpl: single -->
    <?php while (have_posts()) {
        the_post(); ?>
        <?php get_template_part('content', 'image'); ?>
        <?php jc_one_content_nav('nav-below', 'image'); ?>
        <?php comments_template('', true); ?>
    <?php } // end of the loop. ?>
<?php get_footer(); ?>
