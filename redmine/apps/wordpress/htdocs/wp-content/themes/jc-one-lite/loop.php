    <!-- tpl: loop -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<header class="entry-header">
			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'jc-one-lite'), the_title_attribute('echo=0')); ?>" rel="bookmark">
					<?php $title = get_the_title(); 
					if (!$title) $title = __('More ...', 'jc-one-lite');
					echo $title;
					?></a></h3>
			<?php if ('post' == get_post_type()) { ?>
			<div class="entry-meta">
				&mdash; <?php printf(__('by %s on', 'jc-one-lite'), get_the_author()); ?> <?php the_date(); ?>
			</div><!-- .entry-meta -->
			<?php } ?>
		</header><!-- .entry-header -->
        
        <?php if (has_post_thumbnail()) {
			the_post_thumbnail();
		} ?>
		
		<div class="entry-content">
		    <div class="entry-summary">
			    <?php the_excerpt(); ?>
		    </div><!-- .entry-summary -->
		    
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
