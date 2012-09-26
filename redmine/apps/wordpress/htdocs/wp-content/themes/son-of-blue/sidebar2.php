<div id="c3">
  <div class="content">
  
<h3>Join In</h3>
<ul>
    <?php wp_register(); ?>
	<li><?php wp_loginout(); ?></li>
	<?php wp_meta(); ?>
</ul>

   <h3>Tag Cloud</h3>
<ul>
	<li class="tagcloud"><?php wp_tag_cloud('smallest=8&largest=18'); ?></li>
</ul>

   <h3>Great Links</h3>
    <ul>
<?php wp_list_bookmarks('categorize=0&title_li=0&title_after=&title_before='); ?>
	</ul>

<h3>Archives</h3>
<ul>
<?php wp_get_archives('type=monthly'); ?>
</ul>
	  
<!-- This is the start of the widgets function -->

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : ?>
<?php endif; ?>

<!-- This is the end of the widgets function -->

    </div><!-- end of the content div -->
  </div><!-- end of c3 div -->
<div id="columns-bottom">&nbsp;</div>
</div><!-- end of column wrapper div -->
	