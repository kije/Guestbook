<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Filters;


use kije\Base\SessionManager;
use kije\Guestbook\Models\User;

class LoggedIn extends Filter
{

    public function __construct($redirect_url = '/')
    {
        parent::__construct('/login', array('redirect' => $redirect_url));
    }

    public function check()
    {
        $user = SessionManager::get('user', false);
        if (!$user) {
            return false;
        }

        return !!User::query()
                     ->where()
                     ->equals('id', $user->get('id'))
                     ->_and()
                     ->equals('deleted', 0)
                     ->_and()
                     ->equals('active', 1)
                     ->findOne();
    }

    protected function onFail() {
        SessionManager::delete('user');
    }
}
