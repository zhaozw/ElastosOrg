<?php get_header(); ?>
    <!-- tpl: page -->
    <?php the_post(); ?>
    <?php get_template_part('content', 'page'); ?>
    <?php jc_one_content_nav('nav-below', 'single'); ?>
    <?php comments_template('', true); ?>
<?php get_footer(); ?>
