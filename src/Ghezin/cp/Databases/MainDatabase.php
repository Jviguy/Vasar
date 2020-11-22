<?php

declare(strict_types=1);

namespace Ghezin\cp\Databases;

/**
 * The Main Database used for Vasar core
 *
 * @package Ghezin\cp\Databases
 *
 * @see Database
 */
class MainDatabase extends Database
{
    /**
     * @author Jviguy
     *
     * MainDatabase constructor.
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
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS rank (player TEXT PRIMARY KEY, rank TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS essentialstats (player TEXT PRIMARY KEY, kills INT, deaths INT, kdr REAL, killstreak INT, bestkillstreak INT, coins INT, elo INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS matchstats (player TEXT PRIMARY KEY, elo INT, wins INT, losses INT, elogained INT, elolost INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS temporary (player TEXT PRIMARY KEY, dailykills INT, dailydeaths INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS temporaryranks (player TEXT PRIMARY KEY, temprank TEXT, duration INT, oldrank TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS voteaccess (player TEXT PRIMARY KEY, bool TEXT, duration INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS levels (player TEXT PRIMARY KEY, level INT, neededxp INT, currentxp INT, totalxp INT);");
    }
}