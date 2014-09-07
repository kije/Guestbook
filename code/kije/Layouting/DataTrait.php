<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Layouting;


trait DataTrait
{

    protected $data = array();

    public function addData($name, $data)
    {
        if ($data) {
            $this->data[$name] = $data;
        }
    }

    public function getData($name, $default = '')
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }
} 