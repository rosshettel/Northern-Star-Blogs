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

function aam_debug($what) {
    echo '<pre>';
    print_r($what);
    echo '</pre>';
}

function init_wpaccess() {
    global $mvb_wpAccess;

    $mvb_wpAccess = new mvb_WPAccess();
}

/**
 * Autoloader for project Advanced Access Manager
 * 
 * Try to load a class if prefix is mvb_
 * 
 * @param string $class_name 
 */
function mvb_autoload($class_name) {

    $parts = preg_split('/_/', $class_name);
    if ($parts[0] == 'mvb') {
        //check what type of class do we need to load
        switch ($parts[1]) {
            case 'Model':
                $path = WPACCESS_BASE_DIR . 'models/';
                break;

            case 'Abstract':
                $path = WPACCESS_BASE_DIR . 'models/abstract/';
                break;

            default:
                $path = '';
                break;
        }
        $file_path = $path . strtolower($class_name) . '.php';

        require_once($file_path);
    }
}

spl_autoload_register('mvb_autoload');

/**
 * Merget to configs
 * 
 * @param object $config
 * @param object $m_config 
 */
function mvb_merge_configs($config, $m_config) {

    //check which config has highest user level and overwrite lower
    if (mvb_Model_Helper::isLowerLevel($config, $m_config)) {
        $config->setMenu($m_config->getMenu());
        $config->setMetaboxes($m_config->getMetaboxes());
        if (count($m_config->getMenuOrder())) {
            $config->setMenuOrder($m_config->getMenuOrder());
        }
    }

    $caps = array_merge($config->getCapabilities(), $m_config->getCapabilities());
    $config->setCapabilities($caps);

    $rests = mvb_Model_Helper::array_merge_recursive($m_config->getRestrictions(), $config->getRestrictions());
    $config->setRestrictions($rests, FALSE);

    $excludes = mvb_Model_Helper::array_merge_recursive($config->getExcludes(), $m_config->getExcludes());
    $config->setExcludes($excludes);

    return $config;
}

function aam_set_current_user() {
    global $current_user;

    //overwrite user capabilities
    //TODO - Not optimized
    $config = mvb_Model_API::getUserAccessConfig($current_user->ID);

    if ($config instanceof mvb_Model_UserConfig) {
        $current_user->allcaps = $config->getCapabilities();
        if ($config->getUser() instanceof WP_User) {
            foreach ($config->getUser()->getRoles() as $role) {
                $current_user->allcaps[$role] = 1;
            }
        }
    }
}

function aam_error_handler($errNo, $errStr, $errFile, $errLine) {
    global $wpdb;

    if (!(error_reporting() & $errNo)) {
        return;
    }

    if (!strpos($errFile, 'advanced-access-manager')) {
        return;
    }

    $f = fopen(WPACCESS_CACHE_DIR . '/error.log', 'a');

    $haldPro = FALSE;

    switch ($errNo) {
        case E_USER_ERROR:
            $str = 'E_USER_ERROR : ' . $errStr . "\n" .
                    '   Fatal error in file ' . $errFile . '(' . $errLine . ')' . "\n";
            $haldPro = TRUE;
            break;
        case E_USER_WARNING:
            $str = 'E_USER_WARNING : ' . $errStr . "\n" .
                    '   Warning in file ' . $errFile . '(' . $errLine . ')' . "\n";
            break;
        case E_USER_NOTICE:
            $str = 'E_USER_NOTICE  : ' . $errStr . "\n" .
                    '   Notice in file ' . $errFile . '(' . $errLine . ')' . "\n";
            break;
        case E_ERROR:
            $str = 'E_ERROR : ' . $errStr . "\n" .
                    '   FATAL ERROR in file ' . $errFile . '(' . $errLine . ')' . "\n";
            $haldPro = TRUE;
            break;
        case E_WARNING:
            $str = 'E_WARNING : ' . $errStr . "\n" .
                    '   WARNING in file ' . $errFile . '(' . $errLine . ')' . "\n";
            break;
        case E_NOTICE:
            $str = 'E_NOTICE  : ' . $errStr . "\n" .
                    '   NOTICE in file ' . $errFile . '(' . $errLine . ')' . "\n";
            break;
        case E_STRICT:
            $str = 'E_STRICT  : ' . $errStr . "\n" .
                    '   E_STRICT in file ' . $errFile . '(' . $errLine . ')' . "\n";
            break;
        case E_RECOVERABLE_ERROR :
            $str = 'E_RECOVERABLE_ERROR  : ' . $errStr . "\n" .
                    '   RECOVERABLE ERROR in file ' . $errFile . '(' . $errLine . ')' . "\n";
            $haldPro = TRUE;
            break;
        default:
            $str = 'Unknown message (' . $errNo . ')  : ' . $errStr . "\n" .
                    '   Message in file ' . $errFile . '(' . $errLine . ')' . "\n";
    }

    if ($f !== FALSE) {
        fwrite($f, $str);
        //gether additional info
        //fwrite($f, "SESSION data: \n" . print_r($_SESSION, true));
        //fwrite($f, "SERVER data: \n" . print_r($_SERVER, true));
        fwrite($f, "REQUEST data: \n" . print_r($_REQUEST, true));
        fclose($f);
    }

    if ($haldPro) {
        die('Advanced Access Manager catched an error. Please contact whimba@gmail.com for support');
    }
}

function aam_fatalerror_handler() {

    $error = error_get_last();

    if (!strpos($error['file'], 'advanced-access-manager')) {
        return;
    }

    if ($error !== NULL) {

        $f = fopen(WPACCESS_CACHE_DIR . '/error.log', 'a');
        $str = 'SHUTDOWN ' . $error['type'] . ': ' . $error['message'] . "\n" .
                'FATAL ERROR in file ' . $error['file'] . '(' . $error['line'] . ')' . "\n";

        if ($f !== FALSE) {
            fwrite($f, $str);
            //gether additional info
            //fwrite($f, "SESSION data: \n" . print_r($_SESSION, true));
            //fwrite($f, "SERVER data: \n" . print_r($_SERVER, true));
            fwrite($f, "REQUEST data: \n" . print_r($_REQUEST, true));
            fclose($f);
        }
    }
}

set_error_handler('aam_error_handler');
register_shutdown_function('aam_fatalerror_handler');
?>