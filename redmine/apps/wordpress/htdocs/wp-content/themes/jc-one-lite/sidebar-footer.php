<div id="footer-sidebar" class="sidebar">
    
    <?php if (is_active_sidebar('footer_1')) { ?>
        <div id="footer-sidebar-1">
	        <?php dynamic_sidebar('footer_1'); ?>
        </div><!--//footer-sidebar-1-->
    <?php } ?>
    
    <?php if (is_active_sidebar('footer_2')) { ?>
        <div id="footer-sidebar-2">
	        <?php dynamic_sidebar('footer_2'); ?>
        </div><!--//footer-sidebar-2-->
    <?php } ?>

    <?php if (is_active_sidebar('footer_3')) { ?>
        <div id="footer-sidebar-3">
	        <?php dynamic_sidebar('footer_3'); ?>
        </div><!--//footer-sidebar-3-->
    <?php } ?>
    <div class="clear"></div>
</div>
