<?php

declare(strict_types=1);

namespace Ghezin\cp\handlers;

use pocketmine\Player;
use Ghezin\cp\Core;
use Ghezin\cp\Utils;

class DatabaseHandler{
	
	private $plugin;

	public function __construct(){
		$this->plugin = Core::getInstance();
	}

    /**
     * @param $player
     * @param $reason
     * @param $duration
     * @param $staff
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function mutePlayer($player, $reason, $duration, $staff){
		$query = $this->plugin->staffdb->prepareQuery("INSERT OR REPLACE INTO mutes (player, reason, duration, staff, date) VALUES (:player, :reason, :duration, :staff, :date);");
		$query->bindValue(":player", Utils::getPlayerName($player));
		$query->bindValue(":reason", $reason);
		$query->bindValue(":duration", $duration);
		$query->bindValue(":staff", Utils::getPlayerName($staff));
		$query->bindValue(":date", Utils::getTime());
		$query->execute();
	}

    /**
     * @param $player
     * @param $reason
     * @param $duration
     * @param $staff
     * @param $givenpoints
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function temporaryBanPlayer($player, $reason, $duration, $staff, $givenpoints){
		$query=$this->plugin->staffdb->prepareQuery("INSERT OR REPLACE INTO temporarybans (player, reason, duration, staff, givenpoints, date) VALUES (:player, :reason, :duration, :staff, :givenpoints, :date);");
		$query->bindValue(":player", Utils::getPlayerName($player));
		$query->bindValue(":reason", $reason);
		$query->bindValue(":duration", $duration);
		$query->bindValue(":staff", Utils::getPlayerName($staff));
		$query->bindValue(":givenpoints", $givenpoints);
		$query->bindValue(":date", Utils::getTime());
		$query->execute();
		
		//$this->setTemporaryBansIssued($staff, $this->getTemporaryBansIssued($staff) + 1);
		//$this->setPointsGiven($staff, $this->getPointsGiven($staff) + $givenpoints);
		$this->setWarnPoints($player, $this->getWarnPoints($player) + $givenpoints);
	}

    /**
     * @param $player
     * @param $reason
     * @param $staff
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function permanentlyBanPlayer($player, $reason, $staff){
		$query=$this->plugin->staffdb->prepareQuery("INSERT OR REPLACE INTO permanentbans (player, reason, staff, date) VALUES (:player, :reason, :staff, :date);");
		$query->bindValue(":player", Utils::getPlayerName($player));
		$query->bindValue(":reason", $reason);
		if($staff=="Server"){
			$query->bindValue(":staff", "Server");
		}else{
			$query->bindValue(":staff", Utils::getPlayerName($staff));
		}
		$query->bindValue(":date", Utils::getTime());
		$query->execute();
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setTimesJoined($player, $int) {
		$this->plugin->staffdb->ExecuteQuery("UPDATE staffstats SET timesjoined='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setTimesLeft($player, $int){
	    $this->plugin->staffdb->ExecuteQuery("UPDATE staffstats SET timesleft='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setPointsGiven($player, $int){
		$this->plugin->staffdb->ExecuteQuery("UPDATE staffstats SET pointsgiven='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setMutesIssued($player, $int){
		$this->plugin->staffdb->ExecuteQuery("UPDATE staffstats SET mutesissued='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setKicksIssued($player, $int){
		$this->plugin->staffdb->ExecuteQuery("UPDATE staffstats SET kicksissued='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setTemporaryBansIssued($player, $int){
		$this->plugin->staffdb->ExecuteQuery("UPDATE staffstats SET temporarybansissued='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setPermanentBansIssued($player, $int){
		$this->plugin->staffdb->ExecuteQuery("UPDATE staffstats SET permanentbansissued='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setWarnPoints($player, $int){
		$this->plugin->staffdb->ExecuteQuery("UPDATE warnpoints SET points='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getTimesJoined($player){
		$query = $this->plugin->staffdb->query("SELECT timesjoined FROM staffstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["timesjoined"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getTimesLeft($player){
		$query=$this->plugin->staffdb->query("SELECT timesleft FROM staffstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["timesleft"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getPointsGiven($player){
		$query=$this->plugin->staffdb->query("SELECT pointsgiven FROM staffstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["pointsgiven"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getMutesIssued($player){
		$query=$this->plugin->staffdb->query("SELECT mutesissued FROM staffstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["mutesissued"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getKicksIssued($player){
		$query=$this->plugin->staffdb->query("SELECT kicksissued FROM staffstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["kicksissued"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getTemporaryBansIssued($player){
		$query=$this->plugin->staffdb->query("SELECT temporarybansissued FROM staffstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["temporarybansissued"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getPermanentBansIssued($player){
		$query=$this->plugin->staffdb->query("SELECT permanentbansissued FROM staffstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["permanentbansissued"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getWarnPoints($player){
		$query=$this->plugin->staffdb->query("SELECT points FROM warnpoints WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["points"];
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function warnPointsAdd($player){
		$check=$this->plugin->staffdb->query("SELECT player FROM warnpoints WHERE player='".Utils::getPlayerName($player)."';");
		$result=$check->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			$query=$this->plugin->staffdb->prepareQuery("INSERT OR REPLACE INTO warnpoints (player, points) VALUES (:player, :points);");
			$query->bindValue(":player", $player);
			$query->bindValue(":points", 0);
			$query->execute();
		}
	}

    /**
     * @param $player
     * @return bool
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function isMuted($player):bool{
		$query=$this->plugin->staffdb->query("SELECT player FROM mutes WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return empty($result)==false;
	}

    /**
     * @param $player
     * @return bool
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function isTemporarilyBanned($player):bool{
		$query=$this->plugin->staffdb->query("SELECT player FROM temporarybans WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return empty($result)==false;
	}

    /**
     * @param $player
     * @return bool
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function isPermanentlyBanned($player):bool{
		$query=$this->plugin->staffdb->query("SELECT player FROM permanentbans WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return empty($result)==false;
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function rankAdd($player){
		$check=$this->plugin->db->query("SELECT player FROM rank WHERE player='".Utils::getPlayerName($player)."';");
		$result=$check->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			$query=$this->plugin->db->prepareQuery("INSERT OR REPLACE INTO rank (player, rank) VALUES (:player, :rank);");
			$query->bindValue(":player", $player);
			$query->bindValue(":rank", "Player");
			$query->execute();
		}
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function voteAccessCreate($player){
		$now=time();
		$hour=24 * 3600;
		$duration=$now + $hour;
		$query=$this->plugin->db->prepareQuery("INSERT OR REPLACE INTO voteaccess (player, duration) VALUES (:player, :duration);");
		$query->bindValue(":player", Utils::getPlayerName($player));
		$query->bindValue(":duration", $duration);
		$query->execute();
	}

    /**
     * @param $player
     * @param $temprank
     * @param $duration
     * @param $oldrank
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function temporaryRankCreate($player, $temprank, $duration, $oldrank){
		$query=$this->plugin->db->prepareQuery("INSERT OR REPLACE INTO temporaryranks (player, temprank, duration, oldrank) VALUES (:player, :temprank, :duration, :oldrank);");
		$query->bindValue(":player", Utils::getPlayerName($player));
		$query->bindValue(":temprank", $temprank);
		$query->bindValue(":duration", $duration);
		$query->bindValue(":oldrank", $oldrank);
		$query->execute();
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function levelsAdd($player){
		$check=$this->plugin->db->query("SELECT player FROM levels WHERE player='".Utils::getPlayerName($player)."';");
		$result=$check->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			$query=$this->plugin->db->prepareQuery("INSERT OR REPLACE INTO levels (player, level, neededxp, currentxp, totalxp) VALUES (:player, :level, :neededxp, :currentxp, :totalxp);");
			$query->bindValue(":player", $player);
			$query->bindValue(":level", 1);
			$query->bindValue(":neededxp", 100);
			$query->bindValue(":currentxp", 0);
			$query->bindValue(":totalxp", 0);
			$query->execute();
		}
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function matchStatsAdd($player){
		$check=$this->plugin->db->query("SELECT player FROM matchstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$check->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			$query=$this->plugin->db->prepareQuery("INSERT OR REPLACE INTO matchstats (player, elo, wins, losses, elogained, elolost) VALUES (:player, :elo, :wins, :losses, :elogained, :elolost);");
			$query->bindValue(":player", $player);
			$query->bindValue(":elo", 1000);
			$query->bindValue(":wins", 0);
			$query->bindValue(":losses", 0);
			$query->bindValue(":elogained", 0);
			$query->bindValue(":elolost", 0);
			$query->execute();
		}
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function essentialStatsAdd($player){
		$check=$this->plugin->db->query("SELECT player FROM essentialstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$check->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			$query=$this->plugin->db->prepareQuery("INSERT OR REPLACE INTO essentialstats (player, kills, deaths, kdr, killstreak, bestkillstreak, coins, elo) VALUES (:player, :kills, :deaths, :kdr, :killstreak, :bestkillstreak, :coins, :elo);");
			$query->bindValue(":player", $player);
			$query->bindValue(":kills", 0);
			$query->bindValue(":deaths", 0);
			$query->bindValue(":kdr", 0);
			$query->bindValue(":killstreak", 0);
			$query->bindValue(":bestkillstreak", 0);
			$query->bindValue(":coins", 0);
			$query->bindValue(":elo", 0);
			$query->execute();
		}
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function tempStatisticsAdd($player){
		$check=$this->plugin->db->query("SELECT player FROM temporary WHERE player='".Utils::getPlayerName($player)."';");
		$result=$check->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			$query=$this->plugin->db->prepareQuery("INSERT OR REPLACE INTO temporary (player, dailykills, dailydeaths) VALUES (:player, :dailykills, :dailydeaths);");
			$query->bindValue(":player", $player);
			$query->bindValue(":dailykills", 0);
			$query->bindValue(":dailydeaths", 0);
			$query->execute();
		}
	}

    /**
     * @param $player
     * @param $rank
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setRank($player, $rank){
		$this->plugin->db->ExecuteQuery("UPDATE rank SET rank='$rank' WHERE player='".Utils::getPlayerName($player)."'");
		$player=Utils::getPlayer($player);
		if($player!==null) $this->plugin->getPermissionHandler()->addPermission($player, $rank);
	}

    /**
     * @param $player
     * @return mixed
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getRank($player){
		$query=$this->plugin->db->query("SELECT rank FROM rank WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return $result["rank"];
	}

    /**
     * @param $type
     * @return mixed
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function countWithRank($type){
		$query=$this->plugin->db->query("SELECT COUNT (player) as count FROM rank WHERE rank='$type';");
		$number=$query->fetchArray();
		return $number['count'];
	}

    /**
     * @param $player
     * @return bool
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function voteAccessExists($player):bool{
		$query=$this->plugin->db->query("SELECT player FROM voteaccess WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return empty($result)==false;
	}/*
	public function isValueEmpty(string $val):bool{
		$query=$this->plugin->main->query("SELECT rank FROM information WHERE rank='$val';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return empty($result)==false;
	}*/

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setLevel($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE levels SET level='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setNeededXp($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE levels SET neededxp='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setCurrentXp($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE levels SET currentxp='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setTotalXp($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE levels SET totalxp='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getLevel($player){
		$query=$this->plugin->db->query("SELECT level FROM levels WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["level"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getNeededXp($player){
		$query=$this->plugin->db->query("SELECT neededxp FROM levels WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["neededxp"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getCurrentXp($player){
		$query=$this->plugin->db->query("SELECT currentxp FROM levels WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["currentxp"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getTotalXp($player){
		$query=$this->plugin->db->query("SELECT totalxp FROM levels WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["totalxp"];
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setRankedElo($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE matchstats SET elo='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setWins($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE matchstats SET wins='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setLosses($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE matchstats SET losses='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setEloGained($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE matchstats SET elogained='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setEloLost($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE matchstats SET elolost='$int' WHERE player='".Utils::getPlayerName($player)."'");
	}

    /**
     * @param $player
     * @return mixed
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getRankedElo($player){
		$query = $this->plugin->db->query("SELECT elo FROM matchstats WHERE player='".Utils::getPlayerName($player)."';");
		return $query->fetchArray(SQLITE3_ASSOC)["elo"];
	}

    /**
     * @param $player
     * @return mixed
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getWins($player){
		$query = $this->plugin->db->query("SELECT wins FROM matchstats WHERE player='".Utils::getPlayerName($player)."';");
		return $query->fetchArray(SQLITE3_ASSOC)["wins"];
	}

    /**
     * @param $player
     * @return mixed
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getLosses($player){
		$query = $this->plugin->db->query("SELECT losses FROM matchstats WHERE player='".Utils::getPlayerName($player)."';");
		return $query->fetchArray(SQLITE3_ASSOC)["losses"];
	}

    /**
     * @param $player
     * @return mixed
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getEloGained($player){
		$query=$this->plugin->db->query("SELECT elogained FROM matchstats WHERE player='".Utils::getPlayerName($player)."';");
		return $query->fetchArray(SQLITE3_ASSOC)["elogained"];
	}

    /**
     * @param $player
     * @return mixed
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getEloLost($player){
		$query=$this->plugin->db->query("SELECT elolost FROM matchstats WHERE player='".Utils::getPlayerName($player)."';");
		return $query->fetchArray(SQLITE3_ASSOC)["elolost"];
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setKills($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET kills='$int' WHERE player='".Utils::getPlayerName($player)."';");
		$this->updateKdr($player);
		$this->plugin->getScoreboardHandler()->updateMainLineKillsDeaths($player);
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setDeaths($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET deaths='$int' WHERE player='".Utils::getPlayerName($player)."';");
		$this->updateKdr($player);
		$this->plugin->getScoreboardHandler()->updateMainLineKillsDeaths($player);
	}

    /**
     * @param $player
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function updateKdr($player){
		$deaths = $this->getDeaths($player->getName());
		$kills = $this->getKills($player->getName());/*
		if($kills!==0 && $deaths==0){
			$this->plugin->main->exec("UPDATE essentialstats SET kdr='$kills'.0 WHERE player='".$player->getName()."'");*/
			if($deaths!==0){
				$kdr = $kills / $deaths;
				$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET kdr='$kdr' WHERE player='".Utils::getPlayerName($player)."';");
				if($kdr!==0){
					$kdr=number_format($kdr, 2);
					$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET kdr='$kdr' WHERE player='".Utils::getPlayerName($player)."';");
				//}
			}
		}
		$this->plugin->getScoreboardHandler()->updateMainLineKdr($player);
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setKillstreak($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET killstreak='$int' WHERE player='".Utils::getPlayerName($player)."';");
		$this->plugin->getScoreboardHandler()->updateMainLineKillstreak($player);
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setBestKillstreak($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET bestkillstreak='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setCoins($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET coins='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setElo($player, $int){
		$this->plugin->db->ExecuteQuery("UPDATE essentialstats SET elo='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getKills($player){
		$query=$this->plugin->db->query("SELECT kills FROM essentialstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["kills"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getDeaths($player){
		$query=$this->plugin->db->query("SELECT deaths FROM essentialstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["deaths"];
	}

    /**
     * @param $player
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getKdr($player){
		if($player instanceof Player){
			$player = $player->getName();
        }
		$deaths = $this->getDeaths($player);
		$kills = $this->getKills($player);
		if($deaths !== 0){
			$kdr = $kills/$deaths;
			if($kdr !== 0){
				return number_format($kdr, 2);
			}
		}
		return $kills.".0";
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getKillstreak($player){
		$query=$this->plugin->db->query("SELECT killstreak FROM essentialstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["killstreak"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getBestKillstreak($player){
		$query=$this->plugin->db->query("SELECT bestkillstreak FROM essentialstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["bestkillstreak"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getCoins($player){
		$query=$this->plugin->db->query("SELECT coins FROM essentialstats WHERE player='".Utils::getPlayerName($player)."';");
		$result=$query->fetchArray(SQLITE3_ASSOC);
		return (int) $result["coins"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getElo($player){
		if($player instanceof Player){
			$player=$player->getName();
		}
		$query = $this->plugin->db->query("SELECT elo FROM essentialstats WHERE player='".Utils::getPlayerName($player)."';");
		return (int) $query->fetchArray(SQLITE3_ASSOC)["elo"];
	}

    /**
     * @param $player
     * @param int $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setDailyKills($player, int $int){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$this->plugin->db->ExecuteQuery("UPDATE temporary SET dailykills='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @param int $int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function setDailyDeaths($player, int $int){
		if($player instanceof Player){
			$player=$player->getName();
		}
		$this->plugin->db->ExecuteQuery("UPDATE temporary SET dailydeaths='$int' WHERE player='".Utils::getPlayerName($player)."';");
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getDailyKills($player){
		if($player instanceof Player){
			$player=$player->getName();
		}
		$query=$this->plugin->db->query("SELECT dailykills FROM temporary WHERE player='".Utils::getPlayerName($player)."';");
		return (int) $query->fetchArray(SQLITE3_ASSOC)["dailykills"];
	}

    /**
     * @param $player
     * @return int
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function getDailyDeaths($player){
		if($player instanceof Player) {
            $player = $player->getName();
        }
		$query=$this->plugin->db->query("SELECT dailydeaths FROM temporary WHERE player='".Utils::getPlayerName($player)."';");
		return (int) $query->fetchArray(SQLITE3_ASSOC)["dailydeaths"];
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topLevels(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM levels ORDER BY level DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j = $i + 1;
			$player=$resultArr['player'];
			$val=$this->getLevel($player);
			if (Utils::isShowInLeaderboardsEnabled($player)) {
				if ($j == 1){
					$message.="§8#1 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 2){
					$message.="§8#2 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 3){
					$message.="§8#3 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j != 1 && $j!= 2 && $j!= 3){
					$message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
				}
				++$i;
			}
		}
		return "§3You - ".$this->getLevel($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topElo(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM matchstats ORDER BY elo DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getRankedElo($player);
			if (Utils::isShowInLeaderboardsEnabled($player)){
				if ($j == 1){
					$message.="§8#1 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 2){
					$message.="§8#2 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 3){
					$message.="§8#3 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j != 1 && $j != 2 && $j != 3){
					$message.="§8#".$j." §7".$player." §8-§7 ".$val."\n"; 
				}
				++$i;
			}
		}
		return "§3You - ".$this->getRankedElo($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topWins(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM matchstats ORDER BY wins DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getWins($player);
			if (Utils::isShowInLeaderboardsEnabled($player)) {
				if ($j == 1){
					$message.="§8#1 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 2){
					$message.="§8#2 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 3){
					$message.="§8#3 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j != 1 && $j != 2 && $j != 3){
					$message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
				}
				++$i;
			}
		}
		return "§3You - ".$this->getWins($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topLosses(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM matchstats ORDER BY losses DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getLosses($player);
			if (Utils::isShowInLeaderboardsEnabled($player)) {
				if ($j == 1){
					$message.="§8#1 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 2){
					$message.="§8#2 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 3){
					$message.="§8#3 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j != 1 && $j != 2 && $j != 3){
					$message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
				}
				++$i;
			}
		}
		return "§3You - ".$this->getLosses($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topKills(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM essentialstats ORDER BY kills DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getKills($player);
			if (Utils::isShowInLeaderboardsEnabled($player)) {
				if ($j == 1) {
					$message.="§8#1 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 2) {
					$message.="§8#2 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j == 3) {
					$message.="§8#3 §7".$player." §8-§7 ".$val."\n"; 
				}
				if ($j != 1 && $j != 2 && $j != 3){
					$message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
				}
				++$i;
			}
		}
		return "§3You - ".$this->getKills($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topDeaths(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM essentialstats ORDER BY deaths DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getDeaths($player);
            if (Utils::isShowInLeaderboardsEnabled($player)) {
                if ($j == 1) {
                    $message.="§8#1 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 2) {
                    $message.="§8#2 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 3) {
                    $message.="§8#3 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j != 1 && $j != 2 && $j != 3){
                    $message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
                }
                ++$i;
            }
		}
		return "§3You - ".$this->getDeaths($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topKdr(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM essentialstats ORDER BY kdr DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getKdr($player);
            if (Utils::isShowInLeaderboardsEnabled($player)) {
                if ($j == 1) {
                    $message.="§8#1 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 2) {
                    $message.="§8#2 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 3) {
                    $message.="§8#3 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j != 1 && $j != 2 && $j != 3){
                    $message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
                }
                ++$i;
            }
		}
		return "§3You - ".$this->getKdr($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topKillstreaks(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM essentialstats ORDER BY bestkillstreak DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getBestKillstreak($player);
            if (Utils::isShowInLeaderboardsEnabled($player)) {
                if ($j == 1) {
                    $message.="§8#1 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 2) {
                    $message.="§8#2 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 3) {
                    $message.="§8#3 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j != 1 && $j != 2 && $j != 3){
                    $message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
                }
                ++$i;
            }
		}
		return "§3You - ".$this->getBestKillstreak($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topDailyKills(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM temporary ORDER BY dailykills DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getDailyKills($player);
            if (Utils::isShowInLeaderboardsEnabled($player)) {
                if ($j == 1) {
                    $message.="§8#1 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 2) {
                    $message.="§8#2 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 3) {
                    $message.="§8#3 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j != 1 && $j != 2 && $j != 3){
                    $message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
                }
                ++$i;
            }
		}
		return "§3You - ".$this->getDailyKills($viewer)."§r\n".$message;
	}

    /**
     * @param string $viewer
     * @return string
     *
     * @noinspection SqlDialectInspection, SqlNoDataSourceInspection
     */
	public function topDailyDeaths(string $viewer){
		$query=$this->plugin->db->query("SELECT * FROM temporary ORDER BY dailydeaths DESC LIMIT 10;");
		$message="";
		$i=0;
		while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
			$j=$i + 1;
			$player=$resultArr['player'];
			$val=$this->getDailyDeaths($player);
            if (Utils::isShowInLeaderboardsEnabled($player)) {
                if ($j == 1) {
                    $message.="§8#1 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 2) {
                    $message.="§8#2 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j == 3) {
                    $message.="§8#3 §7".$player." §8-§7 ".$val."\n";
                }
                if ($j != 1 && $j != 2 && $j != 3){
                    $message.="§8#".$j." §7".$player." §8-§7 ".$val."\n";
                }
                ++$i;
            }
		}
		return "§3You - ".$this->getDailyDeaths($viewer)."§r\n".$message;
	}
}