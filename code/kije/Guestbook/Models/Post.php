<?php


namespace kije\Guestbook\Models;


use kije\SimpleORM\ORM;

/**
 * Class Post
 * @package kije\Guestbook\Models
 *
 * @property string $title
 * @property string $content
 * @property int $date
 * @property User $user
 * @property Post $reply_to
 */
class Post extends ORM
{
    public static function tableName()
    {
        return 'Posts';
    }

    public static function columns()
    {
        return array_merge(
            parent::columns(),
            array(
               'title',
               'content',
               'date',
               'reply_to',
               'user',
            )
        );
    }

    public static function fks() {
        return array(
            'user' => User::getClassName(),
            'reply_to' => Post::getClassName()
        );
    }

    /**
     * @return Post|false
     */
    public function getReplies() {
        return static::query()->where()->equals('reply_to', $this->id)->findMany();
    }
}
