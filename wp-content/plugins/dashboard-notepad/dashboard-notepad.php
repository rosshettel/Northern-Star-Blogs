<?php
/*
Plugin Name: Dashboard Notepad
Plugin URI: http://sillybean.net/code/wordpress/dashboard-notepad/
Description: The very simplest of notepads for your Dashboard. Based on <a href="http://www.contutto.com/">Alex G&uuml;nsche's</a> Headache With Pictures. You can use the <code>&lt;?php dashboard_notes(); ?&gt;</code> template tag or the <code>[dashboard_notes]</code> shortcode to display your notes publicly.
Author: Stephanie Leary
Version: 1.39
Author URI: http://sillybean.net/
Text Domain: dashboard-notepad
*/

/*
	Dashboard Notepad Copyright (C) 2009  Stephanie Leary  (email : steph@sillybean.net)
	
	Based on:
	Headache With Pictures -- WP plugin to quickly note things on the dashboard.
	Copyright (C) 2006 Alex G&uuml;nsche <ag@zirona.com>
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation in the Version 2.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/

function dashboard_notepad_widget() {
	$options = dashboard_notepad_widget_options();
	if (!empty($_POST['dashboard_notepad_submit']) ) {			
			if ( current_user_can('unfiltered_html') )
				$options['notes'] =  stripslashes($_POST['dashboard_notepad']);
			else
				$options['notes'] = stripslashes( wp_filter_post_kses( $_POST['dashboard_notepad'] ) );
			update_option('dashboard_notepad', $options);
	}
	$form = '<form method="post" action="'.admin_url().'">';
	$form .= '<textarea id="dashboard_notepad" name="dashboard_notepad" rows="'.(int)$options['notepad_size'].'"';
	if (!current_user_can('edit_dashboard_notes')) $form.= ' readonly="readonly"';
	$form .= '>'. esc_textarea($options['notes']).'</textarea>';
	if (current_user_can('edit_dashboard_notes')) $form .= '<p><input type="submit" value="' . __('Save Notes', 'dashboard-notepad') . '" class="button widget-control-save"></p> 
		<input type="hidden" name="dashboard_notepad_submit" value="true" />';
	$form .= '</form>';
	echo $form;
}

function dashboard_notepad_css() {
	?>
	<style type="text/css">
		textarea#dashboard_notepad { width: 95%; background: #fcfcfc; }
		div.dashboard-role-column { float: left; width: 47%; margin-left: 2%; }
		p.dashboard-note-clear { clear: both; margin-top: 1em; }
	</style>
	<?php
}
 
function dashboard_notepad_widget_setup() {
	$options = dashboard_notepad_widget_options();
	if (!is_array($options)) $options = array('title' => __('Notepad', 'dashboard-notepad'));
        if (current_user_can('edit_dashboard') || (current_user_can('read_dashboard_notes') || current_user_can('edit_dashboard_notes'))) {
		wp_add_dashboard_widget( 'dashboard_notepad_widget_id', $options['notepad_title'], 'dashboard_notepad_widget', 'dashboard_notepad_widget_control');
	}
}

add_action("admin_head-index.php", 'dashboard_notepad_css'); 			 // add styles to Dashboard only
add_action('wp_dashboard_setup', 'dashboard_notepad_widget_setup');

function dashboard_notepad_widget_options() {
	$defaults = array( 'notes' => __('Enter here whatever is on your mind.', 'dashboard-notepad'), 
		'edit_dashboard_notes' => array('administrator','editor'), 
		'read_dashboard_notes' => array('administrator','editor','contributor','author','subscriber'), 
		'notepad_title' => __('Notepad', 'dashboard-notepad'), 
		'notepad_size' => 8,
		'autop' => '');
	$options = get_option('dashboard_notepad');
	if (!is_array($options)) $options = array();
	$options = array_merge( $defaults, $options );
	// upgrade from old options
	if (isset($options['can_read'])) {
		switch ($options['can_read']) {
			case 'edit_dashboard': $options['read_dashboard_notes'] = array_merge($options['read_dashboard_notes'], array('administrator')); break;
			case 'edit_pages': $options['read_dashboard_notes'] = array_merge($options['read_dashboard_notes'], array('editor')); break;
			case 'publish_posts': $options['read_dashboard_notes'] = array_merge($options['read_dashboard_notes'], array('author')); break;
			case 'edit_posts': $options['read_dashboard_notes'] = array_merge($options['read_dashboard_notes'], array('contributor')); break;
			case 'read': $options['read_dashboard_notes'] = array_merge($options['read_dashboard_notes'], array('subscriber')); break;
			case 'guest': $options['read_dashboard_notes'] = array_merge($options['read_dashboard_notes'], array('guest')); break;
		}
		unset($options['can_read']);
	}
	if (isset($options['can_edit'])) {
		switch ($options['can_edit']) {
			case 'edit_dashboard': $options['edit_dashboard_notes'] = array_merge($options['edit_dashboard_notes'], array('administrator')); break;
			case 'edit_pages': $options['edit_dashboard_notes'] = array_merge($options['edit_dashboard_notes'], array('editor')); break;
			case 'publish_posts': $options['edit_dashboard_notes'] = array_merge($options['edit_dashboard_notes'], array('author')); break;
			case 'edit_posts': $options['edit_dashboard_notes'] = array_merge($options['edit_dashboard_notes'], array('contributor')); break;
			case 'read': $options['edit_dashboard_notes'] = array_merge($options['edit_dashboard_notes'], array('subscriber')); break;
		}
		unset($options['can_edit']);
	}
	return $options;
}

function dashboard_notepad_widget_control() {
	$options = dashboard_notepad_widget_options();
	$myroles = get_editable_roles();
	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'dashboard_notepad_widget_id' == $_POST['widget_id'] ) {
		if ( isset($_POST['edit_dashboard_notes']) ) {
			$options['edit_dashboard_notes'] = $_POST['edit_dashboard_notes'];
			foreach ( $myroles as $name => $role ) {
				$edit = get_role( $name );
				if (in_array($name, $options['edit_dashboard_notes']))
					$edit->add_cap( 'edit_dashboard_notes' );
				elseif ($edit->has_cap( 'edit_dashboard_notes' ))
					$edit->remove_cap( 'edit_dashboard_notes' );
			}
		}
		if ( isset($_POST['read_dashboard_notes']) ) {
			$options['read_dashboard_notes'] = $_POST['read_dashboard_notes'];
			foreach ( $myroles as $name => $role ) {
				$read = get_role( $name );
				if (in_array($name, $options['read_dashboard_notes']))
					$read->add_cap( 'read_dashboard_notes' );
				elseif ($read->has_cap( 'read_dashboard_notes' ))
					$read->remove_cap( 'read_dashboard_notes' );
			}
		}
		if ( isset($_POST['notepad_title']) )
			$options['notepad_title'] = esc_attr($_POST['notepad_title']);
		if ( isset($_POST['notepad_size']) )
			$options['notepad_size'] = (int)$_POST['notepad_size'];
		if (in_array($_POST['autop'], array('yes', 'no')))
			$options['autop'] = $_POST['autop'];
		else $options['autop'] = 'no';
		update_option( 'dashboard_notepad', $options );
	}
?>
	<p><label for="notepad_title"><?php _e( 'Widget title:' , 'dashboard-notepad'); ?></label>
		<input type="text" id="notepad_title" name="notepad_title" value="<?php esc_attr_e(  $options['notepad_title'] ); ?>" /> &nbsp;&nbsp;&nbsp;
	<label for="notepad_size"><?php _e( 'Widget height:' , 'dashboard-notepad'); ?></label>
		<input type="text" id="notepad_size" name="notepad_size" value="<?php esc_attr_e( $options['notepad_size'] ); ?>" size="4" />	<?php _e( 'lines' , 'dashboard-notepad'); ?>
	</p>
	<div class="dashboard-role-column">
    <p><?php _e( 'Users in these roles can <strong>edit</strong> the notes:' , 'dashboard-notepad'); ?></p>
		<ul>
			<?php foreach ($myroles as $slug => $role) { ?>
				<li><label><input type="checkbox" name="edit_dashboard_notes[]" value="<?php esc_attr_e($slug); ?>" <?php if (in_array($slug, $options['edit_dashboard_notes'])) echo 'checked="checked"'; ?> /> <?php esc_html_e($role['name']); ?><label></li>
			<?php } ?>
        </ul>
	</div>
	<div class="dashboard-role-column">
    <p><?php _e( 'Users in these roles can <strong>read</strong> the notes:' , 'dashboard-notepad'); ?></p>
		<ul>
			<?php foreach ($myroles as $slug => $role) { ?>
				<li><label><input type="checkbox" name="read_dashboard_notes[]" value="<?php esc_attr_e($slug); ?>" <?php if (in_array($slug, $options['read_dashboard_notes'])) echo 'checked="checked"'; ?> /> <?php esc_html_e($role['name']); ?><label></li>
			<?php } ?>
            <li><label><input type="checkbox" name="read_dashboard_notes[]" value="guest" <?php if (in_array('guest', $options['read_dashboard_notes'])) echo 'checked="checked"'; ?> /> <?php _e('The Public', 'dashboard-notepad'); ?><label></li>
		</ul>
    </div>
	<p class="dashboard-note-clear">
    <label><input id="autop" name="autop" type="checkbox" value="yes" <?php checked('yes', $options['autop']); ?> /> 
		<?php _e('Automatically add paragraphs when displaying the notes on the front end.', 'dashboard-notepad'); ?></label>
    </p>
<?php
}

// show dashboard notes on front end
function dashboard_notes() {
	$options = dashboard_notepad_widget_options();
	if (current_user_can('read_dashboard_notes') || in_array('guest', $options['read_dashboard_notes'])) {
		echo '<div id="dashboard-notes">';
		if ($options['autop'] == 'yes')
		  echo wpautop(wp_kses_post($options['notes']));
		else echo wp_kses_post($options['notes']);
		echo '</div>';
	}
}

add_shortcode('dashboard_notes', 'dashboard_notes');

// Members integration

if ( function_exists( 'members_plugin_init' ) ) {
	add_filter( 'edit_dashboard_notes', 'dashboard_notepad_edit_notes' );
	add_filter( 'read_dashboard_notes', 'dashboard_notepad_read_notes' );
}
	
if ( function_exists( 'members_get_capabilities' ) )
	add_filter( 'members_get_capabilities', 'dashboard_notepad_extra_caps' );

function dashboard_notepad_extra_caps( $caps ) {
	$caps[] = 'edit_dashboard_notes';
	$caps[] = 'read_dashboard_notes';
	return $caps;
}

function dashboard_notepad_edit_notes( $cap ) {
	return 'edit_dashboard_notes';
}

function dashboard_notepad_read_notes( $cap ) {
	return 'read_dashboard_notes';
}

// i18n
$plugin_dir = basename(dirname(__FILE__)). '/languages';
load_plugin_textdomain( 'DashboardNotepad', WP_PLUGIN_DIR.'/'.$plugin_dir, $plugin_dir );
?>