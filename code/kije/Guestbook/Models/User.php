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
    const PASSWORD_HASH_ALGO = PASSWORD_DEFAULT;
    const PASSWORD_HASH_COST = 12;

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

    public static function login($username, $password)
    {
        if (empty($username) || empty($password)) {
            return false;
        }

        /** @var User $user */
        $user = self::query()->where()->equals('username', $username)->findOne();

        if ($user) {
            if ($user->verifyPassword($password)) {
                return $user;
            }
        }

        return false;
    }



    protected function verifyPassword($password)
    {
        if (!password_verify($password, $this->password)) {
            return false;
        }

        if (
        password_needs_rehash(
            $this->password,
            self::PASSWORD_HASH_ALGO,
            array('cost' => self::PASSWORD_HASH_COST)
        )
        ) {
            $this->setPassword($password);
            $this->save();
        }

        return true;
    }

    public function setPassword($password)
    {
        $hash = $this->hashPassword($password);
        if ($hash) {
            $this->set('password', $hash);
        }
    }

    /**
     * @param $password
     * @return bool|string
     */
    public static function hashPassword($password)
    {
        return password_hash($password, self::PASSWORD_HASH_ALGO, array('cost' => self::PASSWORD_HASH_COST));
    }
}
