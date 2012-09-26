<!-- tpl: content page -->

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php the_content(); ?>
        <div class="clear"></div>
        <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'jc-one-lite') . '</span>', 'after' => '</div>')); ?>
    </div><!-- .entry-content -->
    
    
    <?php edit_post_link(__('Edit', 'jc-one-lite'), '<span class="edit-link">', '</span>'); ?>
    
</article><!-- #post-<?php the_ID(); ?> -->

