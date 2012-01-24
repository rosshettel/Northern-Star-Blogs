<?php
/*
Template Name: Sitemap
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
						
						<h2><?php _e('Sitemap',woothemes); ?></h2>
						
						<div class="entry">
						
                        <h2><?php _e('Pages',woothemes); ?></h2>
            
                        <ul>
                            <?php wp_list_pages('depth=1&sort_column=menu_order&title_li=' ); ?>		
                        </ul>		

                        <h2><?php _e('Categories',woothemes); ?></h2>
            
                        <ul>
                            <?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>	
                        </ul>	
                        
					<?php
                
                        $cats = get_categories();
                        foreach ($cats as $cat) {
                
                        query_posts('cat='.$cat->cat_ID);
            
                    ?>
                    
                        <h2 style="margin-top:10px !important; padding:0px;"><?php echo $cat->cat_name; ?></h2>
            
                        <ul>	
                                <?php while (have_posts()) : the_post(); ?>
                                <li style="font-weight:normal !important;"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php _e('Comments',woothemes); ?> (<?php echo $post->comment_count ?>)</li>
                                <?php endwhile;  ?>
                        </ul>
                
					<?php } ?>					                                                
						
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

<?php $GLOBALS['sitemap'] = true; get_sidebar(); ?>

<?php get_footer(); ?>