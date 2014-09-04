<?php


/**
 * Database (PDO) Helper Class
 * @package kije\Formgenerator\inc
 */
class DB
{
    // Formats for MySQL dates
    const MYSQL_DATE = 'Y-m-d';
    const MYSQL_DATETIME = 'Y-m-d H:i:s';

    protected static $instance;

    /**
     * Returns a configured PDO instance
     * @return null|\PDO
     */
    public static function dbh()
    {
        if (!self::$instance) {
            try {
                // Initialize PDO intstance, connects to database
                self::$instance = new \PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE,
                    DB_USER,
                    DB_PASSWORD,
                    array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );

                // set error mode
                self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                // Log error
                error_log('Could not connect to database: Error: ' . $e->getCode() . PHP_EOL . $e->getMessage(), 0);
                return null;
            }
        }

        return self::$instance;
    }
}
