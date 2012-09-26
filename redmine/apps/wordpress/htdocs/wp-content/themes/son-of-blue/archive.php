<?php get_header(); ?>
<div id="columnwrapper">
<?php get_sidebar(); ?>

<div id="c2">
<div class="content">

<div class="post">
<?php if (have_posts()) :?>
<?php $postCount=0; ?>
<?php while (have_posts()) : the_post(); $loopcounter++;?>
<?php $postCount++;?>

<?php if ($postCount==1):?>

<div class="calendar"><?php the_time('M'); ?>
<div class="calday"><?php the_time('j'); ?></div>
</div><!-- end of the calendar div --><h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title(); ?>"><?php the_title(); ?></a></h1>
<div class="author">written by <?php the_author() ?></div><!-- end author div -->
<div class="postdate">- <?php the_time('F jS, Y') ?></div><!-- end postdate div -->

<?php else : ?>

<div class="calendar"><?php the_time('M'); ?>
<div class="calday"><?php the_time('j'); ?></div>
</div><!-- end of the calendar div --><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
<div class="author">written by <?php the_author() ?></div><!-- end author div -->
<div class="postdate">- <?php the_time('F jS, Y') ?></div><!-- end postdate div -->

<?php endif; ?>

<div class="entry"><?php the_excerpt(); _e('<p><a href="'.get_permalink().'">Read More About - '); the_title(); _e('</a> &#187;</p>');  ?>
</div><!-- end entry div -->

<div class="postmetadata">
<div class="ncomments">Comments: <?php comments_popup_link('No comments yet. &#187;', '1 Comment, Join in &#187;', '% Comments, Join in &#187;')?></div><!-- end ncomments div -->
<div class="tags"><?php the_tags(); ?></div><!-- end tags div -->
<div class="category">Category: <?php the_category(',') ?></div><!-- end category div -->
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

</div><!-- end of c2 div -->
</div><!-- end of column wrapper div -->

<?php include(TEMPLATEPATH . '/sidebar2.php'); ?>
<?php get_footer(); ?>
