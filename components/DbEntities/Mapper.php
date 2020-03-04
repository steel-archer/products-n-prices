<?php

namespace PNP\Components\DbEntities;

use \go\DB\DB;

/**
 * Class Mapper
 * @package PNP\Components\DbEntities
 */
abstract class Mapper
{
    /**
     * @var DB
     */
    private $connection;

    /**
     * @return DB
     */
    public function getConnection(): DB
    {
        return $this->connection;
    }

    /**
     * @param DB $connection
     * @return Mapper
     */
    public function setConnection(DB $connection): Mapper
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * Mapper constructor.
     */
    public function __construct()
    {
        $params = [
            'host'     => DB_SERVER_ADDRESS,
            'username' => DB_USER_NAME,
            'password' => DB_PASSWORD,
            'dbname'   => DB_DATABASE_NAME,
        ];

        $db = DB::create($params, 'mysql');

        $this->setConnection($db);
    }
}
