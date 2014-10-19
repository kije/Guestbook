<?php


namespace kije\SimpleORM;

use kije\SimpleORM\QueryBuilder\QueryBuilder;


/**
 * Class ORM
 * @package kije\SimpleORM
 *
 * @property int $id
 * @property string $active
 * @property string $deleted
 */
class ORM implements ORMInterface
{
    /**
     * @var string
     */
    protected static $save_sql;

    /**
     * @var string
     */
    protected static $fetch_all_sql;

    /**
     * @var string
     */
    protected static $delete_sql;

    /**
     * @var string
     */
    protected static $pk_where_sql;

    /**
     * @var string
     */
    protected static $truncate_sql;

    /**
     * @var string
     */
    protected static $drop_sql;
    /**
     * @var array
     */
    protected $data = array();

    /**
     *
     */
    protected function __construct()
    {

    }

    public static function fetchAll()
    {
        if (empty(static::$fetch_all_sql)) {
            static::$fetch_all_sql = 'SELECT ';
            static::$fetch_all_sql .= '`' . implode('`,`', static::columns()) . '` ';
            static::$fetch_all_sql .= 'FROM `' . static::tableName() . '`';
            static::$fetch_all_sql .= ';';
        }

        $res = static::execute(
            static::$fetch_all_sql,
            array(),
            !\DB::dbh()->inTransaction(),
            $stmt
        );

        /** @var \PDOStatement $stmt */


        if ($res) {
            $instances = array();

            while (($row = $stmt->fetchObject())) {
                $instances[] = static::createFromObject($row);
            }

            return $instances;
        }

        return $res;
    }

    public static function tableName()
    {
        return '';
    }

    /**
     * Executes a sql query and binds data to it
     *
     * @param string $sql The Sql query to execute
     * @param array $data The data which should be bound to it.
     * @param bool $inTransaction should the query be executed in a transaction
     * @param null|\PDOStatement $stmt passed by reference. if provided, prepared PDO statement will be assigned for later use (e.g. fetching results of select query)
     * @param null|\PDOException $exception passed by reference. if there is an exception, it will be assigned
     * @return bool result of the query
     */
    public static function execute($sql, $data = array(), $inTransaction = false, &$stmt = null)
    {
        if ($inTransaction) {
            \DB::dbh()->beginTransaction();
        }

        try {
            $save_stmt = \DB::dbh()->prepare($sql);

            $stmt = $save_stmt;

            $res = $save_stmt->execute($data);

        } catch (\PDOException $e) {

            if ($inTransaction) {
                \DB::dbh()->rollBack();
            }

            throw $e;
        }

        if ($inTransaction) {
            \DB::dbh()->commit();
        }

        return $res;
    }

    public static function createFromObject(\stdClass $obj, $use__class_property = false)
    {
        $class = get_called_class();

        // has class property (e.g. when serialized json object)
        if (property_exists($obj, '_class') && $use__class_property) {
            $class = $obj->_class;
        }

        $instance = new $class();

        if ($instance instanceof ORMInterface) {
            foreach (static::columns() as $col) {
                if (property_exists($obj, $col)) {
                    $instance->set($col, $obj->$col);
                }
            }

            return $instance;
        }

        return null;
    }

    public static function columns()
    {
        return array(
            'id',
            'active',
            'deleted'
        );
    }

    public static function truncate()
    {
        if (empty(static::$truncate_sql)) {
            static::$truncate_sql = 'TRUNCATE `' . static::tableName() . '`;';
        }

        return static::execute(static::$truncate_sql, null, !\DB::dbh()->inTransaction());

    }

    public static function drop()
    {
        if (empty(static::$drop_sql)) {
            static::$drop_sql = 'DROP `' . static::tableName() . '`;';
        }

        return static::execute(static::$drop_sql, null, !\DB::dbh()->inTransaction());
    }

    /**
     * @var string
     */
    protected static $fetch_sql;

    public static function fetch($pk)
    {
        if (empty(static::$fetch_sql)) {
            static::$fetch_sql = 'SELECT ';
            static::$fetch_sql .= '`' . implode('`,`', static::columns()) . '` ';
            static::$fetch_sql .= 'FROM `' . static::tableName() . '` ';
            static::$fetch_sql .= static::_buildPKWhere();
            static::$fetch_sql .= 'LIMIT 1;';
        }

        /** @var \PDOStatement $stmt */
        $stmt = null;

        $res = static::execute(
            static::$fetch_sql,
            $pk,
            !\DB::dbh()->inTransaction(),
            $stmt
        );

        if ($res) {
            $row = $stmt->fetchObject();

            if (!empty($row)) {
                return static::createFromObject($row);
            }
        }

        return $res;
    }

    public function delete($soft_delete = true)
    {
        if ($soft_delete) {
            $this->deleted = true;
            return $this->save();
        }

        if (empty(static::$delete_sql)) {
            static::$delete_sql = 'DELETE FROM `' . $this->tableName() . '`';
            static::$delete_sql .= $this->_buildPKWhere();
            static::$delete_sql .= ';';
        }

        return $this->execute(
            static::$delete_sql,
            $this->mapValuesToColumns(true, $this->pk()),
            !\DB::dbh()->inTransaction()
        );
    }

    public function save()
    {
        if (empty(static::$save_sql)) {
            $cols = $this->columns();

            static::$save_sql = 'INSERT INTO `' . $this->tableName() . '`';
            static::$save_sql .= ' (`' . implode('`,`', $cols) . '`)';
            static::$save_sql .= ' VALUES (:' . implode(',:', $cols) . ')';

            static::$save_sql .= ' ON DUPLICATE KEY UPDATE ';

            $updates = array();
            foreach ($cols as $col) {
                $updates[] = '`' . $col . '` = :' . $col;
            }

            static::$save_sql .= implode(',', $updates);

            static::$save_sql .= ';';
        }

        return $this->execute(static::$save_sql, $this->mapValuesToColumns(), !\DB::dbh()->inTransaction());
    }

    /**
     * @param bool $include_empty_values
     * @param bool $only Array of columns to include. If not false, only these columns will be included
     * @return array
     */
    protected function mapValuesToColumns($include_empty_values = true, $only = false)
    {
        $res = array();

        $cols = $this->columns();

        if ($only !== false) {
            if (!is_array($only)) {
                $only = array($only);
            }

            $cols = array_intersect($cols, $only);
        }

        foreach ($cols as $col) {
            $val = $this->get($col, null);

            if (!is_scalar($val) &&
                $this->fks() &&
                in_array(get_class($val), $this->fks()) &&
                $val instanceof ORMInterface
            ) {
                /** @var ORMInterface $val */
                $val = $val->get($val->pk());
            }

            if (!empty($val) || $include_empty_values) {
                $res[$col] = $val;
            }
        }

        return $res;
    }

    public function get($name, $default = false)
    {
        if ($this->has($name)) {
            return $this->data[$name];
        }

        return $default;
    }

    public function has($name)
    {
        return array_key_exists($name, $this->data);
    }

    public static function fks()
    {
        return false;
    }

    protected static function _buildPKWhere()
    {
        if (empty(static::$pk_where_sql)) {
            static::$pk_where_sql = ' WHERE ';

            $pk = static::pk();

            if (!is_array($pk)) {
                $pk = array($pk);
            }

            $wheres = array();
            foreach ($pk as $_pk) {
                $wheres[] = '`' . $_pk . '` = :' . $_pk;
            }

            static::$pk_where_sql .= implode(' AND ', $wheres);
        }

        return static::$pk_where_sql;
    }

    public static function pk()
    {
        return 'id';
    }

    public function refresh()
    {

    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __unset($name)
    {
        if ($this->has($name)) {
            unset($this->data[$name]);
        }
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();

        $obj->_data = $this->data;

        foreach ($this->mapValuesToColumns(false) as $col => $val) {
            $obj->$col = $val;
        }

        $obj->_columns = $this->columns();
        $obj->_pk = $this->pk();
        $obj->_fk = $this->fks();

        $obj->_class = $this->getClassName();

        return $obj;
    }

    public static function getClassName()
    {
        return get_called_class();
    }

    public function create($pk)
    {
        $instance = static::fetch($pk);

        if (!empty($instance)) {
            $instance = new static();

            foreach ($pk as $col => $val) {
                $instance->set($col, $val);
            }
        }


        return $instance;
    }



    public static function query()
    {
        return new QueryBuilder(static::getClassName());
    }

    public static function fetchRaw($partial_query, array $data = array()) {
        $sql = 'SELECT ';
        $sql .= '`' . implode('`,`', static::columns()) . '` ';
        $sql .= 'FROM `' . static::tableName() . '` ';
        $sql .= $partial_query;
        $sql .= ';';

        /** @var \PDOStatement $stmt */
        $res = static::execute(
            $sql,
            $data,
            !\DB::dbh()->inTransaction(),
            $stmt
        );

        if ($res) {
            $instances = array();

            while (($row = $stmt->fetchObject())) {
                $instances[] = static::createFromObject($row);
            }

            return $instances;
        }

        return $res;
    }
}
