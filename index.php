<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/1/14
 */

use kije\Base\SessionManager;
use kije\Guestbook\inc\Guestbook;

// compression
$ob_handler = null;
if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false && function_exists('ob_deflatehandler')) {
    $ob_handler = 'ob_deflatehandler';
} elseif (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false && function_exists('ob_gzhandler')) {
    $ob_handler = 'ob_gzhandler';
}


ob_start($ob_handler);

require_once 'inc/globals.inc.php';

/** @var Guestbook $app */
$app = require CODE_ROOT . '/kije/Guestbook/App.php';

$app->run();
ob_end_flush(); // might never reach this point -> app exits after rendering
