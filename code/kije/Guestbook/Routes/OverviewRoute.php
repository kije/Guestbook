<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Routes;


use kije\Guestbook\Filters\LoggedInFilter;

class OverviewRoute extends Route {

    public function __construct($uri = '/', $alias_uris = null, $alias_redirect = true)
    {
        parent::__construct($uri, new LoggedInFilter($uri), $alias_uris, $alias_redirect);
    }

    public function handle()
    {
    }
} 