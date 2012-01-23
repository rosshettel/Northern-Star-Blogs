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
 * Main Access Control Model
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_AccessControl {

    /**
     *
     * @var type 
     */
    protected static $user_config = NULL;

    /**
     *
     * @var type 
     */
    protected static $admin_menu = NULL;

    /**
     * Main function for checking if user has access to a page
     * 
     * Check if current user has access to requested page. If no, print an
     * notification
     * @global object $wp_query
     */
    public static function checkAccess() {
        global $wp_query, $post;

        if (is_admin() && !mvb_Model_API::isSuperAdmin()) {

            $uri = $_SERVER['REQUEST_URI'];
            if (!self::getMenuConf()->checkAccess($uri)) {
                self::getUserConf()->getConfigPress()->doRedirect();
            }

            //check if user try to access a post
            if (isset($_GET['post'])) {
                $post_id = (int) $_GET['post'];
            } elseif (isset($_POST['post_ID'])) {
                $post_id = (int) $_POST['post_ID'];
            } else {
                $post_id = 0;
            }

            if ($post_id) { //check if current user has access to current post
                $post = get_post($post_id);
                if (self::checkPostAccess($post)) {
                    self::getUserConf()->getConfigPress()->doRedirect();
                }
            } elseif (isset($_GET['taxonomy']) && isset($_GET['tag_ID'])) { // TODO - Find better way
                if (self::checkCategoryAccess($_GET['tag_ID'])) {
                    self::getUserConf()->getConfigPress()->doRedirect();
                }
            }
        } elseif (!mvb_Model_API::isSuperAdmin()) {
            if (is_category()) {
                $cat_obj = $wp_query->get_queried_object();
                if (!self::checkCategoryAccess($cat_obj->term_id)) {
                    self::getUserConf()->getConfigPress()->doRedirect();
                }
            } else {
                if (!$wp_query->is_home() && $post) {
                    if (self::checkPostAccess($post) && !mvb_Model_API::isSuperAdmin()) {
                        self::getUserConf()->getConfigPress()->doRedirect();
                    }
                }
            }
        }
    }

    /**
     *
     * @return type 
     */
    public static function getUserConf() {

        if (self::$user_config === NULL) {
            $user_id = get_current_user_id();
            if (!(self::$user_config = mvb_Model_Cache::getCacheData('user', $user_id))) {
                self::$user_config = mvb_Model_API::getUserAccessConfig($user_id);
                mvb_Model_Cache::saveCacheData('user', $user_id, self::$user_config);
            }
            //initialize Restriction Tree
            self::$user_config->initRestrictionTree();
        }

        return self::$user_config;
    }

    /**
     * 
     */
    public static function getMenuConf() {
        //init admin menu 
        if (self::$admin_menu === NULL) {
            self::$admin_menu = new mvb_Model_FilterMenu();
        }

        return self::$admin_menu;
    }

    /**
     * Check Category Restriction
     * 
     * @param int $id
     * @return boolean TRUE if restricted
     */
    public static function checkCategoryAccess($id) {

        $restrict = FALSE;
        if ($data = self::getUserConf()->getRestriction('taxonomy', $id)) {
            if (is_admin() && $data['restrict']) {
                $restrict = TRUE;
            } elseif (!is_admin() && $data['restrict_front']) {
                $restrict = TRUE;
            }
        }

        return $restrict;
    }

    /**
     * Check if user has access to current post
     * 
     * @param object $post
     * @return boolean
     */
    public static function checkPostAccess($post) {

        $restrict = FALSE;
        //get post's categories
        $taxonomies = get_object_taxonomies($post);

        $cat_list = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));

        if (is_array($cat_list)) {
            foreach ($cat_list as $cat_id) {
                if (self::checkCategoryAccess($cat_id)) {
                    $restrict = TRUE;
                    break;
                }
            }
        }

        if ($data = self::getUserConf()->getRestriction('post', $post->ID)) {
            if (is_admin() && $data['restrict']) {
                $restrict = TRUE;
            } elseif (!is_admin() && $data['restrict_front']) {
                $restrict = TRUE;
            }
        }

        return $restrict;
    }

    /**
     * Check if page is excluded from the menu
     * 
     * @param type $page
     * @return boolean 
     * @todo Delete This is not necessary
     */
    public static function checkPageExcluded($page) {

        return (self::getUserConf()->hasExclude($page->ID) ? TRUE : FALSE);
    }

}

?>