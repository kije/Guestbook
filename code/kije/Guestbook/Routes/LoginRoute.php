<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Routes;


use kije\Guestbook\Filters\LoggedOutFilter;
use kije\Guestbook\inc\Guestbook;
use kije\Guestbook\Views\LoginView;
use kije\Routing\RouteHandler;

class LoginRoute extends Route
{

    /**
     * @var \kije\Guestbook\Views\LoginView
     */
    protected $view;

    public function __construct($uri = '/login', $alias_uris = null, $alias_redirect = true)
    {
        parent::__construct($uri, new LoggedOutFilter(), $alias_uris, $alias_redirect);
        $this->view = new LoginView();
    }

    public function handle()
    {
        Guestbook::getLayout()->addData('title', 'Login ' . Guestbook::getLayout()->getData('title'));
        Guestbook::getLayout()->addChild($this->view);

        if (!empty($_POST)) {
            $_SESSION['LOGIN'] = true;

            RouteHandler::redirect($this->uri, false, 302); // reload page
        }

    }
}