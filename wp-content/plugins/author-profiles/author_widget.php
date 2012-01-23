<?php
/**
 * Plugin Name: Author Avatar Images and Post Links.
 * Plugin URI: http://sabir.co.in/plugins/
 * Description: A widget that allows you to show the avatar Image and post link to the author page.The previously plugin display only the tabular format and the avatar images.Even it can exclude the some authors which you want to exclude from the widgets just by typing the author name seperated by comma. eg:sam,sabir in the column.
 Addidtionally added one more functionality that is it links to the author post page. It display all the post which is posted by Author.
 2.It sort the author by number of posted by the author.If the author has posted zero post its name will not appear in the list.
 3.If you mouseover on the link it display the author post counts.
 For more information visit: http://www.sabir.co.in/plugins
 
 * Version: 1.1
 * Author: Sabir Abdul Gafoor
 * Author URI: http://sabir.co.in
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'sab_author_widgets' );

function sab_author_widgets() {
	register_widget( 'sab_author_widget' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class sab_author_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function sab_author_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sab_author_widget', 'description' => __('Display Author Avatars list.', 'sabir') );

		/* Widget control settings. */
		//$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'lt_300x250_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'sab_author_widget', __('Author Avatars List', 'sabir'), $widget_ops, $control_ops );
	}




	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
	    $title = apply_filters('widget_title', $instance['title'] );
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
		{
		echo $before_title . $title . $after_title;
		}
		else
		{
			echo $before_title . 'Author Profile' . $after_title;
		}
	   
	 

       ?>
       <?php
	   $colums=$instance['columns'];
	   $author_space=$instance['author_space'];
	   $author_numbers=$instance['author_numbers'];
	   $author_size=$instance['author_size'];
	   $width='auto';
	   if($author_size) { } else {$author_size=64; $width=90;}
	   if($author_numbers) {} else { $author_numbers=50; }
	   if($author_space) {} else { $author_space=15; }
	   if($colums) {} else {$colums=3;}
	   $list = $instance['exclude_author'];
	   $authorlink = "yes";
$array = explode(',', $list); 
 $count=count($array);
for($excludeauthor=0;$excludeauthor<=$count;$excludeauthor++)
{
	$exclude.="user_login!='".trim($array[$excludeauthor])."'";
	if($excludeauthor!=$count)
	{
		$exclude.=" and ";
	}
}
 $where = "WHERE ".$exclude."";
global $wpdb;
$table_prefix.=$wpdb->base_prefix;
$table_prefix.="users";
$table_prefix1.=$wpdb->base_prefix;
$table_prefix1.="posts";
//$comment_counts = (array) $wpdb->get_results("SELECT id, user_login, display_name, user_email, user_url, user_registered FROM  `{$table_prefix}` {$where} ", object);
$get_results="SELECT count(p.post_author) as post1,c.id, c.user_login, c.display_name, c.user_email, c.user_url, c.user_registered FROM {$table_prefix} as c , {$table_prefix1} as p {$where} and p.post_type = 'post' AND p.post_status = 'publish' and c.id=p.post_author GROUP BY p.post_author order by post1 DESC limit {$author_numbers}  ";

$comment_counts = (array) $wpdb->get_results("{$get_results}", object);


?>
<table cellpadding="<?php echo $author_space; ?>" cellspacing="1" style="float:left;">

<?php
$i=0;
$j=$colums;
foreach ( $comment_counts as $count ) {
  $user = get_userdata($count->id);
  if($i==0)
  {
  echo '<tr>';
  }
  
  echo '<td style="width:'.$width.'px;text-align:center;padding-bottom:10px;" valign="top">';
  
 
  $post_count = get_usernumposts($user->ID);

  echo get_avatar( $user->user_email, $size = $author_size);
   if($authorlink=="No")
  {
  $temp=explode(" ",$user->display_name);
  echo  '<br><div style="width:'.$width.'px;text-align:center;align:center">'.$temp[0];
  echo '<br>'.$temp[1].' '.$temp[2];
 
	echo "</div>";
	}
	else
	{
	$temp=explode(" ",$user->display_name);
 

	 $link = sprintf(
		'<a href="%1$s" title="%2$s" style="font-size:12px;"><br><div style="width:'.$width.';text-align:center;align:center">%3$s <br> %4$s %5$s</a></div>',
		get_author_posts_url( $user->ID, $user->user_login ),
		esc_attr( sprintf( __( 'Posts by %s (%s)' ), $user->display_name,get_usernumposts($user->ID) ) ),
		$temp[0],$temp[1],$temp[2]
	);
	echo $link;

	}
  echo '</td>';
  $i++;
  if($i==$j)
  {
  echo '</tr>';
  $j=$j+$colums;
  }
}
?>
</table>
	   <?php
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		//$defaults = array( 'title' => __('Example', 'example'), 'name' => __('John Doe', 'example'), 'sex' => 'male', 'show_sex' => true );
		//$instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'sabir'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
        <?php $video_embed_c = stripslashes(htmlspecialchars($instance['exclude_author'], ENT_QUOTES)); ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'exclude_author' ); ?>"><?php _e('Exclude the user:', 'skyali'); ?></label>
		<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'exclude_author' ); ?>" name="<?php echo $this->get_field_name( 'exclude_author' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['exclude_author'] ), ENT_QUOTES)); ?></textarea>
        </p>
		 <p>
        <label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php _e('Number of Columns:', 'sabir'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" value="<?php echo $instance['columns']; ?>" style="width:100%;" />
        </p>
         <p>
        <label for="<?php echo $this->get_field_id( 'author_size' ); ?>"><?php _e('Author Gravatar Email Size:', 'sabir'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('author_size'); ?>" name="<?php echo $this->get_field_name('author_size'); ?>" value="<?php echo $instance['author_size']; ?>" style="width:100%;" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'author_numbers' ); ?>"><?php _e('Number of Authors:', 'sabir'); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('author_numbers'); ?>" name="<?php echo $this->get_field_name('author_numbers'); ?>" value="<?php echo $instance['author_numbers']; ?>" style="width:100%;" />
        </p>
         <p>
        <label for="<?php echo $this->get_field_id( 'author_space' ); ?>"><?php _e('Space between each author:', 'sabir'); ?>eg:10,15,20</label>
        <input type="text" id="<?php echo $this->get_field_id('author_space'); ?>" name="<?php echo $this->get_field_name('author_space'); ?>" value="<?php echo $instance['author_space']; ?>" style="width:100%;" />
        </p>
		
		
	<?php
	}
}

?>