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
 * Filter for Metaboxes and Widgets
 * 
 * Probably it future releases this will be used also for filtering Front-End
 * Widgets. But still this issue is under consideration
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_FilterMetabox extends mvb_Abstract_Filter {

    /**
     *
     * @global type $wp_meta_boxes
     * @global type $post
     * @param type $area 
     */
    function manage($area = 'post') {
        global $wp_meta_boxes, $post;

        switch ($area) {
            case 'dashboard':
                if (is_array($wp_meta_boxes['dashboard'])) {
                    foreach ($wp_meta_boxes['dashboard'] as $position => $metaboxes) {
                        foreach ($metaboxes as $priority => $metaboxes1) {
                            foreach ($metaboxes1 as $metabox => $data) {
                                if (mvb_Model_AccessControl::getUserConf()->hasMetabox('dashboard-' . $metabox)) {
                                    unset($wp_meta_boxes['dashboard'][$position][$priority][$metabox]);
                                }
                            }
                        }
                    }
                }
                break;

            default:
                if ($wp_meta_boxes[$post->post_type]) {
                    foreach ($wp_meta_boxes[$post->post_type] as $position => $metaboxes) {
                        foreach ($metaboxes as $priority => $metaboxes1) {
                            foreach ($metaboxes1 as $metabox => $data) {
                                if (mvb_Model_AccessControl::getUserConf()->hasMetabox($post->post_type . '-' . $metabox)) {
                                    unset($wp_meta_boxes[$post->post_type][$position][$priority][$metabox]);
                                }
                            }
                        }
                    }
                }
                break;
        }
    }

}

?>