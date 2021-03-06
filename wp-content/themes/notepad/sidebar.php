	<div id="sidebar">
            <div class="widget">
                <h4>Search</h4>
                <?php include (TEMPLATEPATH . '/searchform.php'); ?>
            </div>
            
            <?php if(is_category()) :
                $category = get_the_category(); ?>
                
                <div class="widget">
                    <h4><?php echo $category[0]->cat_name; ?></h4>
                    <p><?php echo category_description(); ?></p>
                </div>
            <?php endif; ?>
            
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

		<div class="widget">
			<h4><?php _e('Pages','notepad-theme'); ?></h4>
			<ul>
			<?php wp_list_pages('title_li=' ); ?>
			</ul>
		</div>

		<div class="widget">
			<h4><?php _e('Category','notepad-theme'); ?></h4>
			<ul>
			<?php wp_list_categories('show_count=1&title_li='); ?>
			</ul>
		</div>

		<div class="widget">
			<h4><?php _e('Archives','notepad-theme'); ?></h4>
			<ul>
			<?php wp_get_archives('type=monthly'); ?>
			</ul>
		</div>

		<div class="widget">
			<h4><?php _e('Recent Comments','notepad-theme'); ?></h4>
			<?php include (TEMPLATEPATH . '/recent_comments.php'); ?>
		</div>

<?php endif; ?>

	</div>
	<!--/sidebar -->
