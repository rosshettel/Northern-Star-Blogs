<?php
$layout = smallbiz_get_current_layout();  
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<!--Expand2Web SmallBiz Version 3.7.4a-->
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php if(get_option('smallbiz_mobile-layout-enabled') && $GLOBALS["smartphone"]){ ?>
<meta name="viewport" content="width=320px; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<?php } else { ?>
<meta name="viewport" content="width=980px;user-scalable=1;" />
<?php } ?>

<?php /* for custom fields start */ ?>
	<title><?php 
         /* Override the Title if the custom tag "_smallbiz_title" has been set. */
         /* if "_smallbiz_extra_title" has been set, append this. */
         $title = "";
         $is_smallbiz_frontpage = is_front_page() || (get_option('smallbiz_page_on_front') == $wp_query->get_queried_object()->ID);
         if (is_singular() && 
             !$is_smallbiz_frontpage){
             if (get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_title", true) && get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_title", true) != ""){ ?><?php
                 $title = get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_title", true);
             }else {             
                 $title = wp_title('|', true, 'right').biz_option('smallbiz_name');
             }
             if(get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_extra_title", true)){
                 if($title != ""){
                     $title .= ",";
                 }
                 $title .= get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_extra_title", true);
             }
         } else if ($is_smallbiz_frontpage){
             $title = biz_option('smallbiz_title');
         }
         if(!$title){ $title = wp_title('|', true, 'right').biz_option('smallbiz_name');}
         echo $title;
         
	?></title>
<?php $hide = (biz_option('smallbiz_seo_disabled')); if($hide == ""){ ?>
	<meta name="description" content="<?php 
         /* Override the description if the custom tag "_smallbiz_description" has been set. */
         /* if "_smallbiz_extra_descrition" has been set, append this. */
         $description = biz_option('smallbiz_description');
         if (is_singular()  &&
             !($is_smallbiz_frontpage)
             ){
             
             if (get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_description", true) && get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_description", true) != ""){ ?><?php
                 $description = get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_description", true);
             }else {             
                 $description = "";
             }
             if(get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_extra_description", true)){
                 if($description != ""){
                     $description .= ",";
                 }
                 $description .= get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_extra_description", true);
             }
             echo $description;
         } else {
             $description = biz_option('smallbiz_description');
            echo $description;
         }	
	?>" />
	<meta name="keywords" content="<?php 
         
         /* Override the keywords if the custom tag "_smallbiz_keywords" has been set. */
         /* if "_smallbiz_extra_keywords" has been set, append this. */
         if (is_singular()  &&
             !($is_smallbiz_frontpage)
             ){
             $keywords = "";
             if (get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_keywords", true) && get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_keywords", true) != ""){ ?><?php
                 $keywords = get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_keywords", true);
             }else {             
                 $keywords = "";
             }
             if(get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_extra_keywords", true)){
                 if($keywords != ""){
                     $keywords .= ",";
                 }
                 $keywords .= get_post_meta($wp_query->get_queried_object()->ID, "_smallbiz_extra_keywords", true);
             }
             echo $keywords;
         } else {
             $keywords = biz_option('smallbiz_keywords');
             echo $keywords;
         }	     
	     ?>" />
<?php /* for custom fields end */ ?>
<?php } ?>	
<?php echo biz_option('smallbiz_webmaster');?>

<style>
#page{background:#<?php echo biz_option('smallbiz_pages_color')?>;}
#access{background-color:#<?php echo biz_option('smallbiz_menu_color')?>;}
#access a{color:#<?php echo biz_option('smallbiz_passive_color')?>;}
#access ul :hover > a {color:#<?php echo biz_option('smallbiz_hover_color')?>;}
#access li:hover > a,{color:#<?php echo biz_option('smallbiz_hover_color')?>;}
#access ul li.current_page_item > a,
#access ul li.current-menu-ancestor > a,
#access ul li.current-menu-item > a,
#access ul li.current-menu-parent > a {color: #<?php echo biz_option('smallbiz_active_color')?>;}
#access ul ul a {background-color:#<?php echo biz_option('smallbiz_menu_color')?>;}
#access ul ul :hover > a {background-color:#<?php echo biz_option('smallbiz_menu_color')?>;color:#<?php echo biz_option('smallbiz_hover_color')?>;}
#access ul ul li.current_page_item > a,
#access ul ul li.current-menu-ancestor > a,
#access ul ul li.current-menu-item > a,
#access ul ul li.current-menu-parent > a {
background-color:#<?php echo biz_option('smallbiz_menu_color')?>;color: #<?php echo biz_option('smallbiz_active_color')?>;}
#sidebar h3 {background-color:#<?php echo biz_option('smallbiz_menu_color')?>;color: #<?php echo biz_option('smallbiz_active_color')?>;}
h2, .smallbiz_map h4 {color: #<?php echo biz_option('smallbiz_headertag_color')?>;}
#blogpost a{color: #<?php echo biz_option('smallbiz_headertag_color')?>;}
a:link, a:visited {color: #<?php echo biz_option('smallbiz_hyper_color')?>;}
a:hover {color: #<?php echo biz_option('smallbiz_hyperhover_color')?>;}
p, ul, li, small {color: #<?php echo biz_option('smallbiz_p_color')?>;}
.contactleft, .contactright {color: #<?php echo biz_option('smallbiz_p_color')?>;}
#respond {color: #<?php echo biz_option('smallbiz_p_color')?>;}
p.footercenter {color: #<?php echo biz_option('smallbiz_creds_color')?>;}
#footer, #footer a {color: #<?php echo biz_option('smallbiz_creds_color')?>;}
</style>
<!-- Styles -->
<link href="<?php bloginfo('template_url'); ?>/css/screen.css?v=<?php echo biz_option('smallbiz_version'); ?>" media="screen,projection,tv" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo('template_url').'/layouts/'.$layout; ?>/css/screen.css?v=<?php echo biz_option('smallbiz_version'); ?>" media="screen,projection,tv" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/smallbiz-options.css.php" type="text/css" media="screen" />
 <!--[if lte IE 9]>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie.css" type="text/css" media="screen" />
<![endif]-->
<!--[if lte IE 7]>
 <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie7.css" type="text/css" media="screen" />
 <![endif]-->
<!--[if lte IE 6]>  
<script src="<?php bloginfo('template_directory'); ?>/js/ie6toie8.js" type="text/javascript"></script> 
<![endif]-->
<!-- RSS & Pingback -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!-- WP3 Background Color/image: -->
<?php 
if(get_background_color() && get_background_color() != "#"){
if(!get_background_image()){
echo "<style>body{background-image:none;}</style>";
}
}
?>
<!-- SmallBiz WordPress Theme by http://www.Expand2Web.com -->
<!-- WP Head -->	
<?php wp_head(); ?>
<?php echo biz_option('smallbiz_analytics');?>
</head>
<body <?php body_class(); ?>>
<div id="site-wrap">
<div onClick="document.location='<?php bloginfo('url')?>';" class= "vcard" id="header" style="background:url(<?php bloginfo('template_url') ?>/images/banners/<?php echo biz_option('smallbiz_banner') ?>) no-repeat;" >
<!-- <div></div> -->
<!-- Hide header if checkbox is not equal to by adding div id noheader -->
<?php $hide = (biz_option('smallbiz_header_box_disabled')); if($hide != ""){ ?>
<div id="noheader"> 		<?php } ?>	
<h1><span class="fn org"><a style="color:#<?php echo biz_option('smallbiz_name_color') ?>;text-decoration:none;" href="<?php bloginfo('url')?>"><?php echo biz_option('smallbiz_name');//bloginfo('name'); ?></a></span></h1>
<h2 style="color:#<?php echo biz_option('smallbiz_sub_header_color')?>;"><?php echo biz_option('smallbiz_sub_header'); //bloginfo('description'); ?></h2>
<p>
<span class="adr">
<span class="street-address" style="color:#<?php echo biz_option('smallbiz_street_color')?>;"><?php echo biz_option('smallbiz_street')?></span><br />

<span class="locality" style="color:#<?php echo biz_option('smallbiz_city_color')?>;"><?php echo biz_option('smallbiz_city')?></span>

<span class="region" style="color:#<?php echo biz_option('smallbiz_state_color')?>;"><?php echo biz_option('smallbiz_state')?></span>

<span class="postal-code" style="color:#<?php echo biz_option('smallbiz_zip_color')?>;"><?php echo biz_option('smallbiz_zip')?></span><br />

<span class="tel" style="color:#<?php echo biz_option('smallbiz_telephone_color')?>;"><?php echo biz_option('smallbiz_telephone')?></span><br />

<span class="email"><a style="color:#<?php echo biz_option('smallbiz_headeremail_color')?>;" href="mailto:<?php echo biz_option('smallbiz_headeremail')?>"><?php echo biz_option('smallbiz_headeremail')?></a></span>
</span>

</p>
<?php $hide = (biz_option('smallbiz_header_box_disabled')); if($hide != ""){ ?> </div> <?php } ?>	
</div>
<div id="page">
<div id="access" role="navigation" class="menu">
<?php /* SmallBiz navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  
The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
<?php smallbiz_wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
</div><!-- #access -->
<div id="content">