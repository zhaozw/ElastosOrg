<?php get_header(); ?>
<!-- tpl: 404 -->
<article id="post-0" class="post no-results not-found">
    <header class="entry-header">
        <h1 class="entry-title"><?php _e('404: Page Not Found', 'jc-one-lite'); ?></h1>
    </header><!-- .entry-header -->
    <div class="entry-content">
        <p><?php _e('We are terribly sorry, but the URL you typed no longer exists. It might have been moved or deleted, or perhaps you mistyped it. We suggest searching the site:', 'jc-one-lite'); ?></p>
        <?php get_search_form(); ?>
    </div><!-- .entry-content -->
</article><!-- #post-0 -->
<?php get_footer(); ?>
