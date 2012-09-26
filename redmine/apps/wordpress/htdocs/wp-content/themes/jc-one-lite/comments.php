<div id="comments">
	<?php if (post_password_required()) { ?>
		<p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'jc-one-lite'); ?></p>
	</div><!-- #comments -->
	<?php
		return;
    }
	?>

	<?php if (have_comments()) { ?>
		<h2 id="comments-title">
			<?php
				printf(_n('1 comment', '%1$s comments', get_comments_number(), 'jc-one-lite'),
					number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
					
			?>
			<a class="comment-leave" href="#respond" title="<?php _e('&mdash; post a comment', 'jc-one-lite'); ?>">
			    <?php _e('&mdash; post a comment', 'jc-one-lite'); ?></a>
		</h2>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) {  ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php _e('Comment navigation', 'jc-one-lite'); ?></h1>
			<div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'jc-one-lite')); ?></div>
			<div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'jc-one-lite')); ?></div>
		</nav>
		<?php } ?>

		<div class="commentlist">
			<?php wp_list_comments(array('callback' => 'jc_one_comment', 'style' => 'div'));	?>
		</div>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) { ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php _e('Comment navigation', 'jc-one-lite'); ?></h1>
			<div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'jc-one-lite')); ?></div>
			<div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'jc-one-lite')); ?></div>
		</nav>
		<?php } ?>

	<?php
        } elseif (! comments_open() && ! is_page() && post_type_supports(get_post_type(), 'comments')) {	?>
        <!-- comments are closed -->
	<?php } ?>

	<?php comment_form(); ?>

</div><!-- #comments -->
