<div class="right">
    <form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>" >
        <div>
            <label class="hidden" for="s"><?php _e('Search', 'jc-one-lite'); ?>: </label>
            <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Search', 'jc-one-lite'); ?>" />
        </div>
    </form>
</div>
