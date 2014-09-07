<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/1/14
 */

namespace kije\Guestbook\inc;

use kije\Base\Runnable;
use kije\Layouting\Layout;
use kije\Layouting\View;
use kije\Routing\RouteHandler;


/**
 * Class Guestbook
 * @package kije\Guestbook
 */
class Guestbook implements Runnable
{

    /**
     * @var string
     */
    protected static $request_uri;

    /**
     * @var Layout
     */
    protected static $layout;

    /**
     * @param string $request_uri
     * @param $layout
     */
    public function __construct($request_uri = '/', $layout)
    {
        self::$request_uri = $this->sanitizeURI($request_uri);
        self::$layout = $layout;
    }

    protected function sanitizeURI($uri)
    {
        $uri = str_replace(PROJECT_URI, '', $uri);
        $uri = strtok($uri, '?');

        return $uri;
    }

    public function run()
    {
        $view = null;
        if (RouteHandler::hasRoute(self::getRequestURI())) {
            $view = RouteHandler::handleRoute(self::getRequestURI());
        } else {
            $view = $this->errorPage(404);
        }

        if ($view && $view instanceof View) {
            /** @var View|Layout $view */
            self::$layout->addChild($view);
        }

        self::$layout->addData('title', self::$layout->getData('title') . ' &ndash; Guestbook');
        self::$layout->render();
    }

    /**
     * @return string
     */
    public static function getRequestURI()
    {
        return self::$request_uri;
    }

    /**
     * @param $error_code
     * @return bool|mixed|null
     */
    protected function errorPage($error_code)
    {
        http_response_code($error_code);

        if (RouteHandler::hasRoute($error_code)) {
            return RouteHandler::handleRoute($error_code);
        } else {
            if (!array_key_exists($error_code, $GLOBALS['HTTP_STATUS_CODES'])) {
                $error_code = 418; // wrong error code? ok, lets joke a little bit... :D
            }

            http_response_code($error_code);
            echo $error_code . ' - ' . $GLOBALS['HTTP_STATUS_CODES'][$error_code];
            exit;

        }
    }

    public static function setLayout(Layout $layout) {
        self::$layout = $layout;
    }

    public static function getLayout() {
        return self::$layout;
    }
}
