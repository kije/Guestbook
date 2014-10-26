<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Filters;

/**
 * Class Filter
 * @package kije\Guestbook\Filters
 * @see \kije\Routing\Filter
 */
abstract class Filter extends \kije\Routing\Filter
{

    /**
     * @param callable|\kije\Base\Runnable|\kije\Routing\Route|string $action
     * @param null|mixed $action_params
     */
    public function __construct($action, $action_params = null)
    {
        parent::__construct(array($this, '_checkWrapper'), $action, $action_params);
    }

    protected function _checkWrapper()
    {
        $res = $this->check();

        if ($res) {
            $this->onSuccess();
        } else {
            $this->onFail();
        }

        return $res;
    }

    public abstract function check();


    protected function onSuccess()
    {

    }

    protected function onFail()
    {

    }
}
