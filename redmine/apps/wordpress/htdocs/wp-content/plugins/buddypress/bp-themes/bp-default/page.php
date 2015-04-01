<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ); ?>

		<div class="page" id="blog-page" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); 
				$the_ID = $id;
				if($the_ID == 2) {
			?>
				<div id="post-2" <?php post_class(); ?> style="display:none">
					<h2 class="pagetitle"><?php the_title(); ?></h2>
					<div class="entry">
						<?php the_content();?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>
					</div>
				</div>
				<div id="post-544" style="display:none">
					<h2 class="pagetitle"><?php $elastos_id = 544; echo get_page( $elastos_id )->post_title; ?></h2>
					<div class="entry">
						<?php echo get_page( $elastos_id )->post_content;?>
					</div>
				</div>
				<div id="elastospage"></div>
				<script src="http://elastos.org/wp-admin/js/jquery.js"></script>
				<script src="http://elastos.org/wp-admin/js/changePage.js"></script>

			<?php } else { ?>

				<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry">

						<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						<?php edit_post_link( __( 'Edit this page.', 'buddypress' ), '<p class="edit-link">', '</p>'); ?>

					</div>

				</div>

				<?php comments_template(); ?>
				<?php } ?>
			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
