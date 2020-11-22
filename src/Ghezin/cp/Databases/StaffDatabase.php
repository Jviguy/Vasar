<?php

declare(strict_types=1);

namespace Ghezin\cp\Databases;

/**
 * The Staff Database used for Vasar core
 *
 * @package Ghezin\cp\Databases
 *
 * @see Database
 */
class StaffDatabase extends Database
{
    /**
     * @author Jviguy
     *
     * StaffDatabase constructor.
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
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS mutes (player TEXT PRIMARY KEY, reason TEXT, duration INT, staff TEXT, date TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS temporarybans (player TEXT PRIMARY KEY, reason TEXT, duration INT, staff TEXT, givenpoints INT, date TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS permanentbans (player TEXT PRIMARY KEY, reason TEXT, staff TEXT, date TEXT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS warnpoints (player TEXT PRIMARY KEY, points INT);");
        $this->ExecuteQuery("CREATE TABLE IF NOT EXISTS staffstats (player TEXT PRIMARY KEY, timesjoined INT, timesleft INT, pointsgiven INT, mutesissued INT, kicksissued INT, temporarybansissued INT, permanentbansissued INT);");
    }
}