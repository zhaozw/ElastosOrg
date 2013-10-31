<!-- tpl: single image -->
    <article id="post-<?php the_ID(); ?>" <?php post_class('image-attachment'); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <div class="entry-meta">
                <?php
                    $metadata = wp_get_attachment_metadata();
                    printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><time class="entry-date" datetime="%1$s" pubdate>%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>.', 'jc-one-lite' ),
                        esc_attr( get_the_date( 'c' ) ),
                        esc_html( get_the_date() ),
                        esc_url( wp_get_attachment_url() ),
                        $metadata['width'],
                        $metadata['height'],
                        esc_url( get_permalink( $post->post_parent ) ),
                        esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
                        get_the_title( $post->post_parent )
                    );
                ?>
            </div>
            <?php jc_one_content_nav('nav-above', 'image'); ?>
        </header>

        <div class="entry-attachment">
            <div class="attachment">
                <a href="<?php echo wp_get_attachment_url(); ?>"><?php
                $attachment_size = apply_filters( 'jc_attachment_size', array( 860, 860 ) );
                echo wp_get_attachment_image( $post->ID, $attachment_size );
                ?></a>

                <?php if ( ! empty( $post->post_excerpt ) ) : ?>
                <div class="entry-caption">
                    <?php the_excerpt(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="entry-content clearfix">
            <?php the_content(); ?>
            <div class="clear"></div>
        </div><!-- .entry-content -->

        <div class="clear"></div>
    </article><!-- #post-<?php the_ID(); ?> -->
