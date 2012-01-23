<?php

/*
  Plugin Name: Advanced Access Manager
  Description: Manage Access to WordPress Backend and Frontend.
  Version: 1.5.7
  Author: Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
  Author URI: http://www.whimba.org
 */

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


/*
 * =============================================================================
 *                        ALL DEVELOPERS NOTIFICATION
 *                        ===========================
 * If you read this message it means you are interested in code and can be treated
 * as a developer.
 * I'm not recommending for current version of plugin, do patches or add-ons.
 * Version 2.0 will be totally different and will follow MVC patterns.
 * There are some filters already implemented in this plugin so I'll leave them
 * in latest version for compatibility reasons.
 * If you have any questions of want to participate in this project, contact me
 * via e-mail whimba@gmail.com 
 * =============================================================================
 */

require_once('mvb_config.php');

/**
 * Main Plugin Class
 * 
 * Responsible for initialization and handling user requests to Advanced Access
 * Manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_WPAccess {

    /**
     * Initialize all necessary vars and hooks
     * 
     * @global object $GLOBALS['post']
     * @return void 
     */
    public function __construct() {
        global $post;

        if (is_admin()) {
            //init labels
            mvb_Model_Label::initLabels();

            if (isset($_GET['page']) && ($_GET['page'] == 'wp_access')) {
                add_action('admin_print_scripts', array($this, 'admin_print_scripts'));
                add_action('admin_print_styles', array($this, 'admin_print_styles'));
            }

            if (mvb_Model_API::isNetworkPanel()) {
                add_action('network_admin_menu', array($this, 'admin_menu'), 999);
            } else {
                add_action('admin_menu', array($this, 'admin_menu'), 999);
            }

            add_action('admin_action_render_optionlist', array($this, 'render_optionlist'));

            //Add Capabilities WP core forgot to
            add_filter('map_meta_cap', array($this, 'map_meta_cap'), 10, 4);

            //ajax
            add_action('wp_ajax_mvbam', array($this, 'ajax'));

            add_action("do_meta_boxes", array($this, 'metaboxes'), 999, 3);

            //roles
            add_filter('editable_roles', array($this, 'editable_roles'), 999);
        } else {
            add_action('wp_before_admin_bar_render', array($this, 'wp_before_admin_bar_render'));
            add_action('wp', array($this, 'wp_front'));
            add_filter('get_pages', array($this, 'get_pages'));
        }

        if (!mvb_Model_API::isSuperAdmin()) {
            add_filter('get_terms', array($this, 'get_terms'), 10, 3);
            add_action('pre_get_posts', array($this, 'pre_get_posts'));
        }

        //Main Hook, used to check if user is authorized to do an action
        //Executes after WordPress environment loaded and configured
        add_action('wp_loaded', array($this, 'check'), 999);
    }

    // ===============================================================
    // ********************* PUBLIC METHODS **************************
    // ===============================================================

    /**
     *
     * @param type $role 
     */
    public function getUserList($role) {

        $args = array(
            'number' => '',
            'blog_id' => mvb_Model_API::getCurrentBlog()->getID(),
            'role' => $role,
            'fields' => 'all',
            'orderby' => 'user_nicename',
            'order' => 'ASC'
        );

        // Query the user IDs for this page
        $wp_user_search = new WP_User_Query($args);

        return $wp_user_search->get_results();
    }

    /**
     * Filter editible roles
     * 
     * Get the highest curent User's Level (from 1 to 10) and filter all User
     * Roles which have higher Level. This is used for promotion feature
     * In fact that Administrator Role has the higherst 10th Level, this function
     * introduces the virtual 11th Level for Super Admin
     * 
     * @param array $roles
     * @return array Filtered Role List
     */
    public function editable_roles($roles) {

        if (isset($roles[WPACCESS_SADMIN_ROLE])) { //super admin is level 11
            unset($roles[WPACCESS_SADMIN_ROLE]);
        }

        if (isset($roles['_visitor'])) {
            unset($roles['_visitor']);
        }

        //get user's highest Level
        $caps = mvb_Model_AccessControl::getUserConf()->getUser()->getAllCaps();
        $highest = mvb_Model_Helper::getHighestUserLevel($caps);

        if ($highest < WPACCESS_TOP_LEVEL && is_array($roles)) { //filter roles
            foreach ($roles as $role => $data) {
                if ($highest < mvb_Model_Helper::getHighestUserLevel($data['capabilities'])) {
                    unset($roles[$role]);
                }
            }
        }

        return $roles;
    }

    /**
     * Print Stylesheets to the head of HTML
     * 
     * @return void
     */
    public function admin_print_styles() {

        //core styles
        wp_enqueue_style('dashboard');
        wp_enqueue_style('global');
        wp_enqueue_style('wp-admin');
        //additional styles
        wp_enqueue_style('jquery-ui', WPACCESS_CSS_URL . 'ui/jquery-ui-1.8.16.custom.css');
        wp_enqueue_style('wpaccess-style', WPACCESS_CSS_URL . 'wpaccess_style.css');
        wp_enqueue_style('wpaccess-treeview', WPACCESS_CSS_URL . 'treeview/jquery.treeview.css');
        wp_enqueue_style('codemirror', WPACCESS_CSS_URL . 'codemirror/codemirror.css');
    }

    /**
     * Control Front-End access
     * 
     * @global object $post
     * @global object $wp_query
     * @param object $wp 
     */
    public function wp_front($wp) {

        mvb_Model_AccessControl::checkAccess();
    }

    /*
     * Filter Admin Top Bar
     * 
     */

    public function wp_before_admin_bar_render() {
        global $wp_admin_bar;

        if (is_object($wp_admin_bar) && isset($wp_admin_bar->menu)) {
            $this->filter_top_bar($wp_admin_bar->menu);
        }
    }

    /*
     * Filter Front Menu
     * 
     */

    public function get_pages($pages) {

        if (is_array($pages)) { //filter all pages which are not allowed
            foreach ($pages as $i => $page) {
                if (mvb_Model_AccessControl::checkPostAccess($page)
                        || mvb_Model_AccessControl::checkPageExcluded($page)) {
                    unset($pages[$i]);
                }
            }
        }

        return $pages;
    }

    /**
     *
     * @param type $query 
     */
    public function pre_get_posts($query) {

        $r_posts = array();
        $r_cats = array();
        $rests = mvb_Model_AccessControl::getUserConf()->getRestrictions();
        $t_posts = array();

        if (isset($rests['categories']) && is_array($rests['categories'])) {
            foreach ($rests['categories'] as $id => $data) {
                $exclude = FALSE;
                if (is_admin() && $data['restrict']) {
                    $exclude = TRUE;
                } elseif (!is_admin() && $data['restrict_front']) {
                    $exclude = TRUE;
                }
                if ($exclude) {
                    if (isset($r_cats[$data['taxonomy']])) {
                        $r_cats[$data['taxonomy']]['terms'][] = $id;
                    } else {
                        $r_cats[$data['taxonomy']] = array(
                            'taxonomy' => $data['taxonomy'],
                            'terms' => array($id),
                            'field' => 'term_id',
                            'operator' => 'NOT IN',
                        );
                    }
                }
            }
        }
        if (isset($rests['posts']) && is_array($rests['posts'])) {
            //get list of all posts
            foreach ($rests['posts'] as $id => $data) {
                if (is_admin() && $data['restrict']) {
                    $t_posts[] = $id;
                } elseif (!is_admin() && $data['restrict_front']) {
                    $t_posts[] = $id;
                }
            }
            $t_posts = (is_array($t_posts) ? $t_posts : array());
            $r_posts = array_merge($r_posts, $t_posts);
        }

        $query->query_vars['tax_query'] = $r_cats;
        $query->query_vars['post__not_in'] = $r_posts;
    }

    /**
     *
     * @param type $terms
     * @param type $taxonomies
     * @param type $args
     * @return type 
     */
    public function get_terms($terms, $taxonomies, $args) {

        if (is_array($terms)) {
            foreach ($terms as $i => $term) {
                if (is_object($term)) {
                    if (mvb_Model_AccessControl::checkCategoryAccess($term->term_id)) {
                        unset($terms[$i]);
                    }
                }
            }
        }

        return $terms;
    }

    public function map_meta_cap($caps, $cap, $user_id, $args) {

        switch ($cap) {
            case 'edit_comment':
                $caps[] = 'edit_comment';
                break;

            default:
                break;
        }

        return $caps;
    }

    /*
     * Ajax interface
     */

    public function ajax() {

        check_ajax_referer(WPACCESS_PREFIX . 'ajax');

        $cap = ( mvb_Model_API::isSuperAdmin() ? 'administrator' : 'aam_manage');
        if (current_user_can($cap)) {
            $m = new mvb_Model_Ajax($this);
            $m->process();
        } else {
            die(json_encode(array('status' => 'error', 'result' => 'error')));
        }
    }

    /*
     * Initialize or filter the list of metaboxes
     * 
     * This function is responsible for initializing the list of metaboxes if
     * "grab" parameter with value "metabox" if precent on _GET global array.
     * In other way it filters the list of metaboxes according to user's Role
     * 
     * @param mixed Result of execution get_user_option() in user.php file
     * @param string $option User option name
     * @param int $user Optional. User ID
     * @return mixed
     */

    public function metaboxes($post_type, $priority, $post) {
        global $wp_meta_boxes;

        //get cache. Compatible with version previouse versions
        $cache = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'cache', array());
        //TODO - deprecated
        if (!count($cache)) { //yeap this is new version 
            $cache = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'options', array());
        }
        /*
         * Check if this is a process of initialization the metaboxes.
         * This process starts when admin click on "Refresh List" or "Initialize list"
         * on User->Access Manager page
         */
        if (isset($_GET['grab']) && ($_GET['grab'] == 'metaboxes')) {
            if (!isset($cache['metaboxes'][$post_type])) {
                $cache['metaboxes'][$post_type] = array();
            }

            if (is_array($wp_meta_boxes[$post_type])) {
                /*
                 * Optimize the saving data
                 * Go throught the list of metaboxes and delete callback and args
                 */
                foreach ($wp_meta_boxes[$post_type] as $pos => $levels) {
                    if (is_array($levels)) {
                        foreach ($levels as $level => $boxes) {
                            if (is_array($boxes)) {
                                foreach ($boxes as $box => $data) {
                                    $cache['metaboxes'][$post_type][$pos][$level][$box] = array(
                                        'id' => $data['id'],
                                        'title' => $data['title']
                                    );
                                }
                            }
                        }
                    }
                }
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'cache', $cache);
            }
        } elseif (!mvb_Model_API::isSuperAdmin()) {
            $screen = get_current_screen();
            $m = new mvb_Model_FilterMetabox();
            switch ($screen->id) {
                case 'dashboard':
                    $m->manage('dashboard');
                    break;

                default:
                    $m->manage();
                    break;
            }
        }
    }

    /*
     * Activation hook
     * 
     * Save default user settings
     */

    public function activate() {
        global $wpdb, $wp_version;

        if (version_compare($wp_version, '3.2', '<')) {
            exit(mvb_Model_Label::get('LABEL_122'));
        }

        if (phpversion() < '5.1.2') {
            exit(mvb_Model_Label::get('LABEL_123'));
        }

        $sites = mvb_Model_Helper::getSiteList();

        if (is_array($sites) && count($sites)) {
            foreach ($sites as $site) {
                $c_blog = new mvb_Model_Blog(array(
                            'id' => $site->blog_id,
                            'url' => get_site_url($site->blog_id),
                            'prefix' => $wpdb->get_blog_prefix($site->blog_id)
                        ));
                self::setOptions($c_blog);
            }
        } else {
            self::setOptions();
        }
    }

    /**
     * Set necessary options to DB for current BLOG
     * 
     * @param object $blog
     */
    public static function setOptions($blog = FALSE) {

        $role_list = mvb_Model_API::getBlogOption('user_roles', array(), $blog);
        //save current setting to DB
        mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'original_user_roles', $role_list, $blog);
        //add options
        $m = new mvb_Model_Role();
        $roles = (is_array($m->roles) ? array_keys($m->roles) : array());
        $options = array();
        if (is_array($roles)) {
            foreach ($roles as $role) {
                $options[$role] = (object) array(
                            'menu' => array(),
                            'metaboxes' => array(),
                        //'capabilities' => $m->roles[$role]['capabilities']
                );
            }
        }
        mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'options', $options, $blog);

        //add custom capabilities
        $custom_caps = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'custom_caps', array(), $blog);
        $custom_caps[] = 'edit_comment';
        mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'custom_caps', $custom_caps, $blog);
        $role_list[WPACCESS_ADMIN_ROLE]['capabilities']['edit_comment'] = 1; //add this role for admin automatically
        mvb_Model_API::updateBlogOption('user_roles', $role_list, $blog);
    }

    /*
     * Deactivation hook
     * 
     * Delete all record in DB related to current plugin
     * Restore original user roles
     */

    public function deactivate() {
        global $wpdb;

        $sites = mvb_Model_Helper::getSiteList();

        if (is_array($sites) && count($sites)) {
            foreach ($sites as $site) {
                $c_blog = new mvb_Model_Blog(array(
                            'id' => $site->blog_id,
                            'url' => get_site_url($site->blog_id),
                            'prefix' => $wpdb->get_blog_prefix($site->blog_id)
                        ));
                self::remove_options($c_blog);
            }
        } else {
            self::remove_options();
        }
    }

    /*
     * Remove options from DB
     * 
     */

    public static function remove_options($blog = FALSE) {

        $roles = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'original_user_roles', array(), $blog);

        if (count($roles)) {
            mvb_Model_API::updateBlogOption('user_roles', $roles, $blog);
        }
        //TODO - also remove cashe and user meta
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'original_user_roles', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'options', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'cache', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'restrictions', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'menu_order', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'key_params', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'sa_dialog', $blog); //TODO - delete in version 1.5
        mvb_Model_API::deleteBlogOption(WPACCESS_FTIME_MESSAGE, $blog);
    }

    /**
     * 
     */
    public function admin_print_scripts() {

        //core scripts
        wp_enqueue_script('postbox');
        wp_enqueue_script('dashboard');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('media-upload');

        //additional scripts
        wp_enqueue_script('jquery-ui', WPACCESS_JS_URL . 'ui/jquery-ui.min.js');
        wp_enqueue_script('jquery-treeview', WPACCESS_JS_URL . 'treeview/jquery.treeview.js');
        wp_enqueue_script('jquery-treeedit', WPACCESS_JS_URL . 'treeview/jquery.treeview.edit.js');
        wp_enqueue_script('jquery-treeview-ajax', WPACCESS_JS_URL . 'treeview/jquery.treeview.async.js');
        wp_enqueue_script('wpaccess-admin', WPACCESS_JS_URL . 'admin-options.js');
        wp_enqueue_script('codemirror', WPACCESS_JS_URL . 'codemirror/codemirror.js');
        wp_enqueue_script('codemirror-xml', WPACCESS_JS_URL . 'codemirror/ini.js');
        $locals = array(
            'nonce' => wp_create_nonce(WPACCESS_PREFIX . 'ajax'),
            'css' => WPACCESS_CSS_URL,
            'js' => WPACCESS_JS_URL,
            'hide_apply_all' => mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'hide_apply_all', 0),
            'LABEL_129' => mvb_Model_Label::get('LABEL_129'),
            'LABEL_130' => mvb_Model_Label::get('LABEL_130'),
            'LABEL_131' => mvb_Model_Label::get('LABEL_131'),
            'LABEL_76' => mvb_Model_Label::get('LABEL_76'),
            'LABEL_77' => mvb_Model_Label::get('LABEL_77'),
            'LABEL_132' => mvb_Model_Label::get('LABEL_132'),
            'LABEL_133' => mvb_Model_Label::get('LABEL_133'),
            'LABEL_90' => mvb_Model_Label::get('LABEL_90'),
            'LABEL_134' => mvb_Model_Label::get('LABEL_134'),
            'LABEL_135' => mvb_Model_Label::get('LABEL_135'),
            'LABEL_136' => mvb_Model_Label::get('LABEL_136'),
            'LABEL_137' => mvb_Model_Label::get('LABEL_137'),
            'LABEL_138' => mvb_Model_Label::get('LABEL_138'),
            'LABEL_139' => mvb_Model_Label::get('LABEL_139'),
            'LABEL_140' => mvb_Model_Label::get('LABEL_140'),
            'LABEL_24' => mvb_Model_Label::get('LABEL_24'),
            'LABEL_141' => mvb_Model_Label::get('LABEL_141'),
            'LABEL_142' => mvb_Model_Label::get('LABEL_142'),
            'LABEL_143' => mvb_Model_Label::get('LABEL_143'),
        );

        if (mvb_Model_API::isNetworkPanel()) {
            //can't use admin-ajax.php in fact it doesn't load menu and submenu
            $blog_id = (isset($_GET['site']) ? $_GET['site'] : get_current_blog_id());
            $c_blog = mvb_Model_API::getBlog($blog_id);
            $locals['handlerURL'] = get_admin_url($c_blog->getID(), 'index.php');
            $locals['ajaxurl'] = get_admin_url($c_blog->getID(), 'admin-ajax.php');
            wp_enqueue_script('wpaccess-admin-multisite', WPACCESS_JS_URL . 'admin-multisite.js');
            wp_enqueue_script('wpaccess-admin-url', WPACCESS_JS_URL . 'jquery.url.js');
        } else {
            $locals['handlerURL'] = admin_url('index.php');
            $locals['ajaxurl'] = admin_url('admin-ajax.php');
        }
        //

        $answer = mvb_Model_API::getBlogOption(WPACCESS_FTIME_MESSAGE);
        if (!$answer) {
            $locals['first_time'] = 1;
        }

        wp_localize_script('wpaccess-admin', 'wpaccessLocal', $locals);
    }

    /**
     * Main function for checking if user has access to a page
     * 
     * Check if current user has access to requested page. If no, print an
     * notification
     * @global object $wp_query
     */
    public function check() {

        mvb_Model_AccessControl::checkAccess();
    }

    /*
     * Main function for menu filtering
     * 
     * Add Access Manager submenu to User main menu and additionality filter
     * the Main Menu according to settings
     * 
     */

    public function admin_menu() {

        $cap = ( mvb_Model_API::isSuperAdmin() ? 'administrator' : 'aam_manage');

        add_submenu_page('users.php', __('Access Manager', 'aam'), __('Access Manager', 'aam'), $cap, 'wp_access', array($this, 'manager_page'));

        //init the list of key parameters
        $this->init_key_params();
        if (!mvb_Model_API::isSuperAdmin()) {
            //filter the menu
            mvb_Model_AccessControl::getMenuConf()->manage();
        }
    }

    /**
     * 
     */
    public function manager_page() {

        $c_role = isset($_REQUEST['role']) ? $_REQUEST['role'] : FALSE;
        $c_user = isset($_REQUEST['user']) ? $_REQUEST['user'] : FALSE;

        if (mvb_Model_API::isNetworkPanel()) {
            //require phpQuery
            require_once(WPACCESS_BASE_DIR . 'library/phpQuery/phpQuery.php');

            //TODO - I don't like site
            $blog_id = (isset($_GET['site']) ? $_GET['site'] : get_current_blog_id());
            $c_blog = mvb_Model_API::getBlog($blog_id);
            $m = new mvb_Model_Manager($this, $c_role, $c_user);
            $m->do_save();
            $params = array(
                'page' => 'wp_access',
                'render_mss' => 1,
                'site' => $blog_id,
                'role' => $c_role,
                'user' => $c_user
            );

            $link = get_admin_url($c_blog->getID(), 'users.php');
            $url = add_query_arg($params, $link);
            $result = mvb_Model_Helper::cURL($url, TRUE, TRUE);
            if (isset($result['content']) && $result['content']) {
                $content = phpQuery::newDocument($result['content']);
                echo $content['#aam_wrap']->htmlOuter();
                unset($content);
            } else {
                wp_die(mvb_Model_Label::get('LABEL_145'));
            }
        } else {
            $m = new mvb_Model_Manager($this, $c_role, $c_user);
            $m->do_save();
            $m->manage();
        }
    }

    /**
     * 
     */
    public function render_optionlist() {

        $role = mvb_Model_Helper::getParam('role', 'POST');
        $user = mvb_Model_Helper::getParam('user', 'POST');
        $m = new mvb_Model_Manager($this, $role, $user);
        $or_roles = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'original_user_roles');
        $content = $m->getMainOptionsList();
        $content = $m->templObj->clearTemplate($content);
        $result = array(
            'html' => apply_filters(WPACCESS_PREFIX . 'option_page', $content),
            'restorable' => (isset($or_roles[$role]) ? TRUE : FALSE)
        );

        die(json_encode($result));
    }

    /*
     * ===============================================================
     *   ******************* PRIVATE METHODS ************************
     * ===============================================================
     */

    /*
     * Initialize the list of all key parameters in the list of all
     * menus and submenus.
     * 
     * This is VERY IMPORTANT step for custom links like on Magic Field or
     * E-Commerce. 
     */

    private function init_key_params() {
        global $menu, $submenu;

        $roles = mvb_Model_API::getCurrentUser()->getRoles();
        $keys = array('post_type' => 1, 'page' => 1); //add core params
        if (in_array(WPACCESS_ADMIN_ROLE, $roles)) { //do this only for admin role
            if (is_array($menu)) { //main menu
                foreach ($menu as $item) {
                    $keys = array_merge($keys, $this->get_parts($item[2]));
                }
            }
            if (is_array($submenu)) {
                foreach ($submenu as $m => $s_items) {
                    if (is_array($s_items)) {
                        foreach ($s_items as $item) {
                            $keys = array_merge($keys, $this->get_parts($item[2]));
                        }
                    }
                }
            }
            mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'key_params', $keys);
        }
    }

    /**
     *
     * @param type $menu
     * @param type $level
     * @return type 
     */
    private function filter_top_bar(&$menu, $level = 0) {

        if ($level > 999) {
            return; //save step
        }

        if (is_object($menu)) {
            foreach ($menu as $item => &$data) {
                if (isset($data['href']) && !isset($data['children']) && mvb_Model_AccessControl::getMenuConf()->checkAccess($data['href'])) {
                    unset($menu->{$item});
                } elseif (isset($data['children'])) {
                    $this->filter_top_bar($data['children'], $level + 1);
                    if (count($data['children'])) {
                        foreach ($data['children'] as $key => $value) {
                            $data['href'] = $value['href'];
                            break;
                        }
                    } else {
                        unset($menu[$item]);
                    }
                }
            }
        }
    }

    /**
     *
     * @param type $menu
     * @return int 
     */
    private function get_parts($menu) {

        //splite requested URI
        $parts = preg_split('/\?/', $menu);
        $result = array();

        if (count($parts) > 1) { //no parameters
            $params = preg_split('/&|&amp;/', $parts[1]);
            foreach ($params as $param) {
                $t = preg_split('/=/', $param);
                $result[trim($t[0])] = 1;
            }
        }

        return $result;
    }

}

register_activation_hook(__FILE__, array('mvb_WPAccess', 'activate'));
//TODO - think about it
//register_deactivation_hook(__FILE__, array('mvb_WPAccess', 'deactivate'));
add_action('init', 'init_wpaccess');
add_action('set_current_user', 'aam_set_current_user');
?>