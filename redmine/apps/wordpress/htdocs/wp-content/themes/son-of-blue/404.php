<?php get_header(); ?>
<div id="columnwrapper">
<div id="columns-top">&nbsp;</div>
<?php get_sidebar(); ?>
<div id="c2">
<div class="content">
<h1>Oops!</h1>
	<p>The page you're looking can not be found or no longer exist. Recent changes have been made to the website to better serve customers. You can try the topics below.</p>
	<ul>
        <li>You could visit the <a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a> home page.</li>		
        <li>You can search the site using the search box to the left.</li>	
	</ul>
Or you check out the recent articles:
	<ul>
	<?php
	query_posts('posts_per_page=10');
	if (have_posts()) : while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" title="Permalink for : <?php the_title(); ?>"><?php the_title(); ?></a>
	<?php endwhile; endif; ?>
	</ul>
</div><!-- end of content div -->
</div><!-- end of c2 div -->
<?php include(TEMPLATEPATH . '/sidebar2.php'); ?>
<?php get_footer(); ?>
