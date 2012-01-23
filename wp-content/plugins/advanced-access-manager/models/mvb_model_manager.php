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
 * Option Manager Model Class
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Manager {

    /**
     * Template object holder
     * 
     * @var object
     * @access public
     */
    public $templObj;

    /**
     * HTML templated from file
     * 
     * @var string Template to work with
     * @access private
     */
    private $template;

    /**
     * Array of User Roles
     * 
     * @var array
     * @access private
     */
    private $roles;

    /**
     * Current role to work with
     * 
     * @var string
     * @access private
     */
    private $currentRole;

    /**
     * Current user to work with
     * 
     * @var int
     * @access private
     */
    private $currentUser;

    /**
     * Main Object
     * 
     * @var object
     * @access protected
     */
    protected $pObj;

    /**
     * Copy of a config array from main object
     * 
     * @var array
     * @access protected
     */
    protected $config;

    /**
     * Cache config
     * 
     * @var array
     * @access protected
     */
    protected $cache;

    /**
     * Initiate an object and other parameters
     * 
     * @param string $currentRole Current role to work with
     * @param string $currentUser Current user to work with
     * @param object Main Object
     */
    function __construct($pObj, $currentRole = FALSE, $currentUser = FALSE) {

        $this->pObj = $pObj;
        $this->templObj = new mvb_Model_Template();
        $templatePath = WPACCESS_TEMPLATE_DIR . 'admin_options.html';
        $this->template = $this->templObj->readTemplate($templatePath);
        $this->roles = mvb_Model_API::getRoleList();
        $this->custom_caps = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'custom_caps', array());

        $this->setCurrentRole($currentRole);
        $this->setCurrentUser($currentUser);

        if ($this->currentUser) {
            $this->config = mvb_Model_API::getUserAccessConfig($this->currentUser);
        } else {
            $this->config = mvb_Model_API::getRoleAccessConfig($this->currentRole);
        }
        
        //get cache. Compatible with version previouse versions
        $cache = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'cache', NULL);
        if (is_array($cache)) { //yeap this is new version
            $this->cache = $cache;
        } else { //TODO - will be deprecated
            $cache = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'options', array());
            $this->cache = (isset($cache['settings']) ? $cache['settings'] : array());
            mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'cache', $this->cache);
        }

        $this->userSummary = count_users();
    }
    
    /**
     *
     * @param type $role
     * @return bool
     */
    protected function setCurrentRole($role) {

        if ($this->role_exists($role)) {
            $this->currentRole = $role;
        } else {
            $this->currentRole = mvb_Model_API::getCurrentEditableUserRole();
        }

        return TRUE;
    }

    /**
     *
     * @param type $user 
     */
    protected function setCurrentUser($user) {

        if ($this->user_exists($user)) {
            $this->currentUser = $user;
        } else {
            $this->currentUser = FALSE;
        }
    }

    /**
     *
     * @param type $user_id
     * @return type 
     */
    public function user_exists($user_id) {

        $result = (get_user_by('id', $user_id) ? TRUE : FALSE);

        return $result;
    }

    /**
     *
     * @param type $role
     * @return type 
     */
    function role_exists($role) {

        $exists = (isset($this->roles[$role]) ? TRUE : FALSE);

        return $exists;
    }

    /**
     *
     * @return type 
     */
    function getTemplate() {

        return $this->template;
    }

    /**
     * 
     */
    function manage() {

        $content = $this->getMainOptionsList();
        $this->template = $this->renderSiteSelector($this->template);
        $this->template = $this->renderRoleSelector($this->template);
        $this->template = $this->renderUserSelector($this->template);
        $this->template = $this->renderDeleteRoleList($this->template);
        $content = $this->templObj->replaceSub('MAIN_OPTIONS_LIST', $content, $this->template);
        $blog = mvb_Model_API::getCurrentBlog();

        //TODO - render_mss do not like it
        if (mvb_Model_API::isNetworkPanel() || isset($_REQUEST['render_mss'])) {
            $s_link = network_admin_url('users.php?page=wp_access');
            $blog_id = (isset($_GET['site']) ? $_GET['site'] : get_current_blog_id());
            $s_link = add_query_arg('site', $blog_id, $s_link);
        } else {
            $s_link = admin_url('users.php?page=wp_access');
        }

        $markerArray = array(
            '###current_role###' => $this->roles[$this->currentRole]['name'],
            '###form_action###' => $s_link,
            '###current_role_id###' => $this->currentRole,
            '###site_url###' => $blog->getURL(),
            '###message_class###' => ( (isset($_POST['submited']) || isset($_GET['show_message'])) ? 'message-active' : 'message-passive'),
            '###nonce###' => wp_nonce_field(WPACCESS_PREFIX . 'options'),
        );
        //get current user data
        if ($this->currentUser) {
            $c_user = get_userdata($this->currentUser);
            $markerArray['###current_user###'] = $c_user->user_login;
            $markerArray['###current_user_id###'] = $c_user->ID;
        } else {
            $markerArray['###current_user###'] = mvb_Model_Label::get('LABEL_120');
        }
        //Apply all blogs
        $t = $this->templObj->retrieveSub('APPLY_ALL', $content);
        if (mvb_Model_API::isNetworkPanel() || isset($_REQUEST['render_mss'])) {
            $content = $this->templObj->replaceSub('APPLY_ALL', $t, $content);
        } else {
            $content = $this->templObj->replaceSub('APPLY_ALL', '', $content);
        }

        $content = $this->templObj->updateMarkers($markerArray, $content);
        $content = $this->templObj->clearTemplate($content);

        //add filter to future add-ons
        $content = apply_filters(WPACCESS_PREFIX . 'option_page', $content);

        echo $content;
    }

    /**
     * Save parameters to database
     * 
     */
    function do_save() {

        if (isset($_POST['submited'])) {
            $params = (isset($_POST['wpaccess']) ? $_POST['wpaccess'] : array());
            //overwrite current blog
            //TODO - maybe there is better way
            if (isset($_GET['site'])) {
                mvb_Model_API::setCurrentBlog($_GET['site']);
            }

            $dump = isset($params['menu']) ? $params['menu'] : array();
            $this->config->setMenu($dump);
            $dump = isset($params['metabox']) ? $params['metabox'] : array();
            $this->config->setMetaboxes($dump);
            $dump = isset($params['advance']) ? $params['advance'] : array();
            $this->config->setCapabilities($dump);

            $this->config->saveConfig();

            //save global access congif
            mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'access_config', $params['access_config']);
        }
    }

    /**
     *
     * @param type $template
     * @return type 
     */
    function renderDeleteRoleList($template) {

        $listTemplate = $this->templObj->retrieveSub('DELETE_ROLE_LIST', $template);
        $itemTemplate = $this->templObj->retrieveSub('DELETE_ROLE_ITEM', $listTemplate);
        $list = '';
        if (is_array($this->roles)) {
            foreach ($this->roles as $role => $data) {
                $list .= $this->renderDeleteRoleItem($role, $data, $itemTemplate);
            }
        }
        $listTemplate = $this->templObj->replaceSub('DELETE_ROLE_ITEM', $list, $listTemplate);

        return $this->templObj->replaceSub('DELETE_ROLE_LIST', $listTemplate, $template);
    }

    /**
     *
     * @param type $role
     * @param type $data
     * @param type $template
     * @return type 
     */
    function renderDeleteRoleItem($role, $data, $template = '') {
        /*
         * This is used for ajax
         */
        if (!$template) {
            $listTemplate = $this->templObj->retrieveSub('DELETE_ROLE_LIST', $this->template);
            $template = $this->templObj->retrieveSub('DELETE_ROLE_ITEM', $listTemplate);
        }
        $count = isset($this->userSummary['avail_roles'][$role]) ? $this->userSummary['avail_roles'][$role] : 0;
        $deleteTemplate = $this->templObj->retrieveSub('DELETE_ROLE_BUTTON', $template);
        $markerArray = array(
            '###role_id###' => esc_js($role),
            '###role_name###' => stripcslashes($data['name']),
            '###count###' => $count,
        );
        if (!$count) {
            $template = $this->templObj->replaceSub('DELETE_ROLE_BUTTON', $deleteTemplate, $template);
        } else {
            $template = $this->templObj->replaceSub('DELETE_ROLE_BUTTON', '', $template);
        }

        return $this->templObj->updateMarkers($markerArray, $template);
    }

    /**
     *
     * @return type 
     */
    function getMainOptionsList() {

        $mainHolder = $this->templObj->retrieveSub('MAIN_OPTIONS_LIST', $this->template);

        return $this->renderMainMenuOptions($mainHolder);
    }

    /**
     *
     * @global type $wpdb
     * @param type $template
     * @return type 
     */
    function renderSiteSelector($template) {
        global $wpdb;

        $m_tempate = $this->templObj->retrieveSub('MULTISITE_SELECTOR', $template);
        if (mvb_Model_API::isNetworkPanel() || isset($_REQUEST['render_mss'])) {
            $listTemplate = $this->templObj->retrieveSub('ROLE_LIST', $m_tempate);
            $list = '';

            $sites = mvb_Model_Helper::getSiteList();
            $current = (isset($_REQUEST['site']) ? $_REQUEST['site'] : get_current_blog_id());
            if (is_array($sites)) {
                foreach ($sites as $site) {
                    $blog_prefix = $wpdb->get_blog_prefix($site->blog_id);
                    //get Site Name
                    $query = "SELECT option_value FROM {$blog_prefix}options ";
                    $query .= "WHERE option_name = 'blogname'";
                    $name = $wpdb->get_var($query);
                    if ($site->blog_id == $current) {
                        $is_current = 'selected="selected"';
                        $c_name = $name;
                    } else {
                        $is_current = '';
                    }
                    $markers = array(
                        '###value###' => $site->blog_id,
                        '###title###' => $name . '&nbsp;', //nicer view :)
                        '###selected###' => $is_current,
                    );
                    $list .= $this->templObj->updateMarkers($markers, $listTemplate);
                }
            }
            $m_tempate = $this->templObj->replaceSub('ROLE_LIST', $list, $m_tempate);
            $m_array = array(
                '###current_site###' => (strlen($c_name) > 15 ? substr($c_name, 0, 14) . '...' : $c_name),
                '###title_full###' => $c_name
            );
            $m_tempate = $this->templObj->updateMarkers($m_array, $m_tempate);
        } else {
            $m_tempate = '';
        }

        return $this->templObj->replaceSub('MULTISITE_SELECTOR', $m_tempate, $template);
    }

    /**
     *
     * @param type $template
     * @return type 
     */
    function renderRoleSelector($template) {
        $listTemplate = $this->templObj->retrieveSub('ROLE_LIST', $template);
        $list = '';
        if (is_array($this->roles)) {
            foreach ($this->roles as $role => $data) {
                $markers = array(
                    '###value###' => $role,
                    '###title###' => stripcslashes($data['name']) . '&nbsp;', //nicer view :)
                    '###selected###' => ($this->currentRole == $role ? 'selected' : ''),
                );
                $list .= $this->templObj->updateMarkers($markers, $listTemplate);
            }
        }

        return $this->templObj->replaceSub('ROLE_LIST', $list, $template);
    }

    /**
     *
     * @param type $template
     * @return type 
     */
    function renderUserSelector($template) {
        $listTemplate = $this->templObj->retrieveSub('USER_LIST', $template);
        //get list of users
        $users = $this->pObj->getUserList($this->currentRole);
        $list = '';

        if (is_array($users)) {
            foreach ($users as $user) {
                $markers = array(
                    '###value###' => $user->ID,
                    '###title###' => stripcslashes($user->user_login) . '&nbsp;', //nicer view :)
                    '###selected###' => ($this->currentUser == $user->ID ? 'selected' : ''),
                );
                $list .= $this->templObj->updateMarkers($markers, $listTemplate);
            }
        }

        return $this->templObj->replaceSub('USER_LIST', $list, $template);
    }

    /**
     *
     * @global type $submenu
     * @param type $template
     * @return type 
     */
    public function renderMainMenuOptions($template) {
        global $submenu;

        $s_menu = $this->getRoleMenu();
        /*
         * First Tab - Main Menu
         */
        $listTemplate = $this->templObj->retrieveSub('MAIN_MENU_LIST', $template);
        $itemTemplate = $this->templObj->retrieveSub('MAIN_MENU_ITEM', $listTemplate);
        $sublistTemplate = $this->templObj->retrieveSub('MAIN_MENU_SUBLIST', $itemTemplate);
        $subitemTemplate = $this->templObj->retrieveSub('MAIN_MENU_SUBITEM', $sublistTemplate);
        $list = '';

        if (is_array($s_menu)) {
            foreach ($s_menu as $menuItem) {
                if (!$menuItem[0]) { //seperator
                    continue;
                }
                //render submenu
                $subList = '';
                if (isset($submenu[$menuItem[2]]) && is_array($submenu[$menuItem[2]])) {
                    foreach ($submenu[$menuItem[2]] as $submenuItem) {
                        $checked = $this->checkChecked('submenu', array($menuItem[2], $submenuItem[2]));

                        $markers = array(
                            '###submenu_name###' => $this->removeHTML($submenuItem[0]),
                            '###value###' => $submenuItem[2],
                            '###checked###' => $checked
                        );
                        $subList .= $this->templObj->updateMarkers($markers, $subitemTemplate);
                    }
                    $subList = $this->templObj->replaceSub('MAIN_MENU_SUBITEM', $subList, $sublistTemplate);
                }
                $tTempl = $this->templObj->replaceSub('MAIN_MENU_SUBLIST', $subList, $itemTemplate);
                $markers = array(
                    '###name###' => $this->removeHTML($menuItem[0]),
                    '###id###' => $menuItem[5],
                    '###menu###' => $menuItem[2],
                    '###whole_checked###' => $this->checkChecked('menu', array($menuItem[2]))
                );
                $list .= $this->templObj->updateMarkers($markers, $tTempl);
            }
        }
        $listTemplate = $this->templObj->replaceSub('MAIN_MENU_ITEM', $list, $listTemplate);
        $template = $this->templObj->replaceSub('MAIN_MENU_LIST', $listTemplate, $template);
        /*
         * Second Tab - Metaboxes
         */
        $listTemplate = $this->renderMetaboxList($template);
        $template = $this->templObj->replaceSub('METABOX_LIST', $listTemplate, $template);
        /*
         * Third Tab - Advance Settings
         */
        $capList = mvb_Model_API::getCurrentUser()->getAllCaps(); //TODO ?
        ksort($capList);

        $listTemplate = $this->templObj->retrieveSub('CAPABILITY_LIST', $template);
        $itemTemplate = $this->templObj->retrieveSub('CAPABILITY_ITEM', $listTemplate);
        $list = '';
        if (is_array($capList) && count($capList)) {
            foreach ($capList as $cap => $dump) {
                $desc = str_replace("\n", '<br/>', mvb_Model_Label::get($cap));
                $markers = array(
                    '###title###' => $cap,
                    '###description###' => $desc,
                    '###checked###' => $this->checkChecked('capability', array($cap)),
                    '###cap_name###' => mvb_Model_Helper::getCapabilityHumanTitle($cap)
                );
                $titem = $this->templObj->updateMarkers($markers, $itemTemplate);
                if (!in_array($cap, $this->custom_caps)) {
                    $titem = $this->templObj->replaceSub('CAPABILITY_DELETE', '', $titem);
                } else {
                    $titem = $this->templObj->replaceSub('CAPABILITY_DELETE', $this->templObj->retrieveSub('CAPABILITY_DELETE', $titem), $titem);
                }
                $list .= $titem;
            }
            $template = $this->templObj->replaceSub('CAPABILITY_LIST_EMPTY', '', $template);
        } else {
            $empty = $this->templObj->retrieveSub('CAPABILITY_LIST_EMPTY', $template);
            $template = $this->templObj->replaceSub('CAPABILITY_LIST_EMPTY', $empty, $template);
        }
        $listTemplate = $this->templObj->replaceSub('CAPABILITY_ITEM', $list, $listTemplate);
        $template = $this->templObj->replaceSub('CAPABILITY_LIST', $listTemplate, $template);

        //Posts & Pages
        $template = $this->templObj->replaceSub('POST_INFORMATION', '', $template);

        $template = $this->templObj->updateMarkers(array(
            '###access_config###' => stripslashes(mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'access_config', ''))), $template);

        return $template;
    }

    protected function getRoleMenu() {
        global $menu;

        $r_menu = $menu;
        ksort($r_menu);

        if (is_array($menu)) {
            $w_menu = array();
            foreach ($this->config->getMenuOrder() as $mid) {
                foreach ($menu as $data) {
                    if (isset($data[5]) && ($data[5] == $mid)) {
                        $w_menu[] = $data;
                    }
                }
            }
            $cur_pos = 0;
            foreach ($r_menu as &$data) {
                for ($i = 0; $i < count($w_menu); $i++) {
                    if (isset($data[5]) && ($w_menu[$i][5] == $data[5])) {
                        $data = $w_menu[$cur_pos++];
                        break;
                    }
                }
            }
        }

        return $r_menu;
    }

    function renderMetaboxList($template) {
        global $wp_post_types;

        $listTemplate = $this->templObj->retrieveSub('METABOX_LIST', $template);

        $itemTemplate = $this->templObj->retrieveSub('METABOX_LIST_ITEM', $listTemplate);
        $list = '';


        if (isset($this->cache['metaboxes']) && is_array($this->cache['metaboxes'])) {
            $plistTemplate = $this->templObj->retrieveSub('POST_METABOXES_LIST', $itemTemplate);
            $pitemTemplate = $this->templObj->retrieveSub('POST_METABOXES_ITEM', $plistTemplate);

            foreach ($this->cache['metaboxes'] as $post_type => $metaboxes) {

                if (!isset($wp_post_types[$post_type])) {
                    if ($post_type != 'dashboard') {
                        continue;
                    }
                }

                $mList = '';
                foreach ($metaboxes as $position => $metaboxes1) {
                    foreach ($metaboxes1 as $priority => $metaboxes2) {
                        if (is_array($metaboxes2) && count($metaboxes2)) {
                            foreach ($metaboxes2 as $id => $data) {

                                if (is_array($data)) {
                                    //strip html for metaboxes. The reason - dashboard metaboxes
                                    $data['title'] = $this->removeHTML($data['title']);
                                    $markerArray = array(
                                        '###title###' => $this->removeHTML($data['title']),
                                        '###short_id###' => (strlen($data['id']) > 25 ? substr($data['id'], 0, 22) . '...' : $data['id']),
                                        '###id###' => $data['id'],
                                        '###priority###' => $priority,
                                        '###internal_id###' => $post_type . '-' . $id,
                                        '###position###' => $position,
                                        '###checked###' => $this->checkChecked('metabox', array($post_type . '-' . $id)),
                                    );
                                    $mList .= $this->templObj->updateMarkers($markerArray, $pitemTemplate);
                                }
                            }
                        }
                    }
                }
                $tList = $this->templObj->replaceSub('POST_METABOXES_ITEM', $mList, $plistTemplate);
                $tList = $this->templObj->replaceSub('POST_METABOXES_LIST', $mList, $itemTemplate);
                $label = ($post_type != 'dashboard' ? $wp_post_types[$post_type]->labels->name : 'Dashboard');
                $list .= $this->templObj->updateMarkers(array('###post_type_label###' => $label), $tList);
            }
            $listTemplate = $this->templObj->replaceSub('METABOX_LIST_EMPTY', '', $listTemplate);
            $listTemplate = $this->templObj->replaceSub('METABOX_LIST_ITEM', $list, $listTemplate);
        } else {
            $emptyMessage = $this->templObj->retrieveSub('METABOX_LIST_EMPTY', $listTemplate);
            $listTemplate = $this->templObj->replaceSub('METABOX_LIST_ITEM', '', $listTemplate);
            $listTemplate = $this->templObj->replaceSub('METABOX_LIST_EMPTY', $emptyMessage, $listTemplate);
        }

        return $listTemplate;
    }

    function removeHTML($text) {
        $text = preg_replace(
                array(
            "'<span[^>]*?>.*?</span[^>]*?>'si",
                ), '', $text);

        $text = preg_replace('/<a[^>]*href[[:space:]]*=[[:space:]]*["\']?[[:space:]]*javascript[^>]*/i', '', $text);

        // Return clean content
        return $text;
    }

    function checkChecked($type, $args) {

        $checked = '';

        switch ($type) {
            case 'submenu':
                $c_menu = $this->config->getMenu();
                if (isset($c_menu[$args[0]])) {
                    if (isset($c_menu[$args[0]]['sub'][$args[1]]) ||
                            (isset($c_menu[$args[0]]['whole']) && $c_menu[$args[0]]['whole'])) {
                        $checked = 'checked';
                    }
                }
                break;

            case 'menu':
                $c_menu = $this->config->getMenu();
                if (isset($c_menu[$args[0]]['whole']) && $c_menu[$args[0]]['whole']) {
                    $checked = 'checked';
                }
                break;

            case 'capability':
                $c_cap = $this->config->getCapabilities();
                if (isset($c_cap[$args[0]])) {
                    $checked = 'checked';
                }
                break;

            case 'metabox':
                $c_meta = $this->config->getMetaboxes();
                if (isset($c_meta[$args[0]])) {
                    $checked = 'checked';
                }
                break;

            default:
                break;
        }

        return $checked;
    }

}

?>