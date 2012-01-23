<?php
/**
 * Cynthia front page theme.
 *
 * @package WordPress
 * @subpackage Expand2Web SmallBiz
 * @since Expand2Web SmallBiz 3.3
 */
 
 $hideSidebar = true;
?>
    <div id="homewrap">
	<div id="homeimage">
	    <img width="960" style="margin-left: -8px; padding-bottom:6px;" src="<?php bloginfo('template_url'); ?>/images/<?php echo biz_option('smallbiz_cynthia_page_image')?>" alt="<?php echo biz_option('smallbiz_name')?>" title="<?php echo biz_option('smallbiz_name')?>" />
	</div>
	
	<div id="home">

<div id="homepage-left">

<?php echo do_shortcode(biz_option('smallbiz_cynthia_left_text'))?>

</div>

<div id="homepage-right">

<?php echo do_shortcode(biz_option('smallbiz_cynthia_main_text'))?>

</div>

</div>
 
</div>
</div>
