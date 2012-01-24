<?php
// Register widgetized areas

function the_widgets_init() {
    if ( ! function_exists( 'register_sidebars' ) )
        return;

    register_sidebar( array( 'name' => 'Sidebar', 'id' => 'primary', 'before_widget' => '<div class="widget">','after_widget' => '</div></div>','before_title' => '<h4><a href="#">','after_title' => '</a></h4><div class="content">' ) );
    register_sidebar( array( 'name' => 'Footer 1', 'id' => 'footer-1', 'before_widget' => '','after_widget' => '','before_title' => '<h4>','after_title' => '</h4>' ) );
    register_sidebar( array( 'name' => 'Footer 2', 'id' => 'footer-2', 'before_widget' => '','after_widget' => '','before_title' => '<h4>','after_title' => '</h4>' ) );
    register_sidebar( array( 'name' => 'Footer 3', 'id' => 'footer-3', 'before_widget' => '','after_widget' => '','before_title' => '<h4>','after_title' => '</h4>' ) );    
    
}

add_action( 'init', 'the_widgets_init' ); 
?>