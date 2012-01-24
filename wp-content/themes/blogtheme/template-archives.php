<?php
/*
Template Name: Archives Page
*/
?>

<?php get_header(); ?>
	
	<div id="contentcontainer" class="grid_9 alpha">

	<div id="content">
				
				<div class="post">
					
					<div class="meta grid_2 alpha">
					
						<ul>
							<li class="pagedesc"><?php 	if ( get_post_meta( $post->ID, 'page-description', $single = true ) <> "" ) { echo '<em>'.__('What is this?',woothemes).'</em><br />' . stripslashes( get_post_meta( $post->ID, 'page-description', $single = true ) ); } else { echo '&nbsp;'; }?></li>
						</ul>
		
					</div><!--grid_2-->

					<div class="postbody grid_7 omega first">
						
						<h2><?php _e('Archives',woothemes); ?></h2>
						
						<div class="entry">
						
                        <h2><?php _e('The Last 30 Posts',woothemes); ?></h2>
            
                        <ul>
                            <?php query_posts('showposts=30'); ?>
                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                                <?php $wp_query->is_home = false; ?>
                                <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php the_time('j F Y') ?> - <?php echo $post->comment_count ?> comments</li>
                            
                            <?php endwhile; endif; ?>	
                        </ul>								

                        <h2><?php _e('Categories',woothemes); ?></h2>
            
                        <ul>
                            <?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>	
                        </ul>						

                        <h2><?php _e('Monthly Archives',woothemes); ?></h2>
            
                        <ul>
                            <?php wp_get_archives('type=monthly&show_post_count=1') ?>	
                        </ul>		
						
						</div><!--entry-->	
					
					</div><!--grid_7-->
					
					<div class="clearfix"></div>
					
				</div>
	
	</div><!--content-->	
		
			<div class="clearfix"></div>
			
			<div id="postnav">

				&nbsp;
			
			</div><!--postnav-->				
	
	</div><!--contentcontainer-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>