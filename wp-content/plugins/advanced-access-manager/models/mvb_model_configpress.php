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
 * Access Script Model Class
 * 
 * Access Script
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_ConfigPress {

    public function __construct($config) {

        $this->config = $config;
    }

    /**
     * Redirect
     * 
     * @param string $area
     */
    public function doRedirect() {

        if (is_admin()) {
            if (isset($this->config->backend->access->deny->redirect)) {
                $redirect = $this->config->backend->access->deny->redirect;
                $this->parseRedirect($redirect);
            }
        } else {
            if (isset($this->config->frontend->access->deny->redirect)) {
                $redirect = $this->config->frontend->access->deny->redirect;
                $this->parseRedirect($redirect);
            }
        }

        mvb_Model_Label::initLabels();
        wp_die(mvb_Model_Label::get('LABEL_127'));
    }

    /**
     * Parse Redirect
     * 
     * @todo Delete in next release
     * @param mixed
     */
    protected function parseRedirect($redirect) {

        if (filter_var($redirect, FILTER_VALIDATE_URL)) {
            wp_redirect($redirect);
            exit;
        } elseif (is_int($redirect)) {
            wp_redirect(get_post_permalink($redirect));
            exit;
        } elseif (is_object($redirect) && isset($redirect->userFunc)) {
            $func = trim($redirect->userFunc);
             if (is_string($func) && is_callable($func)) {
                call_user_func($func);
            }
        }
        
    }

}

?>