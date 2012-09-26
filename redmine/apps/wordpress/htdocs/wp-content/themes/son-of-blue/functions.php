<?php
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'Left Sidebar',
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array('name'=>'Right Sidebar',
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h3>',
'after_title' => '</h3>',
));

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
                 <?php echo get_avatar($comment,$size='75'); ?>

         <?php printf(__('<span class="commentauthor">%s</span>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

<div class="commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_date('F j Y') ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?><p><a href="<?php comment_author_url(); ?>"> Visit My Website</a></p></div>


     <p class="commenttext"><?php comment_text() ?></p>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php
        }

?>