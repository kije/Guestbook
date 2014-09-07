<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/6/14
 */

use kije\Guestbook\inc\Guestbook;
use kije\Guestbook\Layouts\MainLayout;

session_name('Guestbook');
@session_start();

$request_uri = $_SERVER['REQUEST_URI'];

$app = new Guestbook($request_uri, new MainLayout());

require_once 'routes.php';


return $app;