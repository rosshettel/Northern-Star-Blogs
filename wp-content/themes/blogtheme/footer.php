
		</div><!--wrap-->
		
		</div><!--contentwrap-->
	
	</div><!--outerwrap-->
	
	<div class="clearfix"></div>
	
	<div id="footer">
		
		<div class="container_12">
		
			<div class="grid_4 alpha">
				
				<?php if ( woo_active_sidebar( 'footer-1' ) ) { woo_sidebar( 'footer-1' ); } ?>	
		
			</div><!--grid_4-->

			<div class="grid_4">

				<?php if ( woo_active_sidebar( 'footer-2' ) ) { woo_sidebar( 'footer-2' ); } ?>	
		
			</div><!--grid_4-->

			<div class="grid_4 omega">

				<?php if ( woo_active_sidebar( 'footer-3' ) ) { woo_sidebar( 'footer-3' ); } ?>				
		
			</div><!--grid_4-->				
		
			<div class="clearfix"></div>
			
			<div id="credit">
				
				<p>&copy; <?php _e( 'Copyright', 'woothemes' ); ?> <?php bloginfo( 'name' ); ?>. <?php _e( 'All Rights Reserved. BlogTheme by', 'woothemes' ); ?> <a href="http://www.woothemes.com"><img src="<?php echo get_template_directory_uri(); ?>/images/woothemes-trans.png" alt="WooThemes" id="woo" /></a></p>
				
			</div>
		
		</div><!--container_12-->
		
	</div>

<?php if( get_option( 'woo_twitter' ) !== '' ) { ?>
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline/<?php echo get_option( 'woo_twitter' ); ?>.json?callback=twitterCallback2&amp;count=1&amp;include_rts=t"></script>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>