<?php


namespace kije\Guestbook\Routes;


use kije\Base\SessionManager;
use kije\Guestbook\Filters\LoggedOut;
use kije\Guestbook\inc\Guestbook;
use kije\Guestbook\Models\User;
use kije\Layouting\Flash;
use kije\Routing\RouteHandler;
use \kije\Guestbook\Views\Register as RegisterView;

class Register extends Route
{

    /**
     * @var RegisterView
     */
    protected $view;

    public function __construct($uri = '/register', $alias_uris = null, $alias_redirect = true)
    {
        parent::__construct($uri, new LoggedOut(), $alias_uris, $alias_redirect);
        $this->view = new RegisterView();
    }

    public function handle()
    {
        Guestbook::getLayout()->appendData('title', 'Register', ' - ');
        Guestbook::getLayout()->addChild($this->view);

        if (!empty($_POST)) {
            if (!empty($_POST['username']) &&
                !empty($_POST['password']) &&
                !empty($_POST['password-repeat']) &&
                !empty($_POST['mail'])
            ) {
                if (!User::query()->where()->equals('username', $_POST['username'])->findOne()) {
                    if ($_POST['password'] === $_POST['password-repeat']) {
                        $new_user = User::create();
                        $new_user->username = $_POST['username'];
                        $new_user->setPassword($_POST['password']);
                        $new_user->mail = $_POST['mail'];
                        $new_user->role = User::ROLE_USER;

                        if ($new_user->save()) {
                            SessionManager::set('user', $new_user);

                            \kije\Layouting\Flash::add(
                                'Successfully registered!',
                                Flash::TYPE_SUCCESS
                            );
                        } else {
                            \kije\Layouting\Flash::add(
                                'Could not create User. Please retry it later again or try an other Username!',
                                Flash::TYPE_ERROR
                            );
                        }
                    } else {
                        \kije\Layouting\Flash::add(
                            'Password-Fields do not match!',
                            Flash::TYPE_WARNING
                        );
                    }
                } else {
                    \kije\Layouting\Flash::add(
                        'Could not create User. Please retry it later again or try an other Username!',
                        Flash::TYPE_ERROR
                    );
                }
            } else {
                \kije\Layouting\Flash::add('Please fill in all information!', Flash::TYPE_WARNING);
            }


            RouteHandler::redirect($this->uri, false, 302); // reload page
        }

    }
}
