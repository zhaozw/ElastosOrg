<?php
/**
 * Plugin Name: BuddyPress Activity Shortcode
 * Author: Brajesh Singh(BuddyDev)
 * Plugin URI: http://buddydev.com/plugins/bp-activity-shortcode/
 * Author URI: http://buddydev.com/members/sbrajesh/
 * Version: 99.1.0.2
 * License: GPL
 */
class BD_Activity_Stream_Shortcodes_Helper{
    
    private static $instance;

    private function __construct() {
  
        //register shortcodes
        $this->register_shortcodes();
  
    }
   
    /**
     * Register  shortcodes
     * 
     */
    private function register_shortcodes() {
        //[activity-stream display_comments=threaded|none title=somethimg per_page=something]
        
        add_shortcode( 'activity-stream', array( $this, 'generate_activity_stream' ) );
     
               

    }
    /**
     * Get Instance
     * 
     * 
     * @return BD_Activity_Stream_Shortcodes_Helper
     */
    public static function get_instance() {

        if ( !isset( self::$instance ) )
            self::$instance = new self();

        return self::$instance;
    }

    public function generate_activity_stream( $atts, $content = null ) {
        //allow to use all those args awesome!
       $atts=shortcode_atts(array(
            'title'            => false,
            'pagination'       => 'true',	 //show or not
            'display_comments' => 'threaded',
            'include'          => false,     // pass an activity_id or string of IDs comma-separated
            'exclude'          => false,     // pass an activity_id or string of IDs comma-separated
            'in'               => false,     // comma-separated list or array of activity IDs among which to search
            'sort'             => 'DESC',    // sort DESC or ASC
            'page'             => 1,         // which page to load
            'per_page'         => 10,        // how many per page
            'max'              => false,     // max number to return

            // Scope - pre-built activity filters for a user (friends/groups/favorites/mentions)
            'scope'            => false,

            // Filtering
            'user_id'          => false,    // user_id to filter on
            'object'           => false,    // object to filter on e.g. groups, profile, status, friends
            'action'           => false,    // action to filter on e.g. activity_update, new_forum_post, profile_updated
            'primary_id'       => false,    // object ID to filter on e.g. a group_id or forum_id or blog_id etc.
            'secondary_id'     => false,    // secondary object ID to filter on e.g. a post_id

            // Searching
            'search_terms'     => false,         // specify terms to search on
        ), $atts );
       
        extract( $atts );

        ob_start(); ?>

    <?php /* if( $use_compat): */ ?>
        <div id="buddypress">
    <?php /* endif; */ ?>
		
	<?php if($title): ?>
            <h3 class="activity-shortcode-title"><?php echo $title; ?></h3>
        <?php endif;?>    

<div>&nbsp;</div>
<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>
		<?php $search_value = !empty( $_REQUEST['search_terms'] ) ? stripslashes( $_REQUEST['search_terms'] ) : 'Filter contents'; ?><li class="feed"  style="list-style:none;">
		<form action="" method="get" id="search-contents-form">
		<input type="submit" id="contents_search_submit" name="contents_search_submit" value="<?php _e( 'Filter', 'buddypress' ) ?>" />
		<label><input type="text" name="search_terms" id="content_search" placeholder="<?php echo esc_attr( $search_value ) ?>" /></label>
		</form>
		</li>
		<?php do_action( 'bp_group_activity_syndication_options' ); ?>

		<li id="activity-filter-select" class="last"  style="list-style:none;">
			<label for="activity-filter-by"><?php _e( 'Show:', 'buddypress' ); ?></label> 
			<select id="activity-filter-by">
				<option value="-1"><?php _e( 'Everything', 'buddypress' ); ?></option>
				<option value="activity_update"><?php _e( 'Updates', 'buddypress' ); ?></option>

				<?php if ( bp_is_active( 'forums' ) ) : ?>
					<option value="new_forum_topic"><?php _e( 'Forum Topics', 'buddypress' ); ?></option>
					<option value="new_forum_post"><?php _e( 'Forum Replies', 'buddypress' ); ?></option>
				<?php endif; ?>

				<option value="joined_group"><?php _e( 'Group Memberships', 'buddypress' ); ?></option>

				<?php do_action( 'bp_group_activity_filter_options' ); ?>
			</select>
		</li>
	</ul>
</div><!-- .item-list-tabs -->
		
        <?php do_action( 'bp_before_activity_loop' ); ?>

        <?php if ( bp_has_activities($atts)  ) : ?>
            <div class="activity <?php if(!$display_comments): ?> hide-activity-comments<?php endif; ?> shortcode-activity-stream">

                <?php if($pagination):?>
                    <div class="pagination">
                        <div class="pag-count"><?php bp_activity_pagination_count(); ?></div>
                        <div class="pagination-links"><?php bp_activity_pagination_links(); ?></div>
                    </div>
                <?php endif;?>

                 <?php if ( empty( $_POST['page'] ) ) : ?>

                    <ul id="activity-stream" class="activity-list item-list" style="list-style:none;">

                 <?php endif; ?>

                 <?php while ( bp_activities() ) : bp_the_activity(); ?>

                    <?php locate_template( array( 'activity/entry.php' ), true, false ); ?>

                 <?php endwhile; ?>

                 <?php if ( empty( $_POST['page'] ) ) : ?>
                    </ul>
                 <?php endif; ?>
                
                <?php if($pagination):?>
                    <div class="pagination">
                        <div class="pag-count"><?php bp_activity_pagination_count(); ?></div>
                        <div class="pagination-links"><?php bp_activity_pagination_links(); ?></div>
                    </div>
                <?php endif;?>
            </div>

	
	<?php else : ?>

        <div id="message" class="info">
            <p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>
        </div>
            
          
    <?php endif; ?>
            
    <?php do_action( 'bp_after_activity_loop' ); ?>

    <form action="" name="activity-loop-form" id="activity-loop-form" method="post">

        <?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>

    </form>
     <?php /* if( $use_compat ): */ ?>       
        </div>
     <?php /* endif; */ ?>
    <?php 

	$output = ob_get_clean();
	
	
	return $output;

    }

}

BD_Activity_Stream_Shortcodes_Helper::get_instance();
