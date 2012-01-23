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
 * User Model Class
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Ajax {

    /**
     * Parent Object
     * 
     * Holds the main plugin object
     * 
     * @var object
     * @access public
     */
    public $pObj;

    /**
     * Requested action
     * 
     * @var string
     * @access protected
     */
    protected $action;

    /**
     * Main Constructor
     * 
     * @param object
     */
    public function __construct($pObj) {

        $this->pObj = $pObj;
        $this->action = $this->get_action();
    }

    /**
     * Process Ajax request
     * 
     */
    public function process() {

        switch ($this->action) {
            case 'apply_all':
                $result = $this->apply_all();
                break;

            case 'add_blog_admin':
                $result = $this->add_blog_admin();
                break;

            case 'restore_role':
                $result = $this->restore_role($_POST['role']);
                break;

            case 'create_role':
                $result = $this->create_role($_POST['role']);
                break;

            case 'delete_role':
                $result = $this->delete_role();
                break;

            case 'render_metabox_list':
                $result = $this->render_metabox_list();
                break;

            case 'initiate_wm':
                $result = $this->initiate_wm();
                break;

            case 'initiate_url':
                $result = $this->initiate_url();
                break;

            case 'add_capability':
                $result = $this->add_capability();
                break;

            case 'delete_capability':
                $result = $this->delete_capability();
                break;

            case 'get_treeview':
                $result = $this->get_treeview();
                break;

            case 'get_info':
                $result = $this->get_info();
                break;

            case 'get_userlist':
                $result = $this->get_userlist();
                break;

            case 'save_info':
                $result = $this->save_info();
                break;

            case 'check_addons':
                $result = $this->check_addons();
                break;

            case 'save_order':
                $result = $this->save_order();
                break;

            case 'export':
                $result = $this->export();
                break;

            case 'upload_config':
                $result = $this->upload_config();
                break;

            case 'create_super':
                $result = $this->create_super();
                break;

            case 'update_role_name':
                $result = $this->update_role_name();
                break;

            default:
                $result = array('status' => 'error');
                break;
        }

        die(json_encode($result));
    }

    /*
     * Apply settings to ALL blogs in Multisite Setup
     * 
     */

    protected function apply_all() {
        global $wpdb;

        $sites = mvb_Model_Helper::getSiteList();

        //TODO - implement more complex checking
        $result = array('status' => 'success', 'message' => mvb_Model_Label::get('LABEL_152'));

        if (is_array($sites) && count($sites)) {
            $c_blog = mvb_Model_API::getCurrentBlog();
            $options = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'options', array());
            $morders = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'menu_order', array());
            $usroles = mvb_Model_API::getBlogOption('user_roles', array());
            $kparams = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'key_params', array());
            $limit = WPACCESS_APPLY_LIMIT;
            /*
             * Check if Restriction class exist.
             * Note for hacks : Better will be to buy an add-on for $10 because on
             * next release I'll change the checking class
             */
            if (class_exists('aamms_msar_extend')) {
                $limit = apply_filters(WPACCESS_PREFIX . 'msar_restrict_limit', $limit);
            }
            foreach ($sites as $i => $site) {
                if ($site->blog_id == $c_blog->getID()) { //skip current blog
                    continue;
                }

                $blog = new mvb_Model_Blog(array(
                            'id' => $site->blog_id,
                            'url' => get_site_url($site->blog_id),
                            'prefix' => $wpdb->get_blog_prefix($site->blog_id))
                );
                /*
                  $options1 = get_blog_option($site->blog_id, $blog_prefix . WPACCESS_PREFIX . 'options', array());
                  $morders1 = get_blog_option($site->blog_id, $blog_prefix . WPACCESS_PREFIX . 'menu_order', array());
                  $usroles1 = get_blog_option($site->blog_id, $blog_prefix . 'user_roles', array());
                  $kparams1 = get_blog_option($site->blog_id, $blog_prefix . WPACCESS_PREFIX . 'key_params', array());

                  $options1 = array_merge_recursive($options, $options1);
                  $morders1 = array_merge_recursive($morders, $morders1);
                  $usroles1 = array_merge_recursive($usroles, $usroles1);
                  $kparams1 = array_merge_recursive($kparams, $kparams1);
                 * */
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'options', $options, $blog);
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'menu_order', $morders, $blog);
                mvb_Model_API::updateBlogOption('user_roles', $usroles, $blog);
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'key_params', $kparams, $blog);

                if (($limit != -1) && ($i + 1 > $limit)) {
                    $result = array('status' => 'error', 'message' => mvb_Model_Label::get('LABEL_156'));
                    break;
                }
            }
        }

        return $result;
    }

    /*
     * Update Roles Label
     * 
     */

    protected function update_role_name() {

        //TODO - Here you can hack and change Super Admin and Admin Label
        //But this is not a big deal.
        $role_list = mvb_Model_API::getRoleList(FALSE);
        $role = $_POST['role_id'];
        //TODO - maybe not the best way
        $label = urldecode(sanitize_title($_POST['label']));
        if (isset($role_list[$role])) {
            $role_list[$role]['name'] = ucfirst($label);
            mvb_Model_API::updateBlogOption('user_roles', $role_list);
            $result = array('status' => 'success');
        } else {
            $result = array('status' => 'error');
        }


        return $result;
    }

    /*
     * Get current action
     * 
     * @return bool Return true if ok
     */

    protected function get_action() {

        $a = (isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : FALSE);

        return $a;
    }

    protected function get_userlist() {

        $role = $_POST['role'];
        $users = $this->pObj->getUserList($role);

        $options = '<option value="0">' . mvb_Model_Label::get('LABEL_120') . '</option>';
        if (is_array($users)) {
            foreach ($users as $user) {
                $options .= '<option value="' . $user->ID . '">' . $user->user_login . '</option>';
            }
        }

        $result = array(
            'status' => 'success',
            'html' => $options
        );

        return $result;
    }

    /*
     * Restore default User Roles
     * 
     * @param string User Role
     * @return bool True if success
     */

    protected function restore_role($role) {
        global $wpdb;

        //get current roles settings
        $or_roles = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'original_user_roles', array());
        $roles = mvb_Model_API::getRoleList(FALSE);

        $allow = TRUE;
        if (($role == WPACCESS_ADMIN_ROLE) && !mvb_Model_API::isSuperAdmin()) {
            $allow = FALSE;
        }

        if (isset($or_roles[$role]) && isset($roles[$role]) && $allow) {
            $roles[$role] = $or_roles[$role];

            //save current setting to DB
            mvb_Model_API::updateBlogOption('user_roles', $roles);

            //unset all option with metaboxes and menu
            $options = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'options');
            if (isset($options[$role])) {
                unset($options[$role]);
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'options', $options);
            }

            //unset all restrictions
            $r = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'restrictions', array());
            if (isset($r[$role])) {
                unset($r[$role]);
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'restrictions', $r);
            }

            //unset menu order
            $menu_order = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'menu_order');
            if (isset($menu_order[$role])) {
                unset($menu_order[$role]);
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'menu_order', $menu_order);
            }

            $result = array('status' => 'success');
        } else {
            $result = array('status' => 'error');
        }

        return $result;
    }

    /**
     *
     * @param type $role
     * @param type $render_html
     * @return type 
     */
    protected function create_role($role, $render_html = TRUE) {

        $m = new mvb_Model_Role();
        $new_role = ($role ? $role : $_REQUEST['role']);
        $result = $m->createNewRole($new_role, array(
            'read' => 1,
            'level_0' => 1)
        );
        if ($result['result'] == 'success') {
            $m = new mvb_Model_Manager($this->pObj, $result['new_role']);
            $content = $m->renderDeleteRoleItem($result['new_role'], array('name' => $role));
            $result['html'] = $m->templObj->clearTemplate($content);
        }

        return $result;
    }

    /*
     * Delete Role
     * 
     */

    protected function delete_role() {

        $m = new mvb_Model_Role();
        //TODO - unsecure
        $m->remove_role($_POST['role']);
        $result = array('status' => 'success');

        return $result;
    }

    /*
     * Render metabox list after initialization
     * 
     * Part of AJAX interface. Is used for rendering the list of initialized
     * metaboxes.
     * 
     * @return string HTML string with result
     */

    protected function render_metabox_list() {

        $role = mvb_Model_Helper::getParam('role', 'POST');
        $user = mvb_Model_Helper::getParam('user', 'POST');
        $m = new mvb_Model_Manager($this->pObj, $role, $user);
        $content = $m->renderMetaboxList($m->getTemplate());
        $result = array(
            'status' => 'success',
            'html' => $m->templObj->clearTemplate($content)
        );

        return $result;
    }

    /*
     * Initialize Widgets and Metaboxes
     * 
     * Part of AJAX interface. Using for metabox and widget initialization.
     * Go through the list of all registered post types and with http request
     * try to access the edit page and grab the list of rendered metaboxes.
     * 
     * @return string JSON encoded string with result
     */

    protected function initiate_wm() {
        global $wp_post_types;

        check_ajax_referer(WPACCESS_PREFIX . 'ajax');

        /*
         * Go through the list of registered post types and try to grab
         * rendered metaboxes
         * Parameter next in _POST array shows the next port type in list of
         * registered metaboxes. This is done for emulating the progress bar
         * after clicking "Refresh List" or "Initialize List"
         */
        $next = trim($_POST['next']);
        $typeList = array_keys($wp_post_types);
        //add dashboard
        // array_unshift($typeList, 'dashboard');
        $typeQuant = count($typeList) + 1;
        $i = 0;
        if ($next) { //if next present, means that process continuing
            while ($typeList[$i] != $next) { //find post type
                $i++;
            }
            $current = $next;
            if (isset($typeList[$i + 1])) { //continue the initialization process?
                $next = $typeList[$i + 1];
            } else {
                $next = FALSE;
            }
        } else { //this is the beggining
            $current = 'dashboard';
            $next = isset($typeList[0]) ? $typeList[0] : '';
        }
        if ($current == 'dashboard') {
            $url = add_query_arg('grab', 'metaboxes', admin_url('index.php'));
        } else {
            $url = add_query_arg('grab', 'metaboxes', admin_url('post-new.php?post_type=' . $current));
        }

        //grab metaboxes
        $result = mvb_Model_Helper::cURL($url);

        $result['value'] = round((($i + 1) / $typeQuant) * 100); //value for progress bar
        $result['next'] = ($next ? $next : '' ); //if empty, stop initialization

        return $result;
    }

    /*
     * Initialize single URL
     * 
     * Sometimes not all metaboxes are rendered if there are conditions. For example
     * render Shipping Address Metabox if status of custom post type is Approved.
     * So this metabox will be not visible during general initalization in function
     * initiateWM(). That is why this function do that manually
     * 
     * @return string JSON encoded string with result
     */

    protected function initiate_url() {

        check_ajax_referer(WPACCESS_PREFIX . 'ajax');

        $url = $_POST['url'];
        if ($url) {
            $url = add_query_arg('grab', 'metaboxes', $url);
            $result = mvb_Model_Helper::cURL($url);
        } else {
            $result = array('status' => 'error');
        }

        return $result;
    }

    /**
     * Add New Capability
     * 
     * @global type $wpdb
     * @return type 
     */
    protected function add_capability() {
        global $wpdb;

        $cap = strtolower(trim($_POST['cap']));

        if ($cap) {
            $cap = sanitize_title_with_dashes($cap);
            $cap = str_replace('-', '_', $cap);
            $capList = mvb_Model_API::getCurrentUser()->getAllCaps();

            if (!isset($capList[$cap])) { //create new capability
                $roles = mvb_Model_API::getRoleList(FALSE);
                if (isset($roles[WPACCESS_SADMIN_ROLE])) {
                    $roles[WPACCESS_SADMIN_ROLE]['capabilities'][$cap] = 1;
                }
                $roles[WPACCESS_ADMIN_ROLE]['capabilities'][$cap] = 1; //add this role for admin automatically
                mvb_Model_API::updateBlogOption('user_roles', $roles);
                //check if this is for specific user
                //TODO
                $user = mvb_Model_Helper::getParam('user', 'POST');
                if ($user) {
                    $conf = mvb_Model_API::getUserAccessConfig($user);
                    $conf->addCapability($cap);
                    $conf->saveConfig();
                }
                //save this capability as custom created
                $custom_caps = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'custom_caps');
                if (!is_array($custom_caps)) {
                    $custom_caps = array();
                }
                $custom_caps[] = $cap;
                mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'custom_caps', $custom_caps);
                //render html
                $tmpl = new mvb_Model_Template();
                $templatePath = WPACCESS_TEMPLATE_DIR . 'admin_options.html';
                $template = $tmpl->readTemplate($templatePath);
                $listTemplate = $tmpl->retrieveSub('CAPABILITY_LIST', $template);
                $itemTemplate = $tmpl->retrieveSub('CAPABILITY_ITEM', $listTemplate);
                $markers = array(
                    '###role###' => $_POST['role'],
                    '###title###' => $cap,
                    '###description###' => '',
                    '###checked###' => 'checked',
                    '###cap_name###' => mvb_Model_Helper::getCapabilityHumanTitle($cap)
                );
                $titem = $tmpl->updateMarkers($markers, $itemTemplate);
                $titem = $tmpl->replaceSub('CAPABILITY_DELETE', $tmpl->retrieveSub('CAPABILITY_DELETE', $titem), $titem);

                $result = array(
                    'status' => 'success',
                    'html' => $tmpl->clearTemplate($titem)
                );
            } else {
                $result = array(
                    'status' => 'error',
                    'message' => 'Capability ' . $_POST['cap'] . ' already exists'
                );
            }
            mvb_Model_Cache::clearCache();
        } else {
            $result = array(
                'status' => 'error',
                'message' => mvb_Model_Label::get('LABEL_124'),
            );
        }

        return $result;
    }

    /*
     * Delete capability
     */

    protected function delete_capability() {
        global $wpdb;

        $cap = trim($_POST['cap']);
        $custom_caps = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'custom_caps');

        if (in_array($cap, $custom_caps)) {
            $roles = mvb_Model_API::getBlogOption('user_roles');
            if (is_array($roles)) {
                foreach ($roles as &$role) {
                    if (isset($role['capabilities'][$cap])) {
                        unset($role['capabilities'][$cap]);
                    }
                }
            }
            mvb_Model_API::updateBlogOption('user_roles', $roles);
            $result = array(
                'status' => 'success'
            );
            mvb_Model_Cache::clearCache();
        } else {
            $result = array(
                'status' => 'error',
                'message' => mvb_Model_Label::get('LABEL_125')
            );
        }

        return $result;
    }

    /*
     * Get Post tree
     * 
     */

    protected function get_treeview() {
        global $wp_post_types;

        $type = $_REQUEST['root'];

        if ($type == "source") {
            $tree = array();
            if (is_array($wp_post_types)) {
                foreach ($wp_post_types as $post_type => $data) {
                    //show only list of post type which have User Interface
                    if ($data->show_ui) {
                        $tree[] = (object) array(
                                    'text' => $data->label,
                                    'expanded' => FALSE,
                                    'hasChildren' => TRUE,
                                    'id' => $post_type,
                                    'classes' => 'roots',
                        );
                    }
                }
            }
        } else {
            $parts = preg_split('/\-/', $type);

            switch (count($parts)) {
                case 1: //root of the post type
                    $tree = $this->build_branch($parts[0]);
                    break;

                case 2: //post type
                    if ($parts[0] == 'post') {
                        $post_type = get_post_field('post_type', $parts[1]);
                        $tree = $this->build_branch($post_type, FALSE, $parts[1]);
                    } elseif ($parts[0] == 'cat') {
                        $taxonomy = mvb_Model_Helper::getTaxonomyByTerm($parts[1]);
                        $tree = $this->build_branch(NULL, $taxonomy, $parts[1]);
                    }
                    break;

                default:
                    $tree = array();
                    break;
            }
        }

        if (!count($tree)) {
            $tree[] = (object) array(
                        'text' => '<i>[' . mvb_Model_Label::get('LABEL_153') . ']</i>',
                        'hasChildren' => FALSE,
                        'classes' => 'post-ontree',
                        'id' => 'empty-' . uniqid()
            );
        }

        return $tree;
    }

    private function build_branch($post_type, $taxonomy = FALSE, $parent = 0) {
        global $wpdb;

        $tree = array();
        if (!$parent && !$taxonomy) { //root of a branch
            $tree = $this->build_categories($post_type);
        } elseif ($taxonomy) { //build sub categories
            $tree = $this->build_categories('', $taxonomy, $parent);
        }
        //render list of posts in current category
        if ($parent == 0) {

            $query = "SELECT p.ID FROM `{$wpdb->posts}` AS p ";
            $query .= "LEFT JOIN `{$wpdb->term_relationships}` AS r ON ( p.ID = r.object_id ) ";
            $query .= "WHERE (p.post_type = '{$post_type}') AND (p.post_status NOT IN ('trash', 'auto-draft')) AND (p.post_parent = 0) AND r.object_id IS NULL";
            $posts = $wpdb->get_col($query);
        } elseif ($parent && $taxonomy) {
            $posts = get_objects_in_term($parent, $taxonomy);
        } elseif ($post_type && $parent) {
            $posts = get_posts(array('post_parent' => $parent, 'post_type' => $post_type, 'fields' => 'ids', 'nopaging' => TRUE));
        }

        if (is_array($posts)) {
            foreach ($posts as $post_id) {
                $post = get_post($post_id);
                $onClick = "loadInfo(event, \"post\", {$post->ID});";
                $tree[] = (object) array(
                            'text' => "<a href='#' onclick='{$onClick}'>{$post->post_title}</a>",
                            'hasChildren' => $this->has_post_childs($post),
                            'classes' => 'post-ontree',
                            'id' => 'post-' . $post->ID
                );
            }
        }

        return $tree;
    }

    private function build_categories($post_type, $taxonomy = FALSE, $parent = 0) {

        $tree = array();

        if ($parent) {
            //$taxonomy = $this->get_taxonomy_get_term($parent);
            //firstly render the list of sub categories
            $cat_list = get_terms($taxonomy, array('get' => 'all', 'parent' => $parent));
            if (is_array($cat_list)) {
                foreach ($cat_list as $category) {
                    $tree[] = $this->build_category($category);
                }
            }
        } else {
            $taxonomies = get_object_taxonomies($post_type);
            foreach ($taxonomies as $taxonomy) {
                if (is_taxonomy_hierarchical($taxonomy)) {
                    $term_list = get_terms($taxonomy);
                    if (is_array($term_list)) {
                        foreach ($term_list as $term) {
                            $tree[] = $this->build_category($term);
                        }
                    }
                }
            }
        }

        return $tree;
    }

    private function build_category($category) {

        $onClick = "loadInfo(event, \"taxonomy\", {$category->term_id});";
        $branch = (object) array(
                    'text' => "<a href='#' onclick='{$onClick}'>{$category->name}</a>",
                    'expanded' => FALSE,
                    'classes' => 'important',
        );
        if ($this->has_category_childs($category)) {
            $branch->hasChildren = TRUE;
            $branch->id = "cat-{$category->term_id}";
        }

        return $branch;
    }

    /*
     * Check if category has children
     * 
     * @param int category ID
     * @return bool TRUE if has
     */

    protected function has_post_childs($post) {

        $posts = get_posts(array('post_parent' => $post->ID, 'post_type' => $post->post_type));

        return (count($posts) ? TRUE : FALSE);
    }

    /*
     * Check if category has children
     * 
     * @param int category ID
     * @return bool TRUE if has
     */

    protected function has_category_childs($cat) {
        global $wpdb;

        //get number of categories
        $query = "SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE parent={$cat->term_id}";
        $counter = $wpdb->get_var($query) + $cat->count;

        return ($counter ? TRUE : FALSE);
    }

    /**
     * Get Information about current post or page
     * 
     * @global type $wp_post_statuses
     * @global type $wp_post_types
     * @return type 
     */
    protected function get_info() {
        global $wp_post_statuses, $wp_post_types;

        $id = intval($_POST['id']);
        $type = trim($_POST['type']);
        $role = $_POST['role'];
        $user = $_POST['user'];

        if ($user) {
            $config = mvb_Model_API::getUserAccessConfig($user);
        } else {
            $config = mvb_Model_API::getRoleAccessConfig($role);
        }


        //render html
        $tmpl = new mvb_Model_Template();
        $templatePath = WPACCESS_TEMPLATE_DIR . 'admin_options.html';
        $template = $tmpl->readTemplate($templatePath);
        $template = $tmpl->retrieveSub('POST_INFORMATION', $template);
        $result = array('status' => 'error');

        switch ($type) {
            case 'post':
                //get information about page or post
                $post = get_post($id);
                if ($post->ID) {
                    $template = $tmpl->retrieveSub('POST', $template);
                    if ($config->hasRestriction('post', $id)) {
                        $restiction = $config->getRestriction('post', $id);
                        $checked = ($restiction['restrict'] ? 'checked' : '');
                        $checked_front = ($restiction['restrict_front'] ? 'checked' : '');
                        $exclude = ($config->hasExclude($id) ? 'checked' : '');
                        $expire = ($restiction['expire'] ? date('m/d/Y', $restiction['expire']) : '');
                    }
                    $markerArray = array(
                        '###post_title###' => mvb_Model_Helper::editPostLink($post),
                        '###disabled_apply_all###' => ($user ? 'disabled="disabled"' : ''),
                        '###restrict_checked###' => (isset($checked) ? $checked : ''),
                        '###restrict_front_checked###' => (isset($checked_front) ? $checked_front : ''),
                        '###restrict_expire###' => (isset($expire) ? $expire : ''),
                        '###exclude_page_checked###' => (isset($exclude) ? $exclude : ''),
                        '###post_type###' => ucfirst($post->post_type),
                        '###post_status###' => $wp_post_statuses[$post->post_status]->label,
                        '###post_visibility###' => mvb_Model_Helper::checkVisibility($post),
                        '###ID###' => $post->ID,
                    );
                    //check what type of post is it and render exclude if page
                    $render_exclude = FALSE;
                    if (isset($wp_post_types[$post->post_type])) {
                        switch ($wp_post_types[$post->post_type]->capability_type) {
                            case 'page':
                                $render_exclude = TRUE;
                                break;

                            default:
                                break;
                        }
                    }
                    if ($render_exclude) {
                        $excld_tmlp = $tmpl->retrieveSub('EXCLUDE_PAGE', $template);
                    } else {
                        $excld_tmlp = '';
                    }
                    $template = $tmpl->replaceSub('EXCLUDE_PAGE', $excld_tmlp, $template);
                    $template = $tmpl->updateMarkers($markerArray, $template);

                    $result = array(
                        'status' => 'success',
                        'html' => $tmpl->clearTemplate($template)
                    );
                }
                break;

            case 'taxonomy':
                //get information about category
                $taxonomy = mvb_Model_Helper::getTaxonomyByTerm($id);
                $term = get_term($id, $taxonomy);
                if ($term->term_id) {
                    $template = $tmpl->retrieveSub('CATEGORY', $template);
                    if ($config->hasRestriction('taxonomy', $id)) {
                        $tax = $config->getRestriction('taxonomy', $id);
                        $checked = ($tax['restrict'] ? 'checked' : '');
                        $checked_front = ($tax['restrict_front'] ? 'checked' : '');
                        $expire = ($tax['expire'] ? date('m/d/Y', $tax['expire']) : '');
                    }
                    $markerArray = array(
                        '###name###' => mvb_Model_Helper::editTermLink($term),
                        '###disabled_apply_all###' => ($user ? 'disabled="disabled"' : ''),
                        '###restrict_checked###' => (isset($checked) ? $checked : ''),
                        '###restrict_front_checked###' => (isset($checked_front) ? $checked_front : ''),
                        '###restrict_expire###' => (isset($expire) ? $expire : ''),
                        '###post_number###' => $term->count,
                        '###ID###' => $term->term_id,
                    );
                    $template = $tmpl->updateMarkers($markerArray, $template);

                    $result = array(
                        'status' => 'success',
                        'html' => $tmpl->clearTemplate($template)
                    );
                }
                break;

            default:
                break;
        }

        return $result;
    }

    /**
     *
     * @param type $config
     * @param type $info
     * @return string 
     */
    protected function updateRestrictions($config, $info) {

        $admin = (isset($info['restrict']) ? 1 : 0);
        $front = (isset($info['restrict_front']) ? 1 : 0);
        $exclude = (isset($info['exclude_page']) ? 1 : 0);
        $expire = trim($info['restrict_expire']);
        $result = array();

        //Check if Restriction class exist.
        //Note for hacks : Better will be to buy an add-on for $5 because on
        //next release I'll change the checking class
        $limit = WPACCESS_RESTRICTION_LIMIT;
        if (class_exists('aamer_aam_extend_restriction')) {
            $limit = apply_filters(WPACCESS_PREFIX . 'restrict_limit', $limit);
        }

        $rests = $config->getRestrictions();
        switch ($info['type']) {
            case 'post':
                $count = (isset($rests['posts']) ? count($rests['posts']) : 0);
                if ($exclude) {
                    $config->addExclude($info['id']);
                } else {
                    $config->deleteExclude($info['id']);
                }
                break;

            case 'taxonomy':
                $count = (isset($rests['categories']) ? count($rests['categories']) : 0);
                break;

            default:
                break;
        }
        if (!$config->hasRestriction($info['type'], $info['id'])) {
            $count++;
        }

        if ($limit == -1 || $count <= $limit) {

            if ($admin || $front || $expire) {
                $config->updateRestriction($info['type'], $info['id'], array(
                    'restrict' => $admin,
                    'restrict_front' => $front,
                    'expire' => $expire)
                );
            } else {
                $config->deleteRestriction($info['type'], $info['id']);
            }
            $result['status'] = 'success';
        } else {
            $result['status'] = 'error';
            $result['message'] = mvb_Model_Label::get('upgrade_restriction');
        }

        if ($result['status'] == 'success') {
            $config->saveConfig();
        }

        return $result;
    }

    /**
     * Save information about page/post/category restriction
     * 
     * @todo Junk
     */
    protected function save_info() {

        $role = $_POST['role'];
        $user = $_POST['user'];
        $apply_all = intval($_POST['apply']);
        $exclude = (isset($_POST['exclude_page']) ? 1 : 0);
        $apply_all_cb = intval($_POST['apply_all_cb']);
        mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'hide_apply_all', $apply_all_cb);

        if ($user) {
            $config = mvb_Model_API::getUserAccessConfig($user);
            $result = $this->updateRestrictions($config, $_POST['info']);
        } else {
            if ($apply_all) {
                foreach (mvb_Model_API::getRoleList() as $role => $dummy) {
                    $config = mvb_Model_API::getRoleAccessConfig($role);
                    $result = $this->updateRestrictions($config, $_POST['info']);
                    if ($result['status'] == 'error') {
                        break;
                    }
                }
            } else {
                $config = mvb_Model_API::getRoleAccessConfig($role);
                $result = $this->updateRestrictions($config, $_POST['info']);
            }
        }

        return $result;
    }

    /*
     * Check if new addons available
     * 
     */

    protected function check_addons() {

        //grab list of features
        $url = 'http://whimba.org/features.php';
        //second paramter is FALSE, which means that I'm not sending any
        //cookies to my website
        $response = mvb_Model_Helper::cURL($url, FALSE, TRUE);

        if (isset($response['content'])) {
            $data = json_decode($response['content']);
        }
        $available = FALSE;
        if (is_array($data->features) && count($data->features)) {
            $plugins = get_plugins();
            foreach ($data->features as $feature) {
                if (!isset($plugins[$feature])) {
                    $available = TRUE;
                    break;
                }
            }
        }

        $result = array(
            'status' => 'success',
            'available' => $available
        );


        return $result;
    }

    /**
     * Save menu order
     * 
     * @return array
     */
    protected function save_order() {

        $apply_all = $_POST['apply_all'];
        $role = $_POST['role'];
        $user = $_POST['user'];


        if ($user) {
            $config = mvb_Model_API::getUserAccessConfig($user);
            $config->setMenuOrder($_POST['menu']);
            $config->saveConfig();
        } else {
            if ($apply_all) {
                foreach (mvb_Model_API::getRoleList() as $role => $dummy) {
                    $config = mvb_Model_API::getRoleAccessConfig($role);
                    $config->setMenuOrder($_POST['menu']);
                    $config->saveConfig();
                }
            } else {
                $config = mvb_Model_API::getRoleAccessConfig($role);
                $config->setMenuOrder($_POST['menu']);
                $config->saveConfig();
            }
        }

        mvb_Model_Cache::clearCache();

        return array('status' => 'success');
    }

    /*
     * Export configurations
     * 
     */

    protected function export() {

        $file = $this->render_config();
        $file_b = basename($file);

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_b));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
        }

        die();
    }

    /*
     * Render Config File
     * 
     */

    private function render_config() {

        $file_path = WPACCESS_BASE_DIR . 'backups/' . uniqid(WPACCESS_PREFIX) . '.ini';
        $m = new mvb_Model_Manager($this->pObj);
        $m->render_config($file_path);

        return $file_path;
    }

    /*
     * Uploading file
     * 
     */

    protected function upload_config() {

        $result = 0;
        if (isset($_FILES["config_file"])) {
            $fdata = $_FILES["config_file"];
            if (is_uploaded_file($fdata["tmp_name"]) && ($fdata["error"] == 0)) {
                $file_name = 'import_' . uniqid() . '.ini';
                $file_path = WPACCESS_BASE_DIR . 'backups/' . $file_name;
                $result = move_uploaded_file($fdata["tmp_name"], $file_path);
            }
        }

        $data = array(
            'status' => ($result ? 'success' : 'error'),
            'file_name' => $file_name
        );

        return $data;
    }

    /*
     * Add Current User to Blog and make him a Super Admin
     * 
     */

    protected function add_blog_admin() {

        $user_id = get_current_user_id();

        $blog_id = get_current_blog_id();
        $ok = add_user_to_blog($blog_id, $user_id, WPACCESS_ADMIN_ROLE);

        if ($ok) {
            mvb_Model_API::getCurrentUser()->add_role(WPACCESS_ADMIN_ROLE);
            mvb_Model_API::getCurrentUser()->add_role(WPACCESS_SADMIN_ROLE);
            $result = array('status' => 'success', 'message' => mvb_Model_Label::get('LABEL_154'));
        } else {
            $result = array('status' => 'error', 'message' => mvb_Model_Label::get('LABEL_155'));
        }

        return $result;
    }

    /*
     * Create super admin User Role
     */

    protected function create_super() {
        global $wpdb;

        $answer = intval($_POST['answer']);
        $user_id = get_current_user_id();

        if ($answer == 1) {
            $role_list = mvb_Model_API::getRoleList(FALSE);

            if (isset($role_list[WPACCESS_SADMIN_ROLE])) {
                $result = array(
                    'result' => 'success',
                    'new_role' => WPACCESS_SADMIN_ROLE
                );
            } else {
                $result = $this->create_role('Super Admin', mvb_Model_API::getAllCapabilities());
            }

            if ($result['result'] == 'success') {
                //update current user role
                if (!is_user_member_of_blog(get_current_blog_id())) {
                    $this->add_blog_admin();
                } else {
                    $this->assign_role(WPACCESS_SADMIN_ROLE, $user_id);
                }
                $this->deprive_role($user_id, WPACCESS_SADMIN_ROLE, WPACCESS_ADMIN_ROLE);
                mvb_Model_API::updateBlogOption(WPACCESS_FTIME_MESSAGE, $answer);
            } else {
                $result = array('result' => 'error');
            }
        } else {
            $result = array('result' => 'error');
        }

        return $result;
    }

    /*
     * Assigne Role to User
     * 
     */

    protected function assign_role($role, $user_id) {

        $m = new mvb_Model_User($user_id);
        $m->add_role($role);
    }

    /*
     * Delete User Role for other Users
     * 
     * @param int Skip User's ID
     * @param string User Role
     * @param string Role to Replace with
     */

    protected function deprive_role($skip_id, $role, $replace_role) {
        global $wpdb;

        //TODO Should be better way to grab the list of users
        $blog = mvb_Model_API::getCurrentBlog();
        $query = "SELECT user_id FROM {$wpdb->usermeta} WHERE ";
        $query .= 'meta_key = "' . $blog->getPrefix() . 'capabilities"';
        $list = $wpdb->get_results($query);

        if (is_array($list) && count($list)) {
            foreach ($list as $row) {
                if ($row->user_id == $skip_id) {
                    continue;
                }
                $m = new mvb_Model_User($row->user_id);

                if ($m->has_cap($role)) {
                    $m->remove_role($role);
                    $m->add_role($replace_role);
                }
            }
        }
    }

}

?>