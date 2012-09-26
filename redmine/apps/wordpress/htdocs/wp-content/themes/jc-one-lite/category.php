<?php get_header(); ?>
<!-- tpl: category -->
	<?php if (have_posts()) { ?>
        <header class="page-header">
			<h1 class="page-title"><?php
				printf(__('Category Archives: %s', 'jc-one-lite'), '<span>' . single_cat_title('', false) . '</span>');
			?></h1>
			<?php
				$category_description = category_description();
				if (! empty($category_description))
					echo apply_filters('category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>');
			?>
		</header>
		
		<?php while (have_posts()) {
            the_post(); ?>
            <?php get_template_part('loop'); ?>
		<?php } ?>
		<div class="clear"></div>
        <?php jc_one_content_nav('nav-below'); ?>
	<?php } else { 
	    jc_one_no_results(__('Apologies, but no results were found for the requested category. Perhaps searching will help find a related post.', 'jc-one-lite'));
	} ?>
<?php get_footer(); ?>
