<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Routes;


use kije\Guestbook\Filters\LoggedInFilter;
use kije\Guestbook\Filters\LoggedOutFilter;
use kije\Guestbook\inc\Guestbook;
use kije\Guestbook\Views\LoginView;
use kije\Routing\RouteHandler;

class LogoutRoute extends Route
{

    public function __construct($uri = '/logout', $alias_uris = null, $alias_redirect = true)
    {
        parent::__construct($uri, new LoggedInFilter(), $alias_uris, $alias_redirect);
    }

    public function handle()
    {
        unset($_SESSION['LOGIN']);
        RouteHandler::redirect($this->uri, false, 302); // reload page
    }
}