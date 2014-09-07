<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/6/14
 */

namespace kije\Routing;


use kije\Base\Runnable;
use kije\Routing\inc\Exception;

class Route implements Runnable
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var callable
     */
    protected $handle;

    /**
     * @var Filter[]
     */
    protected $filters;

    /**
     * @var string[]
     */
    protected $alias_uris;

    /**
     * @var boolean
     */
    protected $alias_redirect;

    /**
     * @param string $uri
     * @param callable $handle
     * @param Filter|Filter[] $filters
     * @param string[] $alias_uris
     * @param bool $alias_redirect
     * @throws Exception
     */
    public function __construct($uri, callable $handle, $filters = null, $alias_uris = null, $alias_redirect = false)
    {

        if (!is_callable($handle)) {
            throw new Exception('$handle must be callable!');
        }

        $this->uri = $uri;
        $this->handle = $handle;

        if ($filters) {
            if (!is_array($filters) && $filters instanceof Filter) { // if there is only one filter
                $this->filters = array($filters);
            } else {
                $this->filters = $filters;
            }
        }

        $this->alias_uris = $alias_uris;
        $this->alias_redirect = $alias_redirect;
    }

    public final function run()
    {
        if (!empty($this->filters)) {
            foreach($this->filters as $filter) {
                $filter->run();
            }
        }
        return call_user_func($this->handle);
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return callable
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return \kije\Routing\Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return \string[]
     */
    public function getAliasUris()
    {
        return $this->alias_uris;
    }

    /**
     * @return boolean
     */
    public function shouldAliasRedirect()
    {
        return $this->alias_redirect;
    }


}