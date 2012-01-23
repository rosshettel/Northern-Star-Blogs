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

error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', FALSE);

//load general files
require_once('mvb_functions.php');

/*
 * Core constants
 */
define('WPACCESS_PREFIX', 'wpaccess_');
define('WPACCESS_BASE_DIR', dirname(__FILE__) . '/');
define('WPACCESS_DIRNAME', basename(WPACCESS_BASE_DIR));

/*
 * Plugin constants
 */
define('WPACCESS_BASE_URL', WP_PLUGIN_URL . '/' . WPACCESS_DIRNAME . '/');
define('WPACCESS_ADMIN_ROLE', 'administrator');
define('WPACCESS_SADMIN_ROLE', 'super_admin');
define('WPACCESS_RESTRICTION_LIMIT', 5);
define('WPACCESS_APPLY_LIMIT', 5);
define('WPACCESS_TOP_LEVEL', 10);

define('WPACCESS_TEMPLATE_DIR', WPACCESS_BASE_DIR . 'view/html/');
define('WPACCESS_CSS_URL', WPACCESS_BASE_URL . 'view/css/');
define('WPACCESS_JS_URL', WPACCESS_BASE_URL . 'view/js/');

define('WPACCESS_CACHE_LIFETIME', 864000); //10 days
define('WPACCESS_CACHE_DIR', WPACCESS_BASE_DIR . 'temp'); //cache dir

define('WPACCESS_FTIME_MESSAGE', WPACCESS_PREFIX . 'first_time');

define('WPACCESS_CACHE_STATUS', 'ON');

load_plugin_textdomain('aam', false, WPACCESS_DIRNAME . '/langs');

//configure include path for library
$path = WPACCESS_BASE_DIR . 'library/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
 
?>