<!-- tpl: content single -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php if ('post' == get_post_type()) : ?>
			<div class="entry-meta">
				&mdash; <?php printf(__('by %s on', 'jc-one-lite'), get_the_author()); ?> <?php the_date(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
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

	</article><!-- #post-<?php the_ID(); ?> -->
