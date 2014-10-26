<?php


namespace kije\SimpleORM\QueryBuilder\QueryParts;

use kije\SimpleORM\QueryBuilder\QueryBuilder;
use kije\SimpleORM\QueryBuilder\QueryPart;


/**
 * Class AbstractQueryPart
 * @package kije\SimpleORM\QueryBuilder\QueryParts
 *
 */
abstract class AbstractQueryPart implements QueryPart
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;
    protected $data = array();

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(&$queryBuilder)
    {
        $this->queryBuilder = &$queryBuilder;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @see QueryBuilder::findMany
     */
    public function findMany()
    {
        return $this->queryBuilder->findMany();
    }

    /**
     * @see QueryBuilder::findOne
     */
    public function findOne()
    {
        return $this->queryBuilder->findOne();
    }

    /**
     * @return Where|null
     * @see QueryBuilder::where
     */
    public function &where()
    {
        return $this->queryBuilder->where();
    }

    /**
     * @return OrderBy|null
     * @see QueryBuilder::orderBy
     */
    public function &orderBy()
    {
        return $this->queryBuilder->orderBy();
    }

    /**
     * @param $limit
     * @param null $offset
     * @return Limit|null
     * @see QueryBuilder::limit
     */
    public function &limit($limit, $offset = null)
    {
        return $this->queryBuilder->limit($limit, $offset);
    }

    protected function addData($data, $key = false)
    {
        if (!$key) {
            $key = $this->getUniqueDataIdentifier();
        }

        $this->data[$key] = $data;

        return $key;
    }

    /**
     * @return string
     */
    protected function getUniqueDataIdentifier()
    {
        static $num = 0;
        $num++;
        return "data_" . md5(get_called_class() . $num);
    }
}
