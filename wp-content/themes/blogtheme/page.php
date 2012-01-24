<?php get_header(); ?>
	
	<div id="contentcontainer" class="grid_9 alpha">

	<div id="content">
	
		<?php if (have_posts()) : ?>
		
			<?php while (have_posts()) : the_post(); ?>
				
				<div class="post">
					
					<div class="meta grid_2 alpha">
					
						<ul>
							<li class="pagedesc"><?php 	if ( get_post_meta( $post->ID, 'page-description', $single = true ) <> "" ) { echo '<em>'.__('What is this?',woothemes).'</em><br />' . stripslashes( get_post_meta( $post->ID, 'page-description', $single = true ) ); } else { echo '&nbsp;'; }?></li>
						</ul>
		
					</div><!--grid_2-->

					<div class="postbody grid_7 omega first">
						
						<h2><?php the_title(); ?></h2>
						
						<div class="entry">
							 <?php the_content(); ?>
						</div><!--entry-->	
					
					</div><!--grid_7-->
					
					<div class="clearfix"></div>
					
				</div>
			
			<?php endwhile; ?>		
		
		<?php endif; ?>
	
	</div><!--content-->		
	
	</div><!--contentcontainer-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>