
  		</section>

		<?php get_sidebar(); ?>

	  </div>

  		<footer>
        <!-- feel free to remove the credit below, but we'd love it if you would leave it in. -->
  			<p>
  				&copy; <?php echo date("Y") ?> <?php bloginfo('name'); ?> | powered by <a href="http://wordpress.org/">WordPress</a><?php if (is_home()) { ?> | Theme by <a href="http://www.stinkyinkshop.co.uk/themes/">Stinkyink</a><?php } ?><br />
  				<a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a>	and <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>.
  			</p>
  		</footer>

  		<?php wp_footer(); ?>

	</body>
</html>
