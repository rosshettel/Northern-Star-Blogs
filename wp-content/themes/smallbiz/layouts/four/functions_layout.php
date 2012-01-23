<?php
/**
 * Functions for Foursquare.
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
    "four_page_image" =>  'spinal.jpg',
"four_business_video" => '<p><img src="'.get_bloginfo('template_url').'/images/videoscreen2.jpg" style="margin-top: -1em;" alt="Expand2Web Video Stockimage" /></p>'   ,
    
"four_right_text" => '<h2>Articles</h2>
<hr />
<p>New Study shows benefits of our Product</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p> <p>Lorem ipsum dolor. </p>',

"four_left_bottom_text" => '<h2>About</h2>
<hr />
<div style="float: left; padding-right:15px; padding-bottom
:8px;"><img src="'.get_bloginfo('template_url').'/images/happy.jpg" alt="Expand2Web Example Image" /></div>
<p>This is an example of a Text Box, you could edit this to put information about yourself or your site so readers know where you are coming from. You rename the box to what you like. This box and all others can be edited with a visual editor inside of WordPress.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>',

"four_right_bottom_text" => '
<h2>Find Us</h2>
<hr />
<p><a href="#"><img src="'.get_bloginfo('template_url').'/images/googlemaps410x242.png" alt="Expand2Web Map Stockimage" /></a></p>',


	"layout_title" =>  'Foursquare',
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
	if(!get_option('smallbiz_four_business_video')){
	    update_option('smallbiz_four_business_video', $layout_defaults['four_business_video']);
	}
	if(!get_option('smallbiz_four_right_bottom_text')){
	    update_option('smallbiz_four_right_bottom_text', $layout_defaults['four_right_bottom_text']);
	}
	if(!get_option('smallbiz_four_left_bottom_text')){
	    update_option('smallbiz_four_left_bottom_text', $layout_defaults['four_left_bottom_text']);
	}
    if(!get_option('smallbiz_four_right_text')){
        update_option('smallbiz_four_right_text', $layout_defaults['four_right_text']);
    }
}
/* Extra options for layout must also be defined here: */
function smallbiz_layout_extra_options()
{
    $options = array(
			'four_business_video',
			'four_right_bottom_text',
			'four_left_bottom_text',
			'four_right_text',
			'four_page_image'
	);
	return $options;
}

/* Section on the options page for layout */
function smallbiz_theme_option_page_layout_options()
{
	global $wpdb, $smallbiz_cur_version ;
?>
<div id="outerbox">             
<h6>Upper Left Video Box</h6>
<div id="mainpagetext">

<p>1) Copy/Paste your embedd code from YouTube, Vimeo etc.</p>
<p>2) Re-size the Video by changing your Width 472px Height 298px in your embed code.</p>
<p>Optional 1: You can also insert a picture instead of a video (suggested size 472px by 298px) Use the Wordpress Media Uploader ("Media" -> "Add New") to get your image url.</p>
<p>Optional 2: You can use the space as a regular text box too. In the sidebar to your left click on "Appearance" -> "Editor" and add: #homepage-top-left {padding:10px;width: 452px;height: 278px;}</p>

	<p><textarea name="four_business_video" cols="60" rows="10"><?php echo get_option('smallbiz_four_business_video')?></textarea>
		</p>

            
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->
            
            </div> <!--outerbox-->
            
            
<div id="outerbox">             
<h6>Upper Right Text Box</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('four_right_text',get_option("smallbiz_four_right_text")); ?>
            

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
<h6>Lower Left Text Box</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('four_left_bottom_text',get_option("smallbiz_four_left_bottom_text")); ?>
            

            <?php

            $pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

            ?>
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->
            
            </div> <!--outerbox-->
            
            

<div id="outerbox">             
<h6>Lower Right Text Box</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('four_right_bottom_text',get_option("smallbiz_four_right_bottom_text")); ?>
            

            <?php

            $pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

            ?>
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->
            
            </div> <!--outerbox-->

<?php } ?>