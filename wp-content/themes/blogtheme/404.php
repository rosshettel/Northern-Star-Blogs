<?php get_header(); ?>
	
	<div id="contentcontainer" class="grid_9 alpha">

	<div id="content">
		
				
				<?php $counter++; ?>
				
				<div class="post">
					
					<div class="meta grid_2 alpha">
					
						<ul>
							<li class="auth"><?php _e('<em>404</em> Error!',woothemes); ?></li>
						</ul>
		
					</div><!--grid_2-->

					<div class="postbody grid_7 omega first">
						
						<h2><?php _e('Sorry! None has been found...',woothemes); ?></h2>
						
						<div class="entry">
							 <p><?php _e('Unfortunately we were not able to find that page you were looking for...',woothemes); ?></p>
							 <p><?php _e('We suggest that you try going back one page or visit',woothemes); ?> <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php _e('our homepage',woothemes); ?></a> <?php _e('instead',woothemes); ?>.</p>
						</div><!--entry-->	
					
					</div><!--grid_7-->
					
					<div class="clearfix"></div>
					
				</div>
	
	</div><!--content-->
	
		<div class="clearfix"></div>
	
	</div><!--contentcontainer-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>