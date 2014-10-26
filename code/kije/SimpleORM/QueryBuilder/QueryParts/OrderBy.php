<?php


namespace kije\SimpleORM\QueryBuilder\QueryParts;


/**
 * Class OrderBy
 * @package kije\SimpleORM\QueryBuilder\QueryParts
 */
class OrderBy extends AbstractQueryPart
{
    protected $columns;

    public function add($col, $desc = false) {
        $this->columns[$col] = $desc ? 'DESC' : 'ASC';

        return $this;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if (!empty($this->columns)) {
            $sql = ' ORDER BY ';

            $i = 0;
            $count = count($this->columns);
            foreach($this->columns as $col => $order) {
                $i++;
                $sql .= '`'.$col.'` '.$order;

                if ($i > $count) {
                    $sql .= ',';
                }
            }

            return $sql;
        }

        return false;
    }
}
