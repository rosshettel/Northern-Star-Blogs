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
 * Abstract class for Advanced Access Manager Configuration Object
 * 
 * Define general logic for Configuration object
 * 
 * @package AAM
 * @subpackage Abstract Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
abstract class mvb_Abstract_Config {
    /**
     * No Restrictions
     */
    const RESTRICT_NO = 0;

    /**
     * Restrict Backend
     */
    const RESTRICT_BACK = 1;

    /**
     * Restrict Frontend
     */
    const RESTRICT_FRONT = 2;

    /**
     * Rescrict Both Sides
     */
    const RESTRICT_BOTH = 3;

    /**
     * Current Object ID
     * 
     * @var int|string
     * @access protected 
     */
    protected $id;

    /**
     * Admin Menu config
     * 
     * @var array
     * @access protected
     */
    protected $menu = array();

    /**
     * Menu Order config
     * 
     * @var array
     * @access protected
     */
    protected $menu_order = array();

    /**
     * Metaboxes config
     * 
     * @var array 
     * @access protected
     */
    protected $metaboxes = array();

    /**
     * Capabilities list
     * 
     * @var array
     * @access protected
     */
    protected $capabilities = array();

    /**
     * Post & Taxonomy restrictions
     * 
     * @var array
     * @access protected
     */
    protected $restrictions = array();

    /**
     * Exclude Pages from Navigation
     * 
     * @var array
     * @access protected
     */
    protected $excludes = array();

    /**
     * Access Configurations
     * 
     * @var object
     * @access protected
     */
    protected $access_config;

    /**
     * Initialize object
     * 
     * @param int|string $id 
     */
    public function __construct($id) {

        $this->ID = $id;
        //get configuration from db
        $this->getConfig();
        //init access Configurations
        $this->initAccessConfig();
    }

    /**
     * Save Configuration to database
     * 
     * @access public
     */
    abstract public function saveConfig();

    /**
     * Get Configuration from database
     * 
     * @access protected
     */
    abstract protected function getConfig();

    /**
     * Return current ID
     * 
     * @return int|string
     */
    public function getID() {

        return $this->ID;
    }

    /**
     * Set Menu Config array
     * 
     * @param array $menu
     */
    public function setMenu($menu) {

        $this->menu = (is_array($menu) ? $menu : array());
    }

    /**
     * Get Menu Config array
     * 
     * @return array
     */
    public function getMenu() {

        return $this->menu;
    }

    /**
     * Set Menu Order
     * 
     * @param array $menu_order
     */
    public function setMenuOrder($menu_order) {

        $this->menu_order = (is_array($menu_order) ? $menu_order : array());
    }

    /**
     * Get Menu Order
     * 
     * @return array
     */
    public function getMenuOrder() {

        return $this->menu_order;
    }

    /**
     * Set Metaboxes Config array
     * 
     * @param array $metaboxes
     */
    public function setMetaboxes($metaboxes) {

        $this->metaboxes = (is_array($metaboxes) ? $metaboxes : array());
    }

    /**
     * Get Metaboxes Config Array
     * 
     * @return array
     */
    public function getMetaboxes() {

        return $this->metaboxes;
    }
    
    /**
     * Check if metabox is set
     * 
     * @param string $id
     * @return bool
     */
    public function hasMetabox($id){
        
        return (isset($this->metaboxes[$id]) ? TRUE : FALSE);
    }

    /**
     * Set Capabilities
     * 
     * @param array $capabilities 
     */
    public function setCapabilities($capabilities) {

        $this->capabilities = (is_array($capabilities) ? $capabilities : array());
    }

    /**
     * Get Capabilities 
     * 
     * @return array
     */
    public function getCapabilities() {

        return $this->capabilities;
    }

    /**
     * Add New Capability
     * 
     * @param string $capability 
     */
    public function addCapability($capability) {

        if (!$this->hasCapability($capability)) {
            $this->capabilities[$capability] = 1;
        }
    }

    /**
     * Check if capability is present in config array
     * 
     * @param string $capability
     * @return bool
     */
    public function hasCapability($capability) {

        return (isset($this->capabilities[$capability]) ? TRUE : FALSE);
    }

    /**
     * Set Restrictions
     * 
     * @access public
     * @param bool $init
     */
    public function setRestrictions($restrictions) {

        $this->restrictions = $restrictions;
    }

    /**
     * Initialize hierarhical restriction tree
     *  
     */
    public function initRestrictionTree() {

        $rests = $this->getRestrictions();

        if (isset($rests['categories']) && is_array($rests['categories'])) {
            foreach ($rests['categories'] as $id => $restrict) {
                $r = $this->checkExpiration($restrict);
                if ($r) {
                    $rests['categories'][$id]['restrict'] = ($r & self::RESTRICT_BACK ? 1 : 0);
                    $rests['categories'][$id]['restrict_front'] = ($r & self::RESTRICT_FRONT ? 1 : 0);
                    //get list of all subcategories
                    $taxonomy = mvb_Model_Helper::getTaxonomyByTerm($id);
                    $rests['categories'][$id]['taxonomy'] = $taxonomy;
                    $cat_list = get_term_children($id, $taxonomy);
                    if (is_array($cat_list)) {
                        foreach ($cat_list as $cid) {
                            $rests['categories'][$cid] = $rests['categories'][$id];
                        }
                    }
                } else {
                    unset($rests['categories'][$id]);
                }
            }
        }
        //prepare list of posts and pages
        if (isset($rests['posts']) && is_array($rests['posts'])) {
            foreach ($rests['posts'] as $id => $restrict) {
                //now check combination of options
                $r = $this->checkExpiration($restrict);
                if ($r) {
                    $rests['posts'][$id]['restrict'] = ($r & self::RESTRICT_BACK ? 1 : 0);
                    $rests['posts'][$id]['restrict_front'] = ($r & self::RESTRICT_FRONT ? 1 : 0);
                } else {
                    if ($rests['posts'][$id]['exclude_page']) {
                        $rests['posts'][$id] = array(
                            'exclude_page' => 1
                        );
                    } else {
                        unset($rests['posts'][$id]);
                    }
                }
            }
        }

        $this->setRestrictions($rests);
    }

    /**
     * Check if access is expired according to date
     * 
     * @param array $data
     * @return int 
     */
    protected function checkExpiration($data) {

        $result = self::RESTRICT_NO;
        if (($data['restrict'] || $data['restrict_front']) && !trim($data['expire'])) {
            $result = ($data['restrict'] ? $result | self::RESTRICT_BACK : $result);
            $result = ($data['restrict_front'] ? $result | self::RESTRICT_FRONT : $result);
        } elseif (($data['restrict'] || $data['restrict_front']) && trim($data['expire'])) {
            if ($data['expire'] >= time()) {
                $result = ($data['restrict'] ? $result | self::RESTRICT_BACK : $result);
                $result = ($data['restrict_front'] ? $result | self::RESTRICT_FRONT : $result);
            }
        } elseif (trim($data['expire'])) {
            if (time() <= $data['expire']) {
                $result = self::RESTRICT_BOTH; //TODO - Think about it
            }
        }

        return $result;
    }

    /**
     * Get Restrictions
     * 
     * @return array
     */
    public function getRestrictions() {

        return $this->restrictions;
    }

    /**
     * Check if restriction specified
     * 
     * @param string $type
     * @param int $id
     * @return bool 
     */
    public function hasRestriction($type, $id) {

        $result = FALSE;

        switch ($type) {
            case 'post':
                $result = (isset($this->restrictions['posts'][$id]) ? TRUE : FALSE);
                break;

            case 'taxonomy':
                $result = (isset($this->restrictions['categories'][$id]) ? TRUE : FALSE);
                break;

            default:
                break;
        }

        return $result;
    }

    /**
     * Get Restriction info
     * 
     * @param string $type
     * @param int $id
     * @return array 
     */
    public function getRestriction($type, $id) {

        $result = array();

        if ($this->hasRestriction($type, $id)) {
            switch ($type) {
                case 'post':
                    $result = $this->restrictions['posts'][$id];
                    break;

                case 'taxonomy':
                    $result = $this->restrictions['categories'][$id];
                    break;

                default:
                    break;
            }
        }

        return $result;
    }

    /**
     * Update Restriction
     * 
     * @param string $type
     * @param int $id
     * @param array $data 
     */
    public function updateRestriction($type, $id, $data) {

        $rests = $this->getRestrictions();
        switch ($type) {
            case 'post':
                if (!isset($rests['posts'])) {
                    $rests['posts'] = array();
                }
                $rests['posts'][$id] = $data;
                break;

            case 'taxonomy':
                if (!isset($rests['categories'])) {
                    $rests['categories'] = array();
                }
                $rests['categories'][$id] = $data;
                break;

            default:
                break;
        }

        $this->setRestrictions($rests);
    }

    /**
     * Delete Restriction
     * 
     * @param string $type
     * @param int $id 
     */
    public function deleteRestriction($type, $id) {

        if ($this->hasRestriction($type, $id)) {
            $rests = $this->getRestrictions();
            switch ($type) {
                case 'post':
                    unset($rests['posts'][$id]);
                    break;

                case 'taxonomy':
                    unset($rests['categoris'][$id]);
                    break;

                default:
                    break;
            }

            $this->setRestrictions($rests);
        }
    }

    /**
     * Set Excludes
     * 
     * @param array $excludes
     */
    public function setExcludes($excludes) {

        $this->excludes = (is_array($excludes) ? $excludes : array());
    }

    /**
     * Get Excludes
     * 
     * @return array
     */
    public function getExcludes() {

        return $this->excludes;
    }

    /**
     * Check if page is excluded
     * 
     * @param int $id
     * @return bool 
     */
    public function hasExclude($id) {

        return (isset($this->excludes[$id]) ? TRUE : FALSE);
    }

    /**
     * Add Exclude
     * 
     * @param int $id
     */
    public function addExclude($id) {

        if (!$this->hasExclude($id)) {
            $this->excludes[$id] = 1;
        }
    }

    /**
     * Delete Exclude
     * 
     * @param int $id 
     */
    public function deleteExclude($id) {

        if ($this->hasExclude($id)) {
            unset($this->excludes[$id]);
        }
    }

    protected function initAccessConfig() {

        $a_conf = stripslashes(mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'access_config'));
        require_once('Zend/Config.php');
        require_once('Zend/Config/Ini_Str.php');
        if (trim($a_conf)) {
            $a_conf = new Zend_Config_Ini_Str($a_conf);
        }
        $this->access_config = new mvb_Model_ConfigPress($a_conf);
    }

    public function getConfigPress() {

        return $this->access_config;
    }

}

?>