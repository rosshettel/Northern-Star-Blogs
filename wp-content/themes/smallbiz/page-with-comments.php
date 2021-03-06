<?php
/*
Template Name: Page with Comments
*/
if (is_front_page() && (get_option('smallbiz_mobile-layout-enabled') && $GLOBALS["smartphone"])){
    include("page.php");
} else { 
?>
<?php get_header(); ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<?php if(!is_page('home')) :?><h2><?php the_title(); ?></h2><?php endif; ?>
			<div class="entry">
							<?php global $more; $more = false; ?>
<?php the_content('...Continue Reading'); ?>
<?php $more = true; ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php comments_template(); ?>
<?php include(TEMPLATEPATH."/sidebar.php");?>
<?php get_footer(); ?>
<?php } ?>