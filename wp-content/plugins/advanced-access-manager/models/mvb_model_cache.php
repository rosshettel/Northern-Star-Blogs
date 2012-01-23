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
 * Cache Model Class
 * 
 * Caching system
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Cache {

    /**
     *
     * @param type $type
     * @param type $id
     * @return type 
     */
    public static function getCacheData($type, $id) {

        $data = FALSE;
        if (self::canBeCached()) {
            $cache = self::getCacheObject();
            $data = $cache->load($type . '_' . $id);
        }

        return $data;
    }

    /**
     * Check if object can be cached
     * 
     * @return boolean 
     */
    public static function canBeCached() {

        $result = FALSE;
        if (WPACCESS_CACHE_STATUS == 'ON' && is_writable(WPACCESS_CACHE_DIR)) {
            $result = TRUE;
        }

        return $result;
    }

    /**
     *
     * @param type $type
     * @param type $id
     * @param type $data 
     */
    public static function saveCacheData($type, $id, $data) {

        if (self::canBeCached()) {
            $cache = self::getCacheObject();
            $cache->save($data, $type . '_' . $id);
        }
    }

    /**
     * Get Zend Cache object
     * 
     * @return object
     */
    public static function getCacheObject() {

        require_once('Zend/Cache.php');

        $f_opts = array(
            'lifetime' => WPACCESS_CACHE_LIFETIME,
            'automatic_serialization' => true
        );
        $b_opts = array(
            'cache_dir' => WPACCESS_CACHE_DIR
        );


        // getting a Zend_Cache_Core object
        return Zend_Cache::factory('Core', 'File', $f_opts, $b_opts);
    }

    /**
     * Clear Cache
     */
    public static function clearCache() {

        if (self::canBeCached()) {
            $cache = self::getCacheObject();
            $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        }
    }

}

?>