<?php get_header(); ?>
	
	<div id="contentcontainer" class="grid_9 alpha">

	<div id="content">
		
		<?php $counter = 0; ?>
	
		<?php if (have_posts()) : ?>
		
			<?php while (have_posts()) : the_post(); ?>
				
				<?php $counter++; ?>
				
				<div class="post">
					
					<div class="meta grid_2 alpha">
					
						<ul>
							<li class="auth"><em><?php _e('By',woothemes); ?></em> <?php the_author_posts_link(); ?></li>
							<li class="cat"><em><?php _e('In',woothemes); ?></em> <?php the_category(', '); ?></li>							
							<li class="date"><?php the_time('d/m/y'); ?> <?php _e('at',woothemes); ?> <?php the_time('H:i'); ?></li>
							<li class="comms"><a href="<?php comments_link(); ?>"><?php comments_number('0','1','%'); ?> <?php _e('Comments',woothemes); ?></a></li>
						</ul>
		
					</div><!--grid_2-->

					<div class="postbody grid_7 omega <?php if ( $counter == 1 ) { ?>first<?php } ?>">
						
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="entry">
							 <?php the_content(); ?>
							 <?php the_tags('<p>Tags: ',', ','</p>'); ?>
						</div><!--entry-->	
					
					</div><!--grid_7-->
					
					<div class="clearfix"></div>
					
				</div>
				
				<div id="comments">
					<?php comments_template(); ?>
				</div><!--comments-->
			
			<?php endwhile; ?>		
		
		<?php endif; ?>
	
	</div><!--content-->
	
	</div><!--contentcontainer-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>