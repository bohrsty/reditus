<?php

/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Reditus\Database;

use Reditus\Database;
use Reditus\Exceptions\SessionDatabaseCopyFailedException;

class SessionDatabase extends Database {
	
	/*
	 * class variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	
	/**
	 * constructor
	 */
	public function __construct() {
		
		// session database path
		$dbPath = __DIR__.'/../../../data/session.db'; 
		
		// check if database exists and is valid
		if(!file_exists($dbPath)) {
			if(@copy($dbPath.'.dist', $dbPath) === false) {
				throw new SessionDatabaseCopyFailedException('Copying of database template from "'.$dbPath.'.dist" to "'.$dbPath.'" failed.');
			}
		}
		
		// open session database
		parent::__construct($dbPath);
	}
	
	
	/**
	 * returns the value for $key in $sessionId
	 * @param string $sessionId the session id
	 * @param string $key the session key to return the value for
	 * @return string|null
	 */
	public function getFromSession(string $sessionId, string $key) {
		
		// prepare return
		$return = null;
		
		// prepare statement
		$sth = $this->prepare('SELECT value FROM session WHERE id=:sessionId AND key=:key LIMIT 1');
		$sth->bindParam(':sessionId', $sessionId, self::PARAM_STR);
		$sth->bindParam(':key', $key, self::PARAM_STR);
		// execute statement
		$sth->execute();
		// get result
		$result = $sth->fetch(self::FETCH_NUM);
		
		// check result
		if(!empty($result)) {
			$return = (string) $result[0];
		}
		
		// return
		return $return;
	}
	
	
	/**
	 * sets $value for $key in $session id
	 * @param string $sessionId the id of the session
	 * @param string $key the key to set the value for
	 * @param string $value the value to set
	 * @return void
	 */
	public function setToSession(string $sessionId, string $key, string $value) {
		
		// prepare statements
		$sthUpd = $this->prepare('UPDATE session SET value=:value WHERE id=:sessionId AND key=:key;');
		$sthIns = $this->prepare('INSERT INTO session (id, key, value) VALUES (:sessionId, :key, :value);');
		$sthUpd->bindParam(':sessionId', $sessionId, self::PARAM_STR);
		$sthUpd->bindParam(':key', $key, self::PARAM_STR);
		$sthUpd->bindParam(':value', $value, self::PARAM_STR);
		$sthIns->bindParam(':sessionId', $sessionId, self::PARAM_STR);
		$sthIns->bindParam(':key', $key, self::PARAM_STR);
		$sthIns->bindParam(':value', $value, self::PARAM_STR);
		// execute statements
		$sthUpd->execute();
		$rowsUpd = $sthUpd->rowCount();
		if($rowsUpd === 0) {
			$sthIns->execute();
		}
	}
	
	
	/**
	 * searches old sessions and remove their entries
	 * return void
	 */
	public function cleanupSessions() {
		
		// prepare statement
		$sthSel = $this->prepare('SELECT id, value FROM session WHERE key="sessionStart"');
		// execute statement
		$sthSel->execute();
		// get result
		while($row = $sthSel->fetch(self::FETCH_BOTH)) {
			
			// is session entry older than 24h
			if(time() - (int) $row['value'] > 60 * 60 * 24) {
				
				// prepare statement
				$sthDel = $this->prepare('DELETE FROM session WHERE id=:sessionId');
				$sthDel->bindParam(':sessionId', $row['id'], self::PARAM_STR);
				// execute statement
				$sthDel->execute();
			}
		}
	}
}
