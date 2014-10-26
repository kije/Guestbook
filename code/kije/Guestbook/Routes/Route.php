<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Routes;


abstract class  Route extends \kije\Routing\Route
{

    public function __construct($uri, $filters = null, $alias_uris = null, $alias_redirect = false)
    {
        parent::__construct($uri, array($this, 'handle'), $filters, $alias_uris, $alias_redirect);
    }

    public abstract function handle();

}
