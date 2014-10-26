<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */
namespace kije\Guestbook;

use kije\Guestbook\Routes\Create;
use kije\Guestbook\Routes\Login;
use kije\Guestbook\Routes\Logout;
use kije\Guestbook\Routes\Overview;
use kije\Guestbook\Routes\Register;
use kije\Routing\RouteHandler;


RouteHandler::registerRoute(new Login('/login', array('/l', '/log-in'), true));
RouteHandler::registerRoute(new Logout('/logout', array('/log-out'), true));
RouteHandler::registerRoute(new Register('/register', array('/r'), true));
RouteHandler::registerRoute(new Overview('/', array('/overview', '/o'), false));
RouteHandler::registerRoute(new Create('/create', array('/post/create', '/c'), true));
