<?php


namespace kije\SimpleORM\QueryBuilder;


use kije\SimpleORM\ORMException;
use kije\SimpleORM\QueryBuilder\QueryParts\Limit;
use kije\SimpleORM\QueryBuilder\QueryParts\OrderBy;
use kije\SimpleORM\QueryBuilder\QueryParts\Where\Where;

/**
 * Class QueryBuilder
 * @package kije\SimpleORM\QueryBuilder
 */
class QueryBuilder implements QueryPart
{

    /**
     * @var string
     */
    protected $class;


    /**
     * @var Where|null
     */
    protected $where;
    /**
     * @var OrderBy|null
     */
    protected $orderBy;
    /**
     * @var Limit|null
     */
    protected $limit;

    /**
     * @param string $class
     * @throws ORMException
     */
    public function __construct($class)
    {
        $this->class = $class;
        $this->conditions = array();
    }

    public function findMany()
    {
        $query = $this->getSql();

        if (!empty($query)) {
            return call_user_func_array(array($this->class, 'fetchRaw'), array($query, $this->getData()));
        }

        return false;
    }

    public function getSql()
    {
        $sql = '';

        if (!empty($this->where)) {
            $sql .= $this->where->getSql() . ' ';
        }

        if (!empty($this->orderBy)) {
            $sql .= $this->orderBy->getSql() . ' ';
        }

        if (!empty($this->limit)) {
            $sql .= $this->limit->getSql() . ' ';
        }

        return $sql;
    }

    public function getData()
    {
        $data = array();

        if (!empty($this->where)) {
            $data = array_merge($data, $this->where->getData());
        }

        if (!empty($this->orderBy)) {
            $data = array_merge($data, $this->orderBy->getData());
        }

        if (!empty($this->limit)) {
            $data = array_merge($data, $this->limit->getData());
        }

        return $data;
    }

    public function findOne()
    {
        $this->limit(1);

        $query = $this->getSql();

        if (!empty($query)) {
            $res = call_user_func_array(array($this->class, 'fetchRaw'), array($query, $this->getData()));

            if (!empty($res)) {
                return $res[0];
            }
        }

        return false;
    }

    /**
     * @return Limit|null
     */
    public function &limit($limit, $offset = null)
    {
        if (empty($this->limit)) {
            $this->limit = new Limit($this, $limit, $offset);
        } else {
            $this->limit->setLimit($limit);

            if ($offset !== null) {
                $this->limit->setOffset($offset);
            }
        }

        return $this->limit;
    }

    /**
     * @return Where|null
     */
    public function &where()
    {
        if (empty($this->where)) {
            $this->where = new Where($this);
        }

        return $this->where;
    }

    /**
     * @return OrderBy|null
     */
    public function &orderBy()
    {
        if (empty($this->orderBy)) {
            $this->orderBy = new OrderBy($this);
        }

        return $this->orderBy;
    }
}
