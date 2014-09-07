<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/6/14
 */

namespace kije\Routing;


use kije\Base\Runnable;
use kije\Routing\inc\Exception;

/**
 * Class Filter
 * Filter for Routing
 *
 * A routing filter specifies a rule(set) which must be satisfied before a route is valid.
 * Example: A login filter can be used to only allow access to a page if the user is logged in. Otherwise the user will
 * be redirected to the login page.
 *
 * @package kije\Routing
 */
class Filter implements Runnable
{
    /**
     * @var callable
     */
    protected $filter;

    /**
     * @var callable
     */
    protected $action;

    /**
     * @var mixed
     */
    protected $action_params;

    /**
     * @param callable $filter function that returns true, if the filter matches, or false if it doesn't
     * @param Route|Runnable|callable|string $action the action that should be executed if the filter doesn't match
     * @param null|mixed $action_params
     * @throws inc\Exception
     */
    public function __construct(callable $filter, $action, $action_params = null)
    {
        $this->filter = $filter;
        $this->action_params = $action_params;

        if (!is_callable($action)) {
            if ($action instanceof Route || is_string($action)) {
                $action = function () use ($action) {
                    $_GET = array_merge($_GET, $this->action_params);
                    RouteHandler::redirect($action, true, 302);
                };
            } else if ($action instanceof Runnable) {
                $action = function () use ($action) {
                    return $action->run($this->action_params);
                };
            } else {
                throw new Exception('$action must be either of type Route, Runnable, string or callable!');
            }
        } else if ($action_params) {
            $action = function () use ($action) {
                return call_user_func($this->action, array($this->action_params));
            };
        }

        $this->action = $action;
    }


    public final function run()
    {
        if (!call_user_func($this->filter)) {
            return call_user_func($this->action);
        }

        return true;
    }
}
