<?php

namespace kije\SimpleORM\QueryBuilder;



/**
 * Interface QueryPart
 * @package kije\SimpleORM\QueryBuilder\QueryParts
 */
interface QueryPart
{
    /**
     * @return string
     */
    public function getSql();

    public function getData();

}
