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
 * Filter for Dashboard Menu
 * 
 * Probably it future releases this will be used also for filtering Front-End
 * Menu. But still this issue is under consideration
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_FilterMenu extends mvb_Abstract_Filter {

    function __construct() {

        $keyParams = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'key_params', array());

        $this->keyParams = array_keys($keyParams); //TODO - Save in array format
    }

    /**
     *
     * @global type $menu
     * @global type $submenu
     * @param type $area 
     */
    function manage($area = '') {
        global $menu, $submenu;

        foreach (mvb_Model_AccessControl::getUserConf()->getMenu() as $main => $data) {
            if (isset($data['whole']) && ($data['whole'] == 1)) {
                $this->unsetMainMenuItem($main);
            } elseif (isset($data['sub']) && is_array($data['sub'])) {
                foreach ($data['sub'] as $sub => $dummy) {
                    $this->unsetSubMenuItem($main, $sub);
                }
            }
        }
        //reorganize menu
        $menu = $this->reorganizeMenu();
    }

    /**
     *
     * @global type $menu
     * @return type 
     * @todo This is a copy from optionmanager
     */
    protected function reorganizeMenu() {
        global $menu;

        $r_menu = $menu;
        ksort($r_menu);
        $m_order = mvb_Model_AccessControl::getUserConf()->getMenuOrder();

        if (is_array($menu) && count($m_order)) {
            $w_menu = array();
            foreach ($m_order as $mid) {
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

    /*
     * Check if User has Access to current page
     * 
     * @param string Current Requested URI
     * @return bool TRUE if access granded
     */

    function checkAccess($requestedMenu) {

        if (!mvb_Model_API::isSuperAdmin()) {
            //get base file
            $parts = $this->get_parts($requestedMenu);
            //aam_debug($this->cParams[$role]['menu']);
            foreach (mvb_Model_AccessControl::getUserConf()->getMenu() as $menu => $sub) {
                if ($this->compareMenus($parts, $menu) && isset($sub['whole'])) {
                    return FALSE;
                }
                if (isset($sub['sub']) && is_array($sub['sub'])) {
                    foreach ($sub['sub'] as $subMenu => $dummy) {
                        if ($this->compareMenus($parts, $subMenu)) {
                            return FALSE;
                        }
                    }
                }
            }
        }

        return TRUE;
    }

	/**
	 *
	 * @param type $parts
	 * @param type $menu
	 * @return boolean 
	 */
    function compareMenus($parts, $menu) {

        $compare = $this->get_parts($menu);
        $c_params = array_intersect($parts, $compare);
        $result = FALSE;

        if (count($c_params) == count($parts)) { //equal menus
            $result = TRUE;
        } elseif (count($c_params) && ($parts[0] == $compare[0])) { //probably similar
            $diff = array_diff($parts, $compare) + array_diff($compare, $parts);
            $result = TRUE;

            foreach ($diff as $d) {
                $td = preg_split('/=/', $d);
                if (in_array($td[0], $this->keyParams)) {
                    $result = FALSE;
                    break;
                }
            }
        }

        return $result;
    }

	/**
	 *
	 * @param string $requestedMenu
	 * @return type 
	 */
    function get_parts($requestedMenu) {

        //this is for only one case - edit.php
        if (in_array(basename($requestedMenu), array('edit.php', 'post-new.php'))) {
            $requestedMenu .= '?post_type=post';
        }
        //splite requested URI
        $parts = preg_split('/\?/', $requestedMenu);
        $result = array(basename($parts[0]));

        if (count($parts) > 1) { //no parameters
            $params = preg_split('/&|&amp;/', $parts[1]);
            $result = array_merge($result, $params);
        }

        return $result;
    }

	/**
	 *
	 * @global type $menu
	 * @global type $submenu
	 * @param type $menuItem 
	 */
    function unsetMainMenuItem($menuItem) {
        global $menu, $submenu;

        if (is_array($menu)) {
            foreach ($menu as $key => $item) {
                if ($item[2] == $menuItem) {
                    unset($menu[$key]);
                    unset($submenu[$menuItem]);
                }
            }
        }
    }

	/**
	 *
	 * @global type $submenu
	 * @param type $dummy
	 * @param type $submenuItem
	 * @return boolean 
	 */
    function unsetSubMenuItem($dummy, $submenuItem) {
        global $submenu;

        $result = FALSE; //not deleted
        if (is_array($submenu)) {
            foreach ($submenu as $main => $subs) {
                if (is_array($subs)) {
                    foreach ($subs as $key => $item) {
                        if ($item[2] == $submenuItem) {
                            unset($submenu[$main][$key]);
                            return TRUE;
                        }
                    }
                }
            }
        }

        return $result;
    }

}

?>