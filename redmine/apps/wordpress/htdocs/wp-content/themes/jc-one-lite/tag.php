<?php get_header(); ?>
<!-- tpl: tag -->
    <?php if (have_posts()) { ?>
        <header class="page-header">
            <h1 class="page-title"><?php
                printf(__('Tag Archives: %s', 'jc-one-lite'), '<span>' . single_tag_title('', false) . '</span>');
            ?></h1>

            <?php
                $tag_description = tag_description();
                if (! empty($tag_description))
                    echo apply_filters('tag_archive_meta', '<div class="tag-archive-meta">' . $tag_description . '</div>');
            ?>
        </header>

        <?php while (have_posts()) {
            the_post(); ?>
            <?php get_template_part('loop'); ?>
        <?php } ?>
        <?php jc_one_content_nav('nav-below'); ?>

    <?php } else {
        jc_one_no_results(__('Apologies, but no results were found for the requested tag. Perhaps searching will help find a related post.', 'jc-one-lite'));
    } ?>

<?php get_footer(); ?>
