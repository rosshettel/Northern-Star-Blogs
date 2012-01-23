<?php
/**
 * Functions for Rotator-Video.
 *
 * @package WordPress
 * @subpackage Expand2Web SmallBiz
 * @since Expand2Web SmallBiz 3.5.1
 */ 

/* Defaults overrides for Layout */
function smallbiz_defaults_for_layout(){
  global $smallbiz_defaults_for_layout, $smallbiz_cur_version;
  if($smallbiz_defaults_for_layout){
      return $smallbiz_defaults_for_layout;
  }

  $smallbiz_defaults_for_layout = array(
"rotator_video" => '<iframe width="300" height="255" src="http://www.youtube.com/embed/r15S62FuOS4?rel=0" frameborder="0" allowfullscreen></iframe>',
    
    
"rotator_main_text1" =>  '<h2>Welcome to my Business!</h2>
<p class="p">If you are looking for first-class service, you have come to the right place! We aim to be friendly and approachable.</p>
<h2>Call us today: 1.192.555.1212 </h2>
<p class="p">We are here to serve you and answer any questions you may have. We are the only Certified and NRT Approved Center in the Pacific Region.</p><p class="p">We specialize is exactly what you need - New customers and inquiries are always welcome.</p><p class="p">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
 tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim 
veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea 
commodo consequat. Duis aute irure dolor in reprehenderit in voluptate 
velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint 
occaecat cupidatat non proident, sunt in culpa qui officia deserunt 
mollit anim id est laborum.</p><p class="p">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
 tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim 
veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea 
commodo consequat.</p>',

"rotator_boxe1" => '<h2>Blog</h2><hr /> <a href="#"><img src="'.get_bloginfo('template_url').'/images/newyear.png" alt="Expand2Web Example Image" /></a>',
"rotator_boxe2" => '<h2>About</h2><hr /> <a href="#"><img src="'.get_bloginfo('template_url').'/images/crew.png" alt="Expand2Web Example Image" /></a>',
"rotator_boxe3" => '<h2>Articles</h2><hr /> <a href="#"><img src="'.get_bloginfo('template_url').'/images/xray.png" alt="Expand2Web Example Image" /></a>',
"rotator_boxe4" => '<h2>Find Us</h2><hr /> <a href="#"><img src="'.get_bloginfo('template_url').'/images/maps.png" alt="Expand2Web Example Image" /></a>',
	"layout_title" =>  'Rotator',
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
	if(!get_option('smallbiz_rotator_boxe1')){
	    update_option('smallbiz_rotator_boxe1', $layout_defaults['rotator_boxe1']);
	}
    if(!get_option('smallbiz_rotator_boxe2')){
        update_option('smallbiz_rotator_boxe2', $layout_defaults['rotator_boxe2']);
    }
    if(!get_option('smallbiz_rotator_boxe3')){
        update_option('smallbiz_rotator_boxe3', $layout_defaults['rotator_boxe3']);
    }
    if(!get_option('smallbiz_rotator_boxe4')){
        update_option('smallbiz_rotator_boxe4', $layout_defaults['rotator_boxe4']);
    }
}
/* Extra options for layout must also be defined here: */
function smallbiz_layout_extra_options()
{
    $options = array(
        'rotator_video',
        'rotator_main_text1',
			'rotator_boxe1',
			'rotator_boxe2',
			'rotator_boxe3',
			'rotator_boxe4',
	);
	return $options;
}

/* Section on the options page for layout */
function smallbiz_theme_option_page_layout_options()
{
	global $wpdb, $smallbiz_cur_version ;
?>

<div id="outerbox">             
<h6>Home Page Main Text Box</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('rotator_main_text1',get_option("smallbiz_rotator_main_text1")); ?>
            

            <?php

            $pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

            ?>
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->
</div> <!--outerbox-->
       
<div id="outerbox">             
<h6>Video - Left Side Homepage</h6>
<div id="mainpagetext">
 
 <p>Get your video embedd code from Youtube, Vimeo etc. & paste the supplied code into the field below. </p>
	<p>Consider the width of your video - an ideal width would be around 300px wide.</p>
<p>
			<textarea name="rotator_video" cols="60" rows="10"><?php echo get_option('smallbiz_rotator_video')?></textarea>
		</p>
			
			   <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
    
</div> <!--mainpagetext-->
</div> <!--outerbox-->      

<div id="outerbox">             
<h6>Bottom Row Box 1</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('rotator_boxe1',get_option("smallbiz_rotator_boxe1")); ?>
            

            <?php

            $pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

            ?>
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->
<div id="protip">
<p>ProTip: Upload your own image. Click "Media" -> "Add New" to upload your image. <br />Toggle the Editor into HTML and copy/paste the Image URL into this box.</p>
</div>
</div> <!--outerbox-->
            
            
<div id="outerbox">             
<h6>Bottom Row Box 2</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('rotator_boxe2',get_option("smallbiz_rotator_boxe2")); ?>
            

            <?php

            $pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

            ?>
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->
</div> <!--outerbox-->
            
<div id="outerbox">             
<h6>Bottom Row Box 3</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('rotator_boxe3',get_option("smallbiz_rotator_boxe3")); ?>
            

            <?php

            $pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

            ?>
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->
</div> <!--outerbox-->
            
            

<div id="outerbox">             
<h6>Bottom Row Box 4</h6>
<div id="mainpagetext">

            <?php echo tinyMCE_HTMLarea('rotator_boxe4',get_option("smallbiz_rotator_boxe4")); ?>
            

            <?php

            $pages = $wpdb->get_results('select * from '. $wpdb->prefix .'posts where post_type="page" and post_status="publish"');

            ?>
            
               <br />
	 <p><input type="submit" value="Save Changes" name="sales_update" /></p>
            
</div> <!--mainpagetext-->            
</div> <!--outerbox-->
<?php } ?>
