<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/1/14
 */

if (!defined('DEBUG')) {
    define('DEBUG', true);
}

// Define absolute Paths
define('INC_ROOT', __DIR__);
define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('PROJECT_ROOT', realpath(INC_ROOT . '/..'));
define('CODE_ROOT', realpath(PROJECT_ROOT . '/code'));

// URI & URL
define('PROJECT_URI', str_replace(DOC_ROOT, '', PROJECT_ROOT));
define(
    'PROJECT_URL',
    sprintf(
        '%s://%s%s',
        'http' . (!empty($_SERVER['HTTPS']) ? 's' : ''),
        (!empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_ADDR']),
        PROJECT_URI
    )
);

// Turn error reporting on/off
ini_set('display_errors', DEBUG);
error_reporting(E_ALL ^ E_DEPRECATED);

// activate Error log
ini_set("log_errors", true);
ini_set("error_log", PROJECT_ROOT . "/var/log/php-error.log");

// vars
require_once 'vars.inc.php';


require_once PROJECT_ROOT . '/inc/autoloader.php';
require_once PROJECT_ROOT . '/inc/db.config.php';
require_once PROJECT_ROOT . '/inc/DB.php';