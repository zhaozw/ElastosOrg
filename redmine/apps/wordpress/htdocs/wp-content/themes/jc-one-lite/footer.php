            </div><!--//#main-->
            <?php if (jc_has_sidebar('primary')) get_sidebar(); ?>
            <div class="clear"></div>
            <?php if (jc_has_sidebar('footer')) get_template_part('sidebar', 'footer'); ?>            
        </div><!--//#container-->
        <footer class="main">
            <?php
            // Socials Icons
            $footer_social = jc_one_get_social();
            if ($footer_social) echo $footer_social; 
        
            $footer_content = jc_get_option('footer_content');
            if ($footer_content) { ?>
            <div class="footer-content"><?php echo $footer_content; ?></div>
            <?php }
            $footer_copyright = jc_get_option('footer_copyright');
            if ($footer_copyright) { ?>
            <div class="footer-copyright"><?php echo $footer_copyright; ?></div>
            <?php } ?>
        </footer><!--//footer#main-->
    </div>
    <?php wp_footer(); ?> 
</body>
</html>
