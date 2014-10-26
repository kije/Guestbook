<?php


namespace kije\SimpleORM\QueryBuilder\QueryParts;


/**
 * Class Where
 * @package kije\SimpleORM\QueryBuilder\QueryParts
 */
class Where extends AbstractQueryPart
{

    protected $sql = array();

    protected $data = array();
    protected $needs_linking = false;

    public function __construct(&$queryBuilder)
    {
        parent::__construct($queryBuilder);
    }

    public function equals($column, $value)
    {
        if ($this->needs_linking) {
            $this->_and();
        }

        $this->sql[] = '`' . $column . '` = :' . $this->addData($value);

        $this->needs_linking = true;

        return $this;
    }

    public function _and()
    {
        $this->sql[] = 'AND';

        $this->needs_linking = false;

        return $this;
    }

    public function notEquals($column, $value)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = '`' . $column . '` != :' . $this->addData($value);

        $this->needs_linking = true;

        return $this;
    }

    public function equalsColumn($column1, $column2)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = '`' . $column1 . '` = `' . $column2 . '`';

        $this->needs_linking = true;

        return $this;
    }

    public function notEqualsColumn($column1, $column2)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = '`' . $column1 . '` != `' . $column2 . '`';

        $this->needs_linking = true;

        return $this;
    }

    public function like($column, $pattern)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = '`' . $column . '` LIKE :' . $this->addData($pattern);

        $this->needs_linking = true;

        return $this;
    }

    public function notLike($column, $pattern)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = '`' . $column . '` NOT LIKE :' . $this->addData($pattern);

        $this->needs_linking = true;

        return $this;
    }

    public function isNull($column)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = '`' . $column . '` IS NULL';

        $this->needs_linking = true;

        return $this;
    }

    public function isNotNull($column)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = '`' . $column . '` IS NOT NULL';

        $this->needs_linking = true;

        return $this;
    }

    public function in($column, array $values)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
    }

    public function notIn($column, array $values)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
    }

    public function between($value1, $value2)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
    }


    public function notBetween($value1, $value2)
    {
        if ($this->needs_linking) {
            $this->_and();
        }
    }


    // linking

    public function regexp($column, $pattern)
    {
        if ($this->needs_linking) {
            $this->_and();
        }

    }

    public function raw($raw, $data = array())
    {
        if ($this->needs_linking) {
            $this->_and();
        }
        $this->sql[] = $raw;
        $this->data = array_merge($this->data, $data);

        $this->needs_linking = true;

        return $this;
    }

    public function _or()
    {
        $this->sql[] = 'OR';

        $this->needs_linking = false;

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
