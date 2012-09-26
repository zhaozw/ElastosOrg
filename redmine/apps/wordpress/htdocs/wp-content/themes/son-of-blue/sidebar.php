<div id="c1">
<div class="content">
  
<h3>Look for It</h3>
<ul>
<li><?php include (TEMPLATEPATH . '/searchform.php'); ?></li>
</ul>

<h3>Categories</h3>
<ul>
<?php wp_list_cats('show_count=0'); ?>
</ul>

<!-- This is the start of the widgets function -->

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : ?>
<?php endif; ?>

<!-- This is the end of the widgets function -->

</div><!-- end of content div -->
</div><!-- end of c1 div -->