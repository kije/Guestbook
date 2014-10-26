<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Layouting;


abstract class View
{

    const TEMPLATE_DIR = 'templates';
    protected $viewName;

    /**
     * @var array
     */
    protected $children;

    protected $data;


    public function __construct()
    {
        $this->viewName = substr(get_class($this), strripos(get_class($this), '\\') + 1);
        $this->children = array('default' => array());
        $this->data = array();
    }

    public function render($return = false)
    {
        if ($return) {
            ob_start();
        }

        include $this->getTemplatePath();


        if ($return) {
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        return null;
    }

    public function getTemplatePath()
    {
        $reflection = new \ReflectionClass($this);
        $dir = substr($reflection->getFileName(), 0, strripos($reflection->getFileName(), DIRECTORY_SEPARATOR));
        return $dir . '/' . self::TEMPLATE_DIR . '/' . $this->viewName . '.phtml';
    }


    public function addChild(View &$child, $area = 'default') {
        if ($child) {
            $this->children[$area][] = $child;
        }
    }

    public function renderChildren($area = 'default') {
        if (array_key_exists($area, $this->children)) {
            foreach($this->children[$area] as $child) {
                /** @var View $child */
                $child->render();
            }
        }
    }

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

    public function appendData($name, $data, $separator = '') {
        if (array_key_exists($name, $this->data)) {
            if (is_array($this->data)) {
                $this->data[$name][] = $data;
            } elseif (is_string($this->data)) {
                $this->data[$name] .= $separator.$data;
            } else {
                $this->data[$name] = $data;
            }
        } else {
            $this->data[$name] = $data;
        }
    }
}