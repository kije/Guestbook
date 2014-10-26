<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Routes;


use kije\Base\SessionManager;
use kije\Guestbook\Filters\LoggedIn;
use kije\Layouting\Flash;
use kije\Routing\RouteHandler;

class Logout extends Route
{

    public function __construct($uri = '/logout', $alias_uris = null, $alias_redirect = true)
    {
        parent::__construct($uri, new LoggedIn(), $alias_uris, $alias_redirect);
    }

    public function handle()
    {
        SessionManager::delete('user');
        Flash::add('Successfully logged out!');
        RouteHandler::redirect($this->uri, false, 302); // reload page
    }
}
