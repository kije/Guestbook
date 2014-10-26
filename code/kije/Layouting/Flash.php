<?php


namespace kije\Layouting;


use kije\Base\SessionManager;

class Flash
{
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';


    public static function add($message, $type = self::TYPE_INFO)
    {
        static $i = 0;
        $i++;
        SessionManager::set('Layout.Flash.' . $type.'.'.$i, $message);
    }

    public static function get()
    {
        return SessionManager::get('Layout.Flash');
    }

    public static function emptyFlashes()
    {
        return SessionManager::delete('Layout.Flash');
    }
}
