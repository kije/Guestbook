<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Filters;


use kije\Routing\RouteHandler;

class LoggedOutFilter extends Filter {

    public function __construct() {
        parent::__construct('/');
    }

    public function check()
    {
        return !isset($_SESSION['LOGIN']); // todo
    }
}