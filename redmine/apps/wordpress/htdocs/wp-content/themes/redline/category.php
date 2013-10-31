<?php
/**
 * Template: Category.php
 *
 * @package RedLine
 * @subpackage Template
 */

get_header();
?>
			<!--BEGIN #primary-->
			<div id="primary" class="hfeed" role="main">
			<?php if ( have_posts() ) : ?>
				<h2 class="page-title archive-title"><?php _e( 'Category Archives:', 'redline' );?> <span id="category-title"><?php single_cat_title(); ?></span></h2>
				<?php while ( have_posts() ) : the_post(); ?>
				
				<!--BEGIN .hentry-->
				<div id="post-<?php the_ID(); ?>" class="<?php semantic_entries(); ?>">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'redline' );?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

					<!--BEGIN .entry-meta .entry-header-->
					<div class="entry-meta entry-header">
						<span class="author vcard"><?php _e( 'Written by', 'redline' );?> <?php printf( '<a class="url fn" href="' . get_author_posts_url( $authordata->ID, $authordata->user_nicename ) . '" title="' . sprintf( __( 'View all posts by %s', 'redline' ), $authordata->display_name ) . '">' . get_the_author() . '</a>' ) ?></span>
						<span class="published"><?php _e( 'on', 'redline' );?> <abbr class="published-time" title="<?php the_time( get_option( 'date_format' ) .' - '. get_option( 'time_format' ) ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></abbr></span>
						<span class="meta-sep">&mdash;</span>
						<span class="comment-count"><a href="<?php comments_link(); ?>"><?php comments_number( __( 'Leave a Comment', 'redline' ), __( '1 Comment', 'redline' ), __( '% Comments', 'redline' ) ); ?></a></span>
						<?php edit_post_link( __( 'edit', 'redline' ), '<span class="edit-post">[', ']</span>' ); ?>
					<!--END .entry-meta .entry-header-->
					</div>

					<!--BEGIN .entry-summary .article-->
					<div class="entry-summary article">
						<?php the_excerpt(); ?>
					<!--END .entry-summary .article-->
					</div>

					<!--BEGIN .entry-meta .entry-footer-->
					<div class="entry-meta entry-footer">
						<?php if ( framework_get_terms( 'cats' ) ) { ?>
						<span class="entry-categories"><?php _e( 'Also posted in', 'redline' );?> <?php echo framework_get_terms( 'cats' ); ?></span>
						<?php } ?>
						<?php if ( framework_get_terms( 'tags' ) ) { ?>
						<?php if ( framework_get_terms( 'tags' ) and framework_get_terms( 'cats' ) ) { ?>
						<span class="meta-sep">|</span>
						<?php } ?>
						<span class="entry-tags"><?php _e( 'Tagged', 'redline' );?> <?php echo framework_get_terms( 'tags' ); ?></span>
						<?php } ?>
					<!--END .entry-meta .entry-footer-->
					</div>
				<!--END .hentry-->
				</div>

				<?php endwhile; ?>
				<?php get_template_part( 'navigation' ) ?>
				<?php else : ?>

				<!--BEGIN #post-0-->
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Not Found', 'redline' );?></h2>

					<!--BEGIN .entry-content-->
					<div class="entry-content">
						<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'redline' );?></p>
						<?php get_search_form(); ?>
					<!--END .entry-content-->
					</div>
				<!--END #post-0-->
				</div>

			<?php endif; ?>
			<!--END #primary .hfeed-->
			</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>