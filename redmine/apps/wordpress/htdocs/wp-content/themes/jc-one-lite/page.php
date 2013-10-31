<?php get_header(); ?>
    <!-- tpl: page -->
    <?php the_post(); ?>
    <?php get_template_part('content', 'page'); ?>
    <?php comments_template('', true); ?>
<?php get_footer(); ?>
