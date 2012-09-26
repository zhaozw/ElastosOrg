<?php get_header(); ?>
<div id="columnwrapper">
<?php get_sidebar(); ?>

<div id="c2">
<div class="content">

<div class="post">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="calendar"><?php the_time('M'); ?>
<div class="calday"><?php the_time('j'); ?></div>
</div><!-- end of the calendar div -->
<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title(); ?>"><?php the_title(); ?></a></h1>
<div class="author">written by <?php the_author() ?></div><!-- end author div -->
<div class="postdate">- <?php the_time('F jS, Y') ?></div><!-- end postdate div -->

<div class="entry"><?php the_content('Read the rest of this entry &raquo;'); ?>
</div><!-- end entry div -->
<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

<div class="postmetadata">
<div class="editit"><?php edit_post_link('Edit', ' &#8226; ', ''); ?></div><!-- end editit div -->
</div><!-- end postmetadata div -->

<?php endwhile; ?>

<?php else : ?>

<h1>Not Found Here</h1>
<p>Sorry, but you are looking for something that isn't here. Try again by using the Search box in the left column.</p>

	<ul>
        <li>You could visit the <a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a> home page.</li>		
        <li>You can search the site using the search box to the left.</li>	
	</ul>

Or you check out the recent articles:
	<ul>
	<?php
	query_posts('posts_per_page=10');
	if (have_posts()) : while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" title="Permalink for : <?php the_title(); ?>"><?php the_title(); ?></a></li>
	<?php endwhile; endif; ?>
	</ul>
	
<?php endif; ?>

</div><!-- end post div -->

<div class="navigation">
	<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
	<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
</div><!-- end navigation div -->

</div><!-- end c2 div -->
</div><!-- end of category div -->

<?php include(TEMPLATEPATH . '/sidebar2.php'); ?>
<?php get_footer(); ?>
