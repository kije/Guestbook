<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Filters;


use kije\Routing\RouteHandler;

class LoggedInFilter extends Filter {

    public function __construct($redirect_url = '/') {
        parent::__construct('/login', array('redirect' => $redirect_url));
    }

    public function check()
    {
        return isset($_SESSION['LOGIN']); // todo
    }
}