<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */
namespace kije\Guestbook;

use kije\Guestbook\Routes\LoginRoute;
use kije\Guestbook\Routes\LogoutRoute;
use kije\Guestbook\Routes\OverviewRoute;
use kije\Routing\RouteHandler;


RouteHandler::registerRoute(new LoginRoute('/login', array('/l', '/log-in'), true));
RouteHandler::registerRoute(new LogoutRoute('/logout'));
RouteHandler::registerRoute(new OverviewRoute());