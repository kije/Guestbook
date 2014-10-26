<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Routes;


use kije\Base\SessionManager;
use kije\Guestbook\Filters\LoggedOut;
use kije\Guestbook\inc\Guestbook;
use kije\Guestbook\Models\User;
use kije\Layouting\Flash;
use kije\Routing\RouteHandler;
use \kije\Guestbook\Views\Login as LoginView;

class Login extends Route
{

    /**
     * @var LoginView
     */
    protected $view;

    public function __construct($uri = '/login', $alias_uris = null, $alias_redirect = true)
    {
        parent::__construct($uri, new LoggedOut(), $alias_uris, $alias_redirect);
        $this->view = new LoginView();
    }

    public function handle()
    {
        Guestbook::getLayout()->appendData('title', 'Login', ' - ');
        Guestbook::getLayout()->addChild($this->view);

        if (!empty($_POST)) {
            $user = User::login($_POST['username'], $_POST['password']);

            if ($user) {
                SessionManager::set('user', $user);
                Flash::add('Successfully logged in as '.$user->username.'!');
            } else {
                \kije\Layouting\Flash::add('Wrong username or password!', Flash::TYPE_ERROR);
            }

            RouteHandler::redirect($this->uri, false, 302); // reload page
        }

    }
}