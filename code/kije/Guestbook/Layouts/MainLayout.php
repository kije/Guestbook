<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Layouts;


use kije\Guestbook\Views\BaseView;
use kije\Layouting\Layout;

class MainLayout extends Layout
{
    public function __construct()
    {
        parent::__construct(new BaseView());
    }
} 