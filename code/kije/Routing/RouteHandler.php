<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/6/14
 */

namespace kije\Routing;

use kije\Guestbook\Routes\Route;

// todo implement parametrized route

/**
 * Class RouteHandler
 * @package kije\Routing
 */
class RouteHandler
{
    /**
     * @var Route[]
     */
    protected static $routes = array();

    /**
     * @param Route $route
     * @return bool
     */
    public static function registerRoute(Route $route)
    {
        if ($route) {
            self::$routes[$route->getUri()] = $route;

            $alias = $route->getAliasUris();
            if (!empty($alias)) {
                foreach ($alias as $alias_uri) {
                    self::$routes[$alias_uri] = $route;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param $uri
     * @return bool
     */
    public static function removeRoute($uri)
    {
        if (self::hasRoute($uri)) {
            unset(self::$routes[$uri]);

            return true;
        }

        return false;
    }

    /**
     * @param string $uri
     * @return boolean
     */
    public static function hasRoute($uri)
    {
        return array_key_exists($uri, self::$routes);
    }

    /**
     * @param $uri
     * @return bool|mixed
     */
    public static function handleRoute($uri)
    {

        if ($route = self::getRoute($uri)) {
            if ($uri !== $route->getUri() && in_array($uri, $route->getAliasUris()) && $route->shouldAliasRedirect()) {
                self::redirect($route);
            } else {
                return $route->run();
            }
        }

        return false;
    }

    /**
     * @param $uri
     * @return bool|Route
     */
    public static function getRoute($uri)
    {
        if (self::hasRoute($uri)) {
            return self::$routes[$uri];
        }

        return false;
    }

    /**
     * @param string|Route $location
     * @param bool $preserve_current_get_params
     * @param int $redirect_code
     */
    public static function redirect($location, $preserve_current_get_params = true, $redirect_code = 301)
    {
        if ($location instanceof Route) {
            $location = $location->getUri();
        }

        if ($preserve_current_get_params && !empty($_GET)) {
            $location .= (parse_url($location, PHP_URL_QUERY) ? '&' : '?') . http_build_query($_GET);
        }

        header('Location: ' . PROJECT_URI . $location, true, $redirect_code);
        echo $redirect_code . ' - ' . $GLOBALS['HTTP_STATUS_CODES'][$redirect_code] . ': ' . PROJECT_URL . $location;
        exit();
    }
}
