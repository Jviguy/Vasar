<?php

declare(strict_types=1);
/**
 * @author Jvigu
 *
 * @api
 *
 * A namespace making life easier for interacting with databases
 *
 * @see \Ghezin\cp\Databases\Database
 */
namespace Ghezin\cp\Databases;

use SQLite3;

/**
 * @author Jviguy
 *
 * @api
 *
 * Class Database
 *
 * @see MainDatabase for example usages
 *
 * @package Ghezin\cp\Databases
 */
abstract class Database
{
    /**
     * @author Jviguy
     *
     * @api
     *
     * @var $internaldb SQLite3 a hidden sqlite3 db wrapped around
     */
    private $internaldb;

    /**
     * @author Jviguy
     *
     * @param string $path the path to the db being loaded into the \Database
     */
    public function __construct(string $path) {
        $this->internaldb = new SQLite3($path);
    }

    /**
     * @author Jviguy
     *
     * @param string $query the SQLite3 query to be executed
     * @phpstan-var string $query the SQLite3 query to be executed
     *
     * @api
     *
     * @see SQLite3::exec()
     *
     * @return bool a Bool indicating if the statement executed successfully
     * @phpstan-return bool a Bool indicating if the statement executed successfully
     */
    public function ExecuteQuery(string $query): bool {
        return $this->internaldb->exec($query);
    }

    /**
     * @author Jviguy
     *
     * @api
     *
     * @return void
     */
    public abstract function Init();
}