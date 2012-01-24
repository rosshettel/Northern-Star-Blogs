<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/reset.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/960.css" type="text/css" />	
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
<!--[if lt IE 7]>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie6.css" type="text/css" />	
<![endif]-->

<?php wp_enqueue_script('jquery'); ?>     
<?php wp_head(); ?>

<!-- Show custom logo -->
<?php if ( get_option('woo_logo') <> "" ) { ?><style type="text/css">#logo h1 {background: url(<?php echo get_option('woo_logo'); ?>) no-repeat !important; }</style><?php } ?> 

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#sidebar_accordian").accordion({ header: "h4", autoHeight: false });
	});
</script>
   
</head>

<body <?php body_class(); ?>>

	<div id="header">
		
		<div class="container_12">
		
			<div id="logo" class="grid_4 alpha">
		
				<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>		
			
			</div>	<!--grid_6-->

			<div id="twitter" class="grid_8 omega">
				<ul id="twitter_update_list"><li></li></ul>
			</div>	<!--grid_6-->
			
			<div class="clearfix"></div>			
		
		</div><!--container_12-->								
	
	</div><!--header-->
	
	<div id="nav">
		
		<div class="container_12">
				<?php
				if ( function_exists('has_nav_menu') && has_nav_menu('primary-menu') ) {
					wp_nav_menu( array( 'depth' => 1, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'navigation', 'theme_location' => 'primary-menu' ) );
				} else {
				?>
				<ul id="navigation">
					<?php 
                	if ( get_option('woo_custom_nav_menu') == 'true' ) {
                    	if ( function_exists('woo_custom_navigation_output') )
                    	    woo_custom_navigation_output('desc=1');
    
                	} else { ?>
					<li><a <?php if ( is_home() ) { ?>class="current_page_item"<?php } ?> href="<?php bloginfo('url'); ?>">
							<?php _e('Home',woothemes); ?>
							<span><?php _e('Where it all began',woothemes); ?></span>
						</a>
					</li>
					<?php woothemes_get_pages(); ?>
					<?php } ?>
				</ul><!--navigation -->	
				<?php } ?>
		</div><!--container_12-->
	
	</div><!--nav-->	
	
	<div id="outerwrap" class="container_12">
	
		<div id="contentwrap">
		
		<div id="wrap">
