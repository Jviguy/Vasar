<?php

declare(strict_types=1);

namespace Ghezin\cp\Databases;

/**
 * The Clans Database used for Vasar core
 *
 * @package Ghezin\cp\Databases
 *
 * @see Database
 */
class ClansDatabase extends Database
{
    /**
     * @author Jviguy
     *
     * ClansDatabase constructor.
     *
     * @param string $path The path to the database to be loaded
     *
     * @param bool $init a bool indicating if we should init the db or not
     */
    public function __construct(string $path,bool $init = true)
    {
        if ($init) $this->Init();
        parent::__construct($path);
    }

    /**
     * Initializes the database with all the needed tables
     *
     * @author Jviguy
     *
     * @api
     *
     * @see Database::Init()
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     *
     * @return void
     */
    public function Init()
    {
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS master (player TEXT PRIMARY KEY, clan TEXT, rank TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS confirm (player TEXT PRIMARY KEY, clan TEXT, invitemainy TEXT, timestamp INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS alliance (player TEXT PRIMARY KEY, clan TEXT, requestemainy TEXT, timestamp INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS motdrcv (player TEXT PRIMARY KEY, timestamp INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS motd (clan TEXT PRIMARY KEY, message TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS noticolor (clan TEXT PRIMARY KEY, color TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS privacy (clan TEXT PRIMARY KEY, open TEXT);");//true false
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS friendlyfire (clan TEXT PRIMARY KEY, pvp TEXT);");//true false
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS plots (clan TEXT PRIMARY KEY, x1 INT, z1 INT, x2 INT, z2 INT, world TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS home (clan TEXT PRIMARY KEY, x INT, y INT, z INT, world TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS power (clan TEXT PRIMARY KEY, power INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS maxplayers (clan TEXT PRIMARY KEY, slots INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS allies (ID INT PRIMARY KEY, clan1 TEXT, clan2 TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS enemies (ID INT PRIMARY KEY, clan1 TEXT, clan2 TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS alliescountlimit (clan TEXT PRIMARY KEY, count INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS created (clan TEXT PRIMARY KEY, date TEXT);");
    }
}