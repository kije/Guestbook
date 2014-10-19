<?php


namespace kije\SimpleORM\QueryBuilder\QueryParts\Where;


use kije\SimpleORM\QueryBuilder\QueryParts\AbstractQueryPart;

/**
 * Class Where
 * @package kije\SimpleORM\QueryBuilder\QueryParts
 */
class Where extends AbstractQueryPart
{

    protected $sql = array();

    protected $data = array();

    public function __construct(&$queryBuilder)
    {
        parent::__construct($queryBuilder);
    }

    public function equals($column, $value)
    {
        $this->sql[] = '`'.$column.'` = :'.$this->addData($value);

        return $this;
    }

    public function notEquals($column, $value)
    {
        $this->sql[] = '`'.$column.'` != :'.$this->addData($value);

        return $this;
    }


    public function equalsColumn($column1, $column2)
    {
        $this->sql[] = '`'.$column1.'` = `'.$column2.'`';

        return $this;
    }

    public function notEqualsColumn($column1, $column2)
    {
        $this->sql[] = '`'.$column1.'` != `'.$column2.'`';

        return $this;
    }


    public function like($column, $pattern)
    {
        $this->sql[] = '`'.$column.'` LIKE :'.$this->addData($pattern);

        return $this;
    }

    public function notLike($column, $pattern)
    {
        $this->sql[] = '`'.$column.'` NOT LIKE :'.$this->addData($pattern);

        return $this;
    }


    public function isNull($column)
    {
        $this->sql[] = '`'.$column.'` IS NULL';

        return $this;
    }

    public function isNotNull($column)
    {
        $this->sql[] = '`'.$column.'` IS NOT NULL';

        return $this;
    }

    public function in($column, array $values)
    {

    }

    public function notIn($column, array $values)
    {

    }

    public function between($value1, $value2)
    {

    }

    public function notBetween($value1, $value2)
    {

    }

    public function regexp($column, $pattern)
    {

    }

    public function raw($raw, $data = array())
    {
        $this->sql[] = $raw;
        $this->data = array_merge($this->data, $data);

        return $this;
    }


    // linking
    public function _and()
    {
        $this->sql[] = 'AND';

        return $this;
    }

    public function _or()
    {
        $this->sql[] = 'OR';

        return $this;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if (!empty($this->sql)) {
            return ' WHERE ' . implode(' ', $this->sql);
        }

        return null;
    }
}
