<?php
/*
Template Name: Sitemap

*/
?>
<?php
get_header(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <h1 class="entry-title"><?php the_title(); ?></h1>
    
    <div class="entry-content">
        <?php the_content(); ?>
    </div><!-- .entry-content -->

    <?php 
    // use the custom meta field "show" for selecting what to show.
    // by default : all
    // possible: pages,categories,posts-by-category,posts,archives,search
    $meta_fields = get_post_meta($post->ID, "show", true);
    if (empty($meta_fields))
        $meta_fields = "pages,categories,archives,search";
    $fields = split(',', $meta_fields);
    foreach ($fields as $field) {
        switch ($field){
            case "pages":
                ?>
                <div class="sitemap-pages">    
                    <h2><?php _e('The Pages:', 'jc-one-lite') ?></h2>
                    <ul>
                        <?php wp_list_pages('title_li='); ?>
                    </ul>
                </div>
                <hr class="space" />
                <?php 
                break;
            case "categories":
                ?>
                <div class="sitemap-categories">    
                    <h2><?php _e('Categories:', 'jc-one-lite') ?></h2>
                    <ul>
                         <?php wp_list_categories(); ?>
                    </ul>
                </div>
                <hr class="space" />
                <?php 
                break;
            case "post-by-category":
                ?>
                <div class="sitemap-articles">    
                    <h2><?php _e('Posts by Category', 'jc-one-lite') ?></h2>
                    <?php $saved = $wp_query;
                    $cats = get_categories();
                    foreach ($cats as $cat) {
                    query_posts('showposts=999&cat='.$cat->cat_ID);
                    ?>
                    <h3><?php echo $cat->cat_name; ?></h3>
                    <ul>
                    <?php while (have_posts()) : the_post(); ?>
                    <li style="font-weight:normal !important;"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php _e('Commentaires', 'jc-one-lite') ?> (<?php echo $post->comment_count ?>)</li>
                    <?php endwhile;  ?>
                    </ul>
                    <?php } $wp_query = $saved; ?>
                </div>
                <hr class="space" />
                <?php 
                break;
            case "posts":
                ?>
                <div class="sitemap-articles">    
                    <h2><?php _e('All Posts', 'jc-one-lite') ?></h2>
                    <?php wp_get_archives('type=postbypost'); ?>
                </div>
                <hr class="space" />
                <?php 
                break;
            case "archives":
                ?>
                <div class="sitemap-articles">    
                    <h2><?php _e('All Posts By Month', 'jc-one-lite') ?></h2>
                    <?php wp_get_archives(''); ?>
                </div>
                <hr class="space" />
                <?php 
                break;
            case "search":
                ?>
                <div class="sitemap-search">    
                    <h2><?php _e('Try a search:', 'jc-one-lite') ?></h2>
                    <?php get_search_form(); ?>
                </div>
                <hr class="space" />
        <?php 
        } // switch
    } // foreach
    ?>
    
    <footer class="entry-meta">
        <?php edit_post_link(__('Edit', 'jc-one-lite'), '<span class="edit-link">', '</span>'); ?>
    </footer><!-- .entry-meta -->           
    
</div> <!-- //#post-<?php the_ID(); ?> -->

<?php get_footer(); ?>
