		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<div id="footer">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="footer-widgets">
					<?php get_sidebar( 'footer' ); ?>
				</div>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo">
				<?php do_action( 'bp_dtheme_credits' ) ?>
				<p>Copyright&nbsp;&copy;&nbsp;2012-<?php echo date('Y'); ?>&nbsp;<a href="http://elastos.org">elastos.org</a>&nbsp;|&nbsp;<a href="http://www.miitbeian.gov.cn">沪ICP备11004688号-7</a></p>
			</div>

			<script>
			(new SidebarFollow()).init({
			    element: jQuery('#bp_core_recently_active_widget-2'),
			    distanceToTop: 15
			});
			</script>

			<?php do_action( 'bp_footer' ); ?>

		</div><!-- #footer -->

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

	</body>

</html>
