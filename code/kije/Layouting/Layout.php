<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Layouting;


abstract class Layout {
    /**
     * @var View
     */
    protected $rootView;

    public function __construct(View $rootView) {
        $this->rootView = $rootView;
    }

    public function render($return = false) {
        $this->rootView->render($return);
    }

    public function addChild(View $child, $area = 'default') {
        $this->rootView->addChild($child, $area);
    }

    public function renderChildren($area = 'default') {
        $this->rootView->addChild($area);
    }

    public function addData($name, $data)
    {
        $this->rootView->addData($name, $data);
    }

    public function getData($name, $default = '')
    {
        return $this->rootView->getData($name, $default);
    }
} 