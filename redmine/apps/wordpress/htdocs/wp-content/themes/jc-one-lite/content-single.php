<!-- tpl: content single -->
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php if ('post' == get_post_type()) { ?>
            <div class="entry-meta"><?php
                $date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>',
                    esc_url( get_permalink() ),
                    esc_attr( get_the_time() ),
                    esc_attr( get_the_date( 'c' ) ),
                    esc_html( get_the_date() )
                );

                $author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
                    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                    esc_attr( sprintf( __( 'View all posts by %s', 'jc-one-lite' ), get_the_author() ) ),
                    get_the_author()
                );
                 ?> &mdash; <?php printf(__('by %s on %s', 'jc-one-lite'), $author, $date);
                if ( comments_open() ) {
                    comments_popup_link( ' &mdash; <span class="leave-reply">' . __( 'Leave a reply', 'jc-one-lite' ) . '</span>', ' &mdash; '.__( '1 Reply', 'jc-one-lite' ), ' &mdash; '. __( '% Replies', 'jc-one-lite' ) );
                }
                ?>
            </div><!-- .entry-meta -->
            <?php } ?>
        </header><!-- .entry-header -->

        <?php if (has_post_thumbnail()) {
            the_post_thumbnail('large-feature');
        } ?>

        <div class="entry-content">
            <?php the_content(); ?>
            <div class="clear"></div>
            <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'jc-one-lite') . '</span>', 'after' => '</div>')); ?>
        </div><!-- .entry-content -->

        <?php if ('post' == get_post_type()) {
        $categories_list = get_the_category_list( __( ', ', 'jc-one-lite' ) );
        $tags_list = get_the_tag_list('', __(', ', 'jc-one-lite'));
        if ($categories_list || $tags_list){
        ?>
        <footer class="entry-meta">
            <?php
            if ( $categories_list ) { ?>
            <div class="entry-categories">
            <?php printf(__('<span>Posted in</span> %1$s', 'jc-one-lite'), $categories_list); ?>
            </div><!-- .entry-categories -->
            <?php
            }

            if ($tags_list){
            ?>
            <div class="entry-tags">
                <?php printf(__('<span>Tagged</span> %1$s', 'jc-one-lite'), $tags_list); ?>
            </div><!-- .entry-tags -->
            <?php } ?>
        </footer>
        <?php }} ?>

        <div class="clear"></div>
    </article><!-- #post-<?php the_ID(); ?> -->
