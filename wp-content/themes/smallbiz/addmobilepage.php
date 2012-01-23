<?php
/*
Template Name: Additional Mobile Page
*/
  $hideSidebar = true;
?>

<head>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile11.dtd">
<meta http-equiv="Content-Type" value="application/xhtml+xml" /> 
<meta name="viewport" content="width=320px; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<meta name='robots' content='noindex,nofollow' />
<title><?php echo biz_option('smallbiz_title');?></title>
<meta name="description" content="<?php echo biz_option('smallbiz_description');?>"/>
<meta name="keywords" content="<?php echo biz_option('smallbiz_keywords');?>" />
<?php echo biz_option('smallbiz_analytics');?>	 

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/mobile.css" type="text/css" media="screen" />

<style>

.tertiary-menu li{
background-color: #<?php echo biz_option('smallbiz_mobile-button-color')?>;
}
.tertiary-menu a{
color: #<?php echo biz_option('smallbiz_mobile-text-button-color')?>;
}

#headerstrip{
	background-image:url(<?php bloginfo('template_url'); ?>/images/mobile/mobile-header.jpg);
	background-repeat:repeat-x;
}
	
#footerstrip{
	background-image:url(<?php bloginfo('template_url'); ?>/images/mobile/mobile-header.jpg);
	background-repeat:repeat-x;
}
	
#footer-mobile-switch{
	background-image:url(<?php bloginfo('template_url'); ?>/images/mobile/mobile-header.jpg);
background-repeat:repeat-x;
}

#pagewrap{background-color:#<?php echo biz_option('smallbiz_mobile-body-color')?>;
}
</style>

	     
</head>
<body>

<div id="pagewrap">

<div id="headerstrip">

<h1>
<a style="color:black;text-decoration:none;" href="<?php bloginfo('url'); ?>">
<?php echo biz_option('smallbiz_name');/* bloginfo('name'); */ ?>
</a>
</h1>

<h2 style="color:black;"><?php echo biz_option('smallbiz_sub_header'); /* bloginfo('description'); */ ?>
</h2>

</div> <!--close headerstrip-->

<div id="mobile-text">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">

		<?php if(!is_page('home')) :?><?php endif; ?>

			<div class="entry">

							<?php global $more; $more = false; ?>

<?php the_content('...Continue Reading'); ?>

<?php $more = true; ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>

		</div>

		<?php endwhile; endif; ?>

</div> <!--closing mobile text-->

<div id="home-mob-menu">
<!--Mobile Menu-->
<?php wp_nav_menu( array(
'container_class' => 'tertiary-menu', 'theme_location' => 'tertiary-menu', 'fallback_cb' => '' ) ); ?>
</div>

<div id="home-link">

<a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('url'); ?>/wp-content/themes/smallbiz/images/mobile/home-f.png" alt="mobile-back-home" style="padding-right:2px;" /></a>

<a href="tel:<?php echo biz_option('smallbiz_countryprefix')?>-<?php echo biz_option('smallbiz_telephone'); ?>"><img src="<?php bloginfo('url'); ?>/wp-content/themes/smallbiz/images/mobile/phone2-f.png" alt="mobile-call-now" style="padding-right:2px;" /></a>

<a href="mailto:<?php echo biz_option('smallbiz_email')?>" target="_blank"><img src="<?php bloginfo('url'); ?>/wp-content/themes/smallbiz/images/mobile/mail-f.png" alt="mobile-mail-now" style="padding-right:2px;" /></a>

<a href="<?php echo biz_option('smallbiz_mobile-map')?>" ><img src="<?php bloginfo('url'); ?>/wp-content/themes/smallbiz/images/mobile/directions1-f.png" alt="mobile-directions"></a>

</div>



<div id="footerstrip">
<p><?php echo biz_option('smallbiz_credit');?></p>
</div> <!--close footerstrip-->

</div> <!--close pagewrap-->
</body>