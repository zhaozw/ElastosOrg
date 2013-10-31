<?php get_header(); ?>
<!-- tpl: archive -->
    <?php if (have_posts()) { ?>
        <header class="page-header">
            <h1 class="page-title">
                <?php if (is_day()) { ?>
                    <?php printf(__('Daily Archives: %s', 'jc-one-lite'), '<span>' . get_the_date() . '</span>'); ?>
                <?php } elseif (is_month()) { ?>
                    <?php printf(__('Monthly Archives: %s', 'jc-one-lite'), '<span>' . get_the_date('F Y') . '</span>'); ?>
                <?php } elseif (is_year()) { ?>
                    <?php printf(__('Yearly Archives: %s', 'jc-one-lite'), '<span>' . get_the_date('Y') . '</span>'); ?>
                <?php /* If this is an author archive */ } elseif (is_author()) {
                    the_post();
                    printf(__('All posts by: %s', 'jc-one-lite'), '<span>' . get_the_author() . '</span>');
                    rewind_posts();
                    ?>
                <?php } else { ?>
                    <?php _e('Blog Archives', 'jc-one-lite'); ?>
                <?php } ?>
            </h1>
        </header>
        <?php while (have_posts()) {
            the_post(); ?>
            <?php get_template_part('loop'); ?>
        <?php } ?>
        <div class="clear"></div>
        <?php jc_one_content_nav('nav-below'); ?>
    <?php } else {
        jc_one_no_results(__('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'jc-one-lite'));
    } ?>
<?php get_footer(); ?>
