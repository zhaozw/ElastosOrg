<?php get_header(); ?>

	<div id="content" class="narrowcolumn" role="main">
		<div class="content_top conleft"></div>

		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle"><?php printf(__('Posts Tagged &#8216;%s&#8217;', 'kubrick'), single_tag_title('', false) ); ?></h2>
 	  <?php } ?>


		<?php while (have_posts()) : the_post(); ?>
		<div <?php post_class(); ?>>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('%s', 'kubrick'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h2>
				<div class="post_intro">
					<span><?php the_author() ?><?php printf(__(' Posted in %s', 'kubrick'), get_the_category_list(', ')); ?>，<?php the_tags( 'Tags: '); ?></span>
					<?php edit_post_link(__('<span>Edit</span>', 'kubrick'), '', ''); ?>
				</div>
				<div class="content_date">
					<div class="datebg">
						<span class="day"><?php the_time('d') ?></span>
						<span><?php the_time('F') ?></span>
						<span><?php the_time('Y') ?></span>
					</div>
				</div>

				<div class="entry">
				<?php the_content(__('More &raquo;', 'kubrick')); ?>
				</div>
			</div>

		<?php endwhile; ?>
<?php par_pagenavi(4); ?>
	<?php else : ?>
		<div class="nofound"></div>
	<?php endif; ?>
<div class="content_foot conleft"></div>
	</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
