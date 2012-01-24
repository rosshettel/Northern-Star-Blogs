<?php 

function woothemes_get_pages() {
	global $wpdb;

	$getcats = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'page' AND $wpdb->posts.post_status = 'publish'");

	$exclude = get_option('woo_menupages');
	$testarr=split(",",$exclude);
	$testarr = array_diff($testarr, array(""));	
	
	foreach($getcats as $thecat) {
	
		$display = true;
		foreach ( $testarr as $test ) { if ( $test == $thecat->ID ) { $display = false; } }
		
		if ( $display ) {
	
			if ( is_page($thecat->ID) ) { $current = 'class="current_page_item"'; } else { $current = ''; }
			echo '<li><a ' . $current . ' href="'.get_permalink($thecat->ID).'">'.$thecat->post_title;
			if ( get_post_meta( $thecat->ID, 'page-description', true ) <> "" ) { echo '<span>'.get_post_meta( $thecat->ID, 'page-description', true ).'</span>'; } else { echo '<span>&nbsp;</span>'; }
			echo'</a></li>';
		
		}
		
	}
}	

/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu' ) ) );
}

?>