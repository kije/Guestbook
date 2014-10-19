<?php


namespace kije\SimpleORM\QueryBuilder\QueryParts;

/**
 * Class Limit
 * @package kije\SimpleORM\QueryBuilder\QueryParts
 */
class Limit extends AbstractQueryPart
{
    protected $limit;
    protected $offset;

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param null $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function __construct(&$queryBuilder, $limit, $offset = null)
    {
        parent::__construct($queryBuilder);

        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        $sql = ' LIMIT ';

        if ($this->offset !== null) {
            $sql .= intval($this->offset).',';
        }

        $sql .= intval($this->limit);

        return $sql;
    }
}
