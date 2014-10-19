<?php


namespace kije\Guestbook\Models;


use kije\SimpleORM\ORM;

/**
 * Class User
 * @package kije\Guestbook\Models
 *
 * @property string $username
 * @property string $password
 * @property string $mail
 * @property string $role
 */
class User extends ORM
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';


    public static function tableName()
    {
        return 'Users';
    }

    public static function columns()
    {
        return array_merge(
            parent::columns(),
            array(
                'username',
                'password',
                'mail',
                'role'
            )
        );
    }

    public static function login($username, $password) {
        if (empty($username) || empty($password)) {
            return false;
        }

        $user = self::query()->where()->equals('username', $username)->findOne();

        if ($user) {
            // todo check password

            return $user;
        }

        return false;
    }
}
