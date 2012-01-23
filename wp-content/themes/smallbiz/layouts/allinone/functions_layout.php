<?php
/**
 * Functions for All-In-One.
 *
 * @package WordPress
 * @subpackage Expand2Web SmallBiz
 * @since Expand2Web SmallBiz 3.3
 */ 

/* Defaults overrides for Layout */
function smallbiz_defaults_for_layout(){
  global $smallbiz_defaults_for_layout, $smallbiz_cur_version;
  if($smallbiz_defaults_for_layout){
      return $smallbiz_defaults_for_layout;
  }

  $smallbiz_defaults_for_layout = array(
	"allinone_main_text" =>  '<h2>Welcome to my Business!</h2>
<p>If you are looking for first-class service, you have come to the right place! We aim to be friendly and approachable.</p>
<h2>Call us today: 1.192.555.1212 </h2>
<p>We are here to serve you and answer any questions you may have. We are the only Certified Business in your area.</p>',

"allinone_business_video" =>	('<p><img src="'.get_bloginfo('template_url').'/images/allinone-video.jpg" alt="Expand2Web Video Stockimage" /></p>')	,
	
"allinone_left_text" => (' <h2>About</h2>
<hr /><div style="float: left; padding-right:12px;"><img src="'.get_bloginfo('template_url').'/images/happy.jpg" style="margin-top: 9px; "alt="Expand2Web Stockimage" /></div><p>This is an example of a Text Box, you can edit this text and put information about yourself, services or your site so readers know where you are coming from. </p><p>You can rename the box to what you like. This box and all others can be edited within the Smallbiz Options Panel - a visual editor inside of WordPress.'),

"allinone_middle_text" => ('<h2>Articles</h2>
<hr /><p>New Study shows benefits of our product</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>'),

"allinone_right_text" => ('<h2>Find Us</h2>
<hr />
<p>Click on the Map for Directions</p><a href="#"><img src="'.get_bloginfo('template_url').'/images/googlemaps362x260.png" alt="Expand2Web Stockimage" /></a>'),

	"layout_title" =>  'All-In-One',
	);
    return $smallbiz_defaults_for_layout;
}

/* Extra options for layout */
/* Not sure this is needed -- check. */ 
function smallbiz_on_layout_activate()
{
	global $wpdb;
	$smallbiz_defaults = smallbiz_defaults();
	$layout_defaults   = smallbiz_defaults_for_layout();
	if(!get_option('smallbiz_allinone_left_text')){
	    update_option('smallbiz_allinone_left_text', $layout_defaults['allinone_left_text']);
	}
	if(!get_option('smallbiz_allinone_middle_text')){
	    update_option('smallbiz_allinone_middle_text', $layout_defaults['allinone_middle_text']);
	}
	if(!get_option('smallbiz_allinone_right_text')){
	    update_option('smallbiz_allinone_right_text', $layout_defaults['allinone_right_text']);
	}
}
/* Extra options for layout must also be defined here: */
function smallbiz_layout_extra_options()
{
    $options = array(
			'allinone_main_text',
			'allinone_left_text',
			'allinone_middle_text',
			'allinone_right_text',
			'allinone_business_video',
	);
	return $options;
}

/* Section on the options page for layout */
function smallbiz_theme_option_page_layout_options()
{
	global $wpdb, $smallbiz_cur_version ;
?>

<div id="outerbox"> 			
  <h6>Home Page Text Box - Top of Page</h6>
  <div id="mainpagetext">
            <?php echo tinyMCE_HTMLarea('allinone_main_text',get_option("smallbiz_allinone_main_text")); ?>
			<?php
			$pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');
			?>	
			
			   <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
  </div> <!--mainpagetext-->			
</div> <!--outerbox-->

<div id="outerbox"> 			
<h6>Home Page Video Box - Video will be inserted to the Left of Top Text Box </h6>
  <div id="mainpagetext">
    <p>1) Copy/Paste your embedd code from YouTube, Vimeo etc.</p>
    <p>2) Re-size the Video by changing your Width 200px Height 175px in your embed code.</p>
    <p>Optional: You can also insert a picture instead of a video. <br />Click on: "Media" -> "Add New" to upload an image. Copy the image URL into the text box. </p>
            
           
           <p><textarea name="allinone_business_video" cols="60" rows="10"><?php echo get_option('smallbiz_allinone_business_video')?></textarea></p>
            
           
			
			   <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
  </div> <!--mainpagetext-->			
</div> <!--outerbox-->			
			
<div id="outerbox"> 			
  <h6>Home Page Text Box - Left Side</h6>
  <div id="mainpagetext">
            <?php echo tinyMCE_HTMLarea('allinone_left_text',get_option("smallbiz_allinone_left_text")); ?>
			<?php
			$pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');
			?>	
			
			   <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
  </div> <!--mainpagetext-->
  <div id="protip">
<p>ProTip: Upload your own image. Click "Media" -> "Add New" to upload your image and copy/paste the URL into this box.</p>
</div>
</div> <!--outerbox-->
			
<div id="outerbox"> 			
  <h6>Home Page Text Box - Middle</h6>
  <div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('allinone_middle_text',get_option("smallbiz_allinone_middle_text")); ?>
			

			<?php

			$pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

			?>
			
			   <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
  </div> <!--mainpagetext-->
</div> <!--outerbox-->
			
			

<div id="outerbox"> 			
<h6>Home Page Text Box - Right Side</h6>
  <div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('allinone_right_text',get_option("smallbiz_allinone_right_text")); ?>
			

			<?php

			$pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

			?>
			
			   <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
			
  </div> <!--mainpagetext-->			
</div> <!--outerbox-->
<?php } ?>