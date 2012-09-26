<?php
if ( function_exists('register_sidebar') )
    register_sidebar();

function par_pagenavi($range = 4){
	global $paged, $wp_query;
	if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
	if($max_page > 1){if(!$paged){$paged = 1;}
	echo '<div class="page_navi clear">';
	if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' class='extend'> First </a>";}
	previous_posts_link(' Prev ');
    if($max_page > $range){
		if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    elseif($paged >= ($max_page - ceil(($range/2)))){
		for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
		for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	next_posts_link(' Next ');
    if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' class='extend'> Last </a>";}
    echo '</div>';}
}


function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
?>

<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>" >
	<div id="div-comment-<?php comment_ID(); ?>" class="commentmetadata">
  <ul class="comminfo clear">
  	<li><?php echo get_avatar( $comment, 32 ); ?></li>
  	<li class="atxt"><span><?php comment_author_link() ?></span><p><?php printf(__('%1$s %2$s'), get_comment_date(),  get_comment_time()) ?> </p></li>
  </ul>
  <ul  class="reply">
  	<?php edit_comment_link(__('Edit'),'','') ?>
  	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
  </ul>
  <ul class="commtext clear">
   <?php if ($comment->comment_approved == '0') : ?>
   <?php _e('Your comment is awaiting moderation.') ?>
   <?php endif; ?>
  	<?php comment_text() ?>
  </ul>
</div>

<?php }

?>