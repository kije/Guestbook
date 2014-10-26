<?php


namespace kije\SimpleORM;

use kije\SimpleORM\QueryBuilder\QueryBuilder;

/**
 * Interface ORMInterface
 * @package kije\SimpleORM
 */
interface ORMInterface extends \JsonSerializable
{
    /**
     * Get the tablename
     * @return string
     */
    public static function tableName();

    /**
     * Get the columns.
     * @return array
     */
    public static function columns();

    /**
     * get Primary Key column(s).
     * Returns false, if there is no pk
     * @return string|array|boolean
     */
    public static function pk();

    /**
     * Foreign Key column(s).
     * Returns false, if there is no foreign key.
     * Otherwise, it returns a array, where the key is the column name,
     * and the value is the type (class) of the referenced object
     *
     * @return bool|array
     */
    public static function fks();

    /**
     * Get the class name of the object
     * @return string
     */
    public static function getClassName();

    /**
     * Fetch all instances of this class
     * @return self[]|boolean|null
     */
    public static function fetchAll();


    /**
     * Delete all entries in this table
     * @return bool
     */
    public static function truncate();

    /**
     * Delete this table
     * @return bool
     */
    public static function drop();

    /**
     * Save the current instance
     * @return bool
     */
    public function save();

    /**
     * Delete the current instance
     * @param bool $soft_delete
     * @return bool
     */
    public function delete($soft_delete = true);

    /**
     * Reloads data from DB
     * @return boolean
     */
    public function refresh();

    /**
     * @return self
     */
    public static function create();

    /**
     * @param array $pk
     * @return self
     */
    public static function fetch($pk);

    /**
     * Get data
     * @param string $name
     * @param bool|mixed $default
     * @return bool|mixed
     */
    public function get($name, $default = false);

    /**
     * @param string $name
     * @return bool|mixed
     */
    public function __get($name);

    /**
     * Set data
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value);

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value);


    /**
     * Check, if a value exists
     * @param string $name
     * @return bool
     */
    public function has($name);

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name);

    /**
     * @param string $name
     */
    public function __unset($name);

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize();

    /**
     * Creates an object from a stdClass object
     * @param \stdClass $obj
     * @param bool $use__class_property
     * @return self|null|object
     */
    public static function createFromObject(\stdClass $obj, $use__class_property = false);

    /**
     * get query builder
     * @return QueryBuilder
     */
    public static function query();

    /**
     * @param string $partial_query
     * @param array $data
     * @return self[]|null|boolean
     */
    public static function fetchRaw($partial_query, array $data = array());
}
