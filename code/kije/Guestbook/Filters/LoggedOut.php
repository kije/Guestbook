<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Filters;


use kije\Base\SessionManager;

class LoggedOut extends Filter
{

    public function __construct()
    {
        parent::__construct('/');
    }

    public function check()
    {
        return !SessionManager::get('user', false);
    }
}
