<?php

/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

/**
 * Labels Model Class
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Label {

    /**
     * Labels container
     * 
     * @var array
     * @access public
     */
    public static $labels = array();

    /**
     * Initialize Labels with current language
     * 
     * @return void
     */
    public static function initLabels() {
        self::$labels['LABEL_1'] = __('Advanced Access Manager', 'aam');
        self::$labels['LABEL_2'] = __('Additional features available', 'aam');
        self::$labels['LABEL_3'] = __('Alert', 'aam');
        self::$labels['LABEL_4'] = __('You have a JavaScript Error on a page', 'aam');
        self::$labels['LABEL_5'] = __('Please read <a href="http://wordpress.org/extend/plugins/advanced-access-manager/faq/" target="_blank">FAQ</a> for more information', 'aam');
        self::$labels['LABEL_6'] = __('Options updated successfully', 'aam');
        self::$labels['LABEL_7'] = __('Main Menu', 'aam');
        self::$labels['LABEL_8'] = __('Metaboxes & Widgets', 'aam');
        self::$labels['LABEL_9'] = __('Capabilities', 'aam');
        self::$labels['LABEL_10'] = __('Posts & Pages', 'aam');
        self::$labels['LABEL_11'] = __('To Reorganize menu just Drag and Drop Items on the List and click Save Order', 'aam');
        self::$labels['LABEL_12'] = __('Reorganize', 'aam');
        self::$labels['LABEL_13'] = __('Whole Branch', 'aam');
        self::$labels['LABEL_14'] = __('To initialize list of metaboxes manually, copy and paste the URL to edit screen page into text field and click "Initiate URL". List of all new metaboxes will be added automatically.', 'aam');
        self::$labels['LABEL_15'] = __('Enter Correct URL', 'aam');
        self::$labels['LABEL_16'] = __('Initiate URL', 'aam');
        self::$labels['LABEL_17'] = __('Refresh List', 'aam');
        self::$labels['LABEL_18'] = __('ID', 'aam');
        self::$labels['LABEL_19'] = __('Priority', 'aam');
        self::$labels['LABEL_20'] = __('Position', 'aam');
        self::$labels['LABEL_21'] = __('Restrict', 'aam');
        self::$labels['LABEL_22'] = __('List of Metaboxes is empty or not initialized.', 'aam');
        self::$labels['LABEL_23'] = __('Initiate the List', 'aam');
        self::$labels['LABEL_24'] = __('Delete Capability', 'aam');
        self::$labels['LABEL_25'] = __('Click for Tooltip', 'aam');
        self::$labels['LABEL_26'] = __('Add New Capability', 'aam');
        self::$labels['LABEL_27'] = __('Add New Cap', 'aam');
        self::$labels['LABEL_28'] = __('Give Administrator\'s List of Capabilities', 'aam');
        self::$labels['LABEL_29'] = __('Administrator', 'aam');
        self::$labels['LABEL_30'] = __('Give Editor\'s List of Capabilities', 'aam');
        self::$labels['LABEL_31'] = __('Editor', 'aam');
        self::$labels['LABEL_32'] = __('Give Author\'s List of Capabilities', 'aam');
        self::$labels['LABEL_33'] = __('Author', 'aam');
        self::$labels['LABEL_34'] = __('Give Contributor\'s List of Capabilities', 'aam');
        self::$labels['LABEL_35'] = __('Contributor', 'aam');
        self::$labels['LABEL_36'] = __('Give Subscriber\'s List of Capabilities', 'aam');
        self::$labels['LABEL_37'] = __('Subscriber', 'aam');
        self::$labels['LABEL_38'] = __('Clear all Capabilities', 'aam');
        self::$labels['LABEL_39'] = __('Clear All', 'aam');
        self::$labels['LABEL_40'] = __('Collapse All', 'aam');
        self::$labels['LABEL_41'] = __('Expand All', 'aam');
        self::$labels['LABEL_42'] = __('Error during saving', 'aam');
        self::$labels['LABEL_43'] = __('Title', 'aam');
        self::$labels['LABEL_44'] = __('This is the title of selected Post or Page', 'aam');
        self::$labels['LABEL_45'] = __('Selected item\'s type', 'aam');
        self::$labels['LABEL_46'] = __('Type', 'aam');
        self::$labels['LABEL_47'] = __('Status', 'aam');
        self::$labels['LABEL_48'] = __('Current Post or Page Status', 'aam');
        self::$labels['LABEL_49'] = __('Visibility', 'aam');
        self::$labels['LABEL_50'] = __('Visibility of current Post or Page', 'aam');
        self::$labels['LABEL_51'] = __('Restrict Admin', 'aam');
        self::$labels['LABEL_52'] = __('Restrict access to current Post or Page on BackEnd', 'aam');
        self::$labels['LABEL_53'] = __('Restrict Front', 'aam');
        self::$labels['LABEL_54'] = __('Restrict access to current Post or Page on FrontEnd', 'aam');
        self::$labels['LABEL_55'] = __('Exclude Page', 'aam');
        self::$labels['LABEL_56'] = __('Just Exclude page from navitation but do not restrict access', 'aam');
        self::$labels['LABEL_57'] = __('Expire', 'aam');
        self::$labels['LABEL_58'] = __('If Restric is checked then restrict access to current Post or Page until the picked date. If Restrict is unchecked then allow access to Page or Post until the picked date', 'aam');
        self::$labels['LABEL_59'] = __('Update info only for current role', 'aam');
        self::$labels['LABEL_60'] = __('Update Current', 'aam');
        self::$labels['LABEL_61'] = __('Update info for all role', 'aam');
        self::$labels['LABEL_62'] = __('Update All', 'aam');
        self::$labels['LABEL_63'] = __('Category', 'aam');
        self::$labels['LABEL_64'] = __('Category title', 'aam');
        self::$labels['LABEL_65'] = __('This is just a type of post\'s taxonomy. Always Category', 'aam');
        self::$labels['LABEL_66'] = __('Posts', 'aam');
        self::$labels['LABEL_67'] = __('Number of posts current category has', 'aam');
        self::$labels['LABEL_68'] = __('Restrict access to current category and for all Sub Categories on BackEnd. Also it\'ll restrict access to all posts under these categories', 'aam');
        self::$labels['LABEL_69'] = __('Restrict access to current category and for all Sub Categories on FrontEnd. Also it\'ll restrict access to all posts under these categories', 'aam');
        self::$labels['LABEL_70'] = __('If Restric is checked then restrict access to current Category and all Sub Categories, and Posts since the picked date. If Restrict is unchecked then allow access to current Category and all Sub Categories, and Posts since the picked date', 'aam');
        self::$labels['LABEL_71'] = __('Select a proper Page, Post or Category.', 'aam');
        self::$labels['LABEL_72'] = __('Click to toggle', 'aam');
        self::$labels['LABEL_73'] = __('General', 'aam');
        self::$labels['LABEL_74'] = __('Current Role', 'aam');
        self::$labels['LABEL_75'] = __('Change', 'aam');
        self::$labels['LABEL_76'] = __('OK', 'aam');
        self::$labels['LABEL_77'] = __('Cancel', 'aam');
        self::$labels['LABEL_78'] = __('Restore Default Setting for Current Role', 'aam');
        self::$labels['LABEL_79'] = __('Export Configurations', 'aam');
        self::$labels['LABEL_81'] = __('Saving...', 'aam');
        self::$labels['LABEL_82'] = __('Save', 'aam');
        self::$labels['LABEL_83'] = __('Role Manager', 'aam');
        self::$labels['LABEL_84'] = __('Role List', 'aam');
        self::$labels['LABEL_85'] = __('Add New', 'aam');
        self::$labels['LABEL_86'] = __('Delete', 'aam');
        self::$labels['LABEL_87'] = __('Enter New Role', 'aam');
        self::$labels['LABEL_88'] = __('Add', 'aam');
        self::$labels['LABEL_89'] = __('New Role Created successfully', 'aam');
        self::$labels['LABEL_90'] = __('Error', 'aam');
        self::$labels['LABEL_91'] = __('Role can\'t be created', 'aam');
        self::$labels['LABEL_92'] = __('Support', 'aam');
        self::$labels['LABEL_93'] = __('Send an Email', 'aam');
        self::$labels['LABEL_94'] = __('Find on Facebook', 'aam');
        self::$labels['LABEL_95'] = __('Follow on Twitter', 'aam');
        self::$labels['LABEL_96'] = __('LinkedIn', 'aam');
        self::$labels['LABEL_97'] = __('Delete Role?', 'aam');
        self::$labels['LABEL_98'] = __('Please confirm deleting Role', 'aam');
        self::$labels['LABEL_99'] = __('Save Menu Order?', 'aam');
        self::$labels['LABEL_100'] = __('Would you like to save Menu Order <b>ONLY</b> for Role', 'aam');
        self::$labels['LABEL_101'] = __('Delete Capability?', 'aam');
        self::$labels['LABEL_102'] = __('Please confirm deleting Capability', 'aam');
        self::$labels['LABEL_103'] = __('Restore Default Role Settings?', 'aam');
        self::$labels['LABEL_104'] = __('All current settings will be lost. Are you sure?', 'aam');
        self::$labels['LABEL_105'] = __('Apply Setting for ALL User Roles?', 'aam');
        self::$labels['LABEL_106'] = __('Do you really want to apply these settings for <b>ALL</b> User Roles?', 'aam');
        self::$labels['LABEL_107'] = __('Do not show me this message again.', 'aam');
        self::$labels['LABEL_108'] = __('Leave Without Saving?', 'aam');
        self::$labels['LABEL_109'] = __('Some changed detected. Are you sure that you want to leave without saving?', 'aam');
        self::$labels['LABEL_110'] = __('Add New Capability', 'aam');
        self::$labels['LABEL_111'] = __('Additional Features Available', 'aam');
        self::$labels['LABEL_112'] = __('Additional features detected to extend Advanced Access Manager functionality.', 'aam');
        self::$labels['LABEL_113'] = __('Read More...', 'aam');
        self::$labels['LABEL_114'] = __('Upgrade functionality', 'aam');
        self::$labels['LABEL_115'] = __('Important Message', 'aam');
        self::$labels['LABEL_116'] = __('Do you want to create a <b>Super Admin</b> Role to get access to ALL features?', 'aam');
        self::$labels['LABEL_117'] = __('Import Configurations', 'aam');
        self::$labels['LABEL_118'] = __('WARNING', 'aam');
        self::$labels['LABEL_122'] = __('Advanced Access Manager requires WordPress 3.2 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Update now!</a>', 'aam');
        self::$labels['LABEL_123'] = __('Advanced Access Manager requires PHP 5.1.2 or newer', 'aam');
        self::$labels['LABEL_124'] = __('Empty Capability', 'aam');
        self::$labels['LABEL_125'] = __('Current Capability can not be deleted', 'aam');
        self::$labels['LABEL_126'] = __('Super Admin', 'aam');
        self::$labels['LABEL_127'] = __('Unauthorized Action', 'aam');
        self::$labels['LABEL_128'] = __('Options List', 'aam');
        self::$labels['LABEL_129'] = __('Reorganize', 'aam');
        self::$labels['LABEL_130'] = __('Yes', 'aam');
        self::$labels['LABEL_131'] = __('Apply for All', 'aam');
        self::$labels['LABEL_132'] = __('Add Capability', 'aam');
        self::$labels['LABEL_133'] = __('Error Appears during Metabox initialization!', 'aam');
        self::$labels['LABEL_134'] = __('Delete Role', 'aam');
        self::$labels['LABEL_135'] = __('Restore', 'aam');
        self::$labels['LABEL_136'] = __('Current Role can not be restored!', 'aam');
        self::$labels['LABEL_137'] = __('Apply All', 'aam');
        self::$labels['LABEL_138'] = __('Error during information grabbing!', 'aam');
        self::$labels['LABEL_139'] = __('Import', 'aam');
        self::$labels['LABEL_140'] = __('Error during importing', 'aam');
        self::$labels['LABEL_141'] = __('Create', 'aam');
        self::$labels['LABEL_142'] = __('Do not Create', 'aam');
        self::$labels['LABEL_143'] = __('Change Role', 'aam');
        self::$labels['LABEL_144'] = __('Current Site', 'aam');
        self::$labels['LABEL_145'] = __('cURL library returned empty result. Contact your system administrator to fix this issue.', 'aam');
        self::$labels['LABEL_146'] = __('You are not an active user for current blog. Please click <a href="#" id="add-user-toblog">here</a> to add youself to current blog as Administrator', 'aam');
        self::$labels['LABEL_147'] = __('<p><span style="color: #FF0000;">PLEASE READ THIS!</span> You entered <b>Advanced Access Manager</b> Option Page.</p>
        <p>This graphic interface allows you to control access to your WordPress Blog. <b>DO NOT</b> try to change settings if you are not sure what you are doing! If you have problems or questions, or just found something weird in a system\'s behavior, <b>PLEASE</b> take a look to <a href="http://wordpress.org/extend/plugins/advanced-access-manager/faq/" target="_blank">FAQ</a> section before asking for support.</p>
        <p>For your safety, after you press <b>OK</b> button, Super Admin Role will be created specifically for your user.</p>
        <p>Users with already defined Super Admin Role will be deprived of it and replaced with Administrator Role</p>
        <p>If you have Multi-Site Setup, you will see the same message again for each new Blog you entered or created.</p>', 'aam');
        self::$labels['LABEL_148'] = __('Apply to ALL Blogs', 'aam');
        self::$labels['LABEL_149'] = __('You are going to apply current Blog\'s Settings to ALL Blogs in a WordPress Multisite setup. Please Notice that it will apply only <i>Main Menu</i>, <i>Metaboxes & Widgets</i> and <i>Capabilities</i> Settings. <i>Pages & Posts</i> Settings will be skip in fact of possible IDs mismatch.', 'aam');
        self::$labels['LABEL_150'] = __('Action completed successfully', 'aam');
        self::$labels['LABEL_151'] = __('Action failed', 'aam');
        self::$labels['LABEL_152'] = __('Settings applied successfully to ALL Blogs', 'aam');
        self::$labels['LABEL_153'] = __('empty', 'aam');
        self::$labels['LABEL_154'] = __('Administrator added Successfully', 'aam');
        self::$labels['LABEL_155'] = __('Failed to add new Administrator', 'aam');
        self::$labels['LABEL_156'] = __('You do not have installed <a href="http://whimba.org/add-ons" target="_blank">AAM MSAR Extend</a> Add-on. Settings Applies only for first ' . WPACCESS_APPLY_LIMIT . ' Blogs.', 'aam');
        self::$labels['LABEL_80'] = __('Config Press', 'aam');
        self::$labels['LABEL_119'] = __('Current User', 'aam');
        self::$labels['LABEL_120'] = __('All Users', 'aam');
        self::$labels['LABEL_121'] = __('Delete current capability', 'aam');
        self::$labels['LABEL_157'] = __('Access Config is global configuration for ALL Roles and Users in current blog. For more information please follow the <a href="http://whimba.org/support" target="_blank">support link</a>.', 'aam');
        self::$labels['upgrade_restriction'] = __('Install <a href="http://whimba.org/add-ons" target="_blank">Extend Restriction</a> to be able to set more then 5 restrictions for one Role', 'aam');
        self::$labels['restrict_message'] = __('<p>You do not have sufficient permissions to perform this action</p>', 'aam');

        self::initCapabilityDescriptions();
    }

    /**
     * Init Capability Descriptions
     * 
     * @todo Rewrite Caps Description
     */
    public static function initCapabilityDescriptions() {

        self::$labels = array_merge(self::$labels, array(
            'switch_themes' => __('Since 2.0
				Allows access to Administration Panel options:
					- Appearance
					- Appearance > Themes', 'aam'),
            'edit_themes' => __('Since 2.0
				Allows access to Appearance > Theme Editor to edit theme files.', 'aam'),
            'edit_theme_options' => __('Since 3.0
				Allows access to Administration Panel options:
					- Appearance > Background
					- Appearance > Header
					- Appearance > Menus
					- Appearance > Widgets
					- Also allows access to Theme Options pages if they are included in the Theme', 'aam'),
            'edit_published_posts' => __('Since 2.0
				User can edit their published posts. This capability is off by default.
				The core checks the capability edit_posts, but on demand this check is changed to edit_published_posts.
				If you do not want a user to be able edit his published posts, remove this capability.', 'aam'),
            'edit_others_posts' => __('Since 2.0
				Allows access to Administration Panel options:
					- Manage > Comments (Lets user delete and edit every comment, see edit_posts above)
					- user can edit other posts through function get_others_drafts()
					- user can see other images in inline-uploading', 'aam'),
            'manage_options' => __('Since 2.0
				Allows access to Administration Panel options: 
                    - Settings > General
                    - Settings > Writing
                    - Settings > Writing
                    - Settings > Reading
                    - Settings > Discussion
                    - Settings > Permalinks
                    - Settings > Miscellaneous', 'aam'),
            'install_themes' => __('Since 2.0
				Allows access to Administration Panel option:
					- Appearance > Add New Themes', 'aam'),
            'activate_plugins' => __('Since 2.0
				Allows access to Administration Panel option:  
					- Plugins', 'aam'),
            'edit_plugins' => __('Since 2.0
				Allows access to Administration Panel option:  
					- Plugins > Plugin Editor', 'aam'),
            'install_plugins' => __('Since 2.0
				Allows access to Administration Panel option:  
					- Plugins > Add New', 'aam'),
            'edit_users' => __('Since 2.0
				Allows access to Administration Panel option:  
					- Users', 'aam'),
            'edit_files' => __('Since 2.0
				Note: No longer used.', 'aam'),
            'moderate_comments' => __('Since 2.0
				Allows users to moderate comments from the Comments SubPanel (although a user needs the edit_posts Capability in order to access this)', 'aam'),
            'manage_categories' => __('Since 2.0
				Allows access to Administration Panel options:  
					- Posts > Categories
					- Links > Categories', 'aam'),
            'manage_links' => __('Since 2.0
				Allows access to Administration Panel options:  
					- Links
					- Links > Add New', 'aam'),
            'upload_files' => __('Since 2.0
				Allows access to Administration Panel options:  
					- Media
					- Media > Add New', 'aam'),
            'import' => __('Since 2.0
				Allows access to Administration Panel options:  
					- Tools > Import
					- Tools > Export', 'aam'),
            'unfiltered_html' => __('Since 2.0
				Allows user to post HTML markup or even JavaScript code in pages, posts, and comments.
				Note: Enabling this option for untrusted users may result in their posting malicious or poorly formatted code.', 'aam'),
            'edit_posts' => __('Since 2.0
				Allows access to Administration Panel options:  
					- Posts
					- Posts > Add New
					- Comments
					- Comments > Awaiting Moderation', 'aam'),
            'publish_posts' => __('Since 2.0
				See and use the "publish" button when editing their post (otherwise they can only save drafts)
				Can use XML-RPC to publish (otherwise they get a "Sorry, you can not post on this weblog or category.")', 'aam'),
            'edit_pages' => __('Since 2.0
				Allows access to Administration Panel options:  
					- Pages
					- Pages > Add New', 'aam'),
            'read' => __('Since 2.0
				Allows access to Administration Panel options:
					- Dashboard
					- Users > Your Profile
				Used nowhere in the core code except the menu.php', 'aam'),
            'edit_others_pages' => __('Since 2.1', 'aam'),
            'edit_published_pages' => __('Since 2.1', 'aam'),
            'edit_published_pages_2' => __('Since 2.1', 'aam'),
            'delete_pages' => __('Since 2.1', 'aam'),
            'delete_others_pages' => __('Since 2.1', 'aam'),
            'delete_published_pages' => __('Since 2.1', 'aam'),
            'delete_posts' => __('Since 2.1', 'aam'),
            'delete_others_posts' => __('Since 2.1', 'aam'),
            'delete_published_posts' => __('Since 2.1', 'aam'),
            'delete_private_posts' => __('Since 2.1', 'aam'),
            'edit_private_posts' => __('Since 2.1', 'aam'),
            'read_private_posts' => __('Since 2.1', 'aam'),
            'delete_private_pages' => __('Since 2.1', 'aam'),
            'edit_private_pages' => __('Since 2.1', 'aam'),
            'read_private_pages' => __('Since 2.1', 'aam'),
            'delete_users' => __('Since 2.1', 'aam'),
            'create_users' => __('Since 2.1', 'aam'),
            'unfiltered_upload' => __('Since 2.3', 'aam'),
            'edit_dashboard' => __('Since 2.5', 'aam'),
            'update_plugins' => __('Since 2.6', 'aam'),
            'delete_plugins' => __('Since 2.6', 'aam'),
            'update_core' => __('Since 3.0', 'aam'),
            'list_users' => __('Since 3.0', 'aam'),
            'remove_users' => __('Since 3.0', 'aam'),
            'add_users' => __('Since 3.0', 'aam'),
            'promote_users' => __('Since 3.0', 'aam'),
            'delete_themes' => __('Since 3.0', 'aam'),
            'export' => __('Since 3.0', 'aam'),
            'edit_comment' => __('Since 3.1', 'aam'),
            'manage_sites' => __('Since 3.0
				Multi-site only
				Allows access to Network Sites menu
				Allows user to add, edit, delete, archive, unarchive, activate, deactivate, spam and unspam new site/blog in the network', 'aam'),
            'manage_network_users' => __('Since 3.0
				Multi-site only
				Allows access to Network Users menu', 'aam'),
            'manage_network_themes' => __('Since 3.0
				Multi-site only
				Allows access to Network Themes menu', 'aam'),
            'manage_network_options' => __('Since 3.0
				Multi-site only
				Allows access to Network Options menu', 'aam'),
            'level_0' => __('User Level 0 converts to Subscriber', 'aam'),
            'level_1' => __('User Level 1 converts to Contributor', 'aam'),
            'level_2' => __('User Level 2 converts to Author', 'aam'),
            'level_3' => __('User Level 3 converts to Author', 'aam'),
            'level_4' => __('User Level 4 converts to Author', 'aam'),
            'level_5' => __('User Level 5 converts to Editor', 'aam'),
            'level_6' => __('User Level 6 converts to Editor', 'aam'),
            'level_7' => __('User Level 7 converts to Editor', 'aam'),
            'level_8' => __('User Level 8 converts to Administrator', 'aam'),
            'level_9' => __('User Level 9 converts to Administrator', 'aam'),
            'level_10' => __('User Level 10 converts to Administrator', 'aam'),
            'publish_pages' => __('Description does not exist', 'aam'),
            'administrator' => __('Description does not exist', 'aam'),
            'update_themes' => __('Description does not exist', 'aam'))
        );
    }

    /**
     * Get label from store
     * 
     * @param string $label
     * @return string|bool
     */
    public static function get($label) {

        return (isset(self::$labels[$label]) ? self::$labels[$label] : FALSE);
    }

}

?>
