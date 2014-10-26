<?php


namespace kije\Base;


class SessionManager
{

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $arr = &$_SESSION;

        if (false !== strpos($key, '.')) {
            $parts = explode('.', $key);
            $key = array_pop($parts);
            foreach ($parts as $part) {
                if (!array_key_exists($part, $arr)) {
                    return $default;
                }

                $arr = &$arr[$part];
            }
        }

        if (!array_key_exists($key, $arr)) {
            return $default;
        }

        return $arr[$key];
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $arr = &$_SESSION;

        if (false !== strpos($key, '.')) {
            $parts = explode('.', $key);
            $key = array_pop($parts);

            foreach($parts as $part) {
                if (array_key_exists($part, $arr)) {
                    $arr[$part] = array();
                }

                $arr = &$arr[$part];
            }

            $arr[$key] = $value;

        } else {
            $arr[$key] = $value;
        }
    }

    public static function delete($key) {
        $arr = &$_SESSION;

        if (false !== strpos($key, '.')) {
            $parts = explode('.', $key);
            $key = array_pop($parts);

            foreach($parts as $part) {
                if (!array_key_exists($part, $arr)) {
                   return false;
                }

                $arr = &$arr[$part];
            }

            unset($arr[$key]);

        } else {
            unset($arr[$key]);
        }

        return true;
    }

}
