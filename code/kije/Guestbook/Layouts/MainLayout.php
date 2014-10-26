<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Layouts;


use kije\Guestbook\Views\Base;
use kije\Layouting\Flash;
use kije\Layouting\Layout;

class MainLayout extends Layout
{
    public function __construct()
    {
        parent::__construct(new Base());
    }

    public function render($return = false) {
        if ($messages = Flash::get()) {
            foreach($messages as $type => $msgs) {
                foreach ($msgs as $message) {
                    $view = new \kije\Guestbook\Views\Flash();
                    $view->type = $type;
                    $view->message = $message;

                    $this->rootView->addChild($view, 'flash');
                }
            }
        }
        parent::render($return);
    }
}
