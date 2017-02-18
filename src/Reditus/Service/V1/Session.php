<?php

/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Reditus\Service\V1;

use Reditus\Service\V1\Service;
use Reditus\Database\SessionDatabase;

class Session extends Service implements ServiceInterface {
		
	/*
	 * class variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	
	/**
	 * constructor
	 * @param Array|null $data the data given in POST request
	 */
	public function __construct($data) {
		
		// parent constructor
		parent::__construct($data);
	}
	
	
	/**
	 * default method called if no valid POST data is provided
	 * @return void
	 */
	public function defaultMethod() {
		
		// set default response
		$this->setApiArray(array(
			'database' => array(
				'available' => false,
				'lastChanged' => '',
			),
		));
	}
	
	
	/**
	 * service documentation for the Help service
	 * @return Array
	 */
	public static function getServiceDocs() {
		
		// return documentation
		return array(
			'service' => 'Session',
			'methods' => array(
				'init' => 'array()',
			),
			'httpStatus' => array(200,404,500),
			'responses' => array(
				'init' => 'Array containing keys "database" = [(Array) keys "available" = (bool) whether a database file is uploaded and valid, "lastChanged" = (string) date and time of last database change]',
			),
			'info' => 'Handles the session management, checks database file up- and download.',
		);
	}
	
	
	/**
	 * default status code without exception
	 * @return int
	 */
	public function getStatusCode() {
		return 200;
	}
	
	
	/**
	 * returns the objects response as array to use in HttpResponse
	 * @return Array
	 */
	public function __toArray() {
		
		return array(
			'status' => 'OK',
			'data' => array(
				'statusCode' => $this->getStatusCode(),
				'response' => $this->getApiArray(),
			),
		);
	}
	
	
	/**
	 * checks the session if database is available and provides infomation about it
	 * @return void
	 */
	public function init() {
		
		// prepare response data
		$available = false;
		$lastChanged = 0;
		
		// get database connection
		$db = new SessionDatabase();
		
		// check if session exists or create one
		$sessionId = $this->getCookie();
		if($sessionId === false) {
			
			// cleanup old sessions
			$db->cleanupSessions();
			
			// get session id and set start timestamp
			$sessionId = $this->setCookie();
			$db->setToSession($sessionId, 'sessionStart', (string) time());
		}
		
		// get data from session
		$databaseAvailable = $db->getFromSession($sessionId, 'databaseAvailable');
		$databaseLastChanged = $db->getFromSession($sessionId, 'databaseLastChanged');
		
		// check data
		if($databaseAvailable !== null && $databaseLastChanged !== null) {
			
			// set for response
			$available = $databaseAvailable === '1';
			$lastChanged = $databaseLastChanged;
		}
		
		// set response data
		$this->setApiArray(array(
			'database' => array(
				'available' => $available,
				'lastChanged' => $lastChanged,
			),
		));
	}
	
	
	/**
	 * generates the session cookie data and sets the cookie
	 * @return string
	 */
	private function setCookie() {
		
		// generate cookie data
		$date = new \DateTime();
		$cookieDate = $date->format(\DateTime::COOKIE);
		$randomString = str_replace('=', '', base64_encode(openssl_random_pseudo_bytes(5)));
		$cookieData = base64_encode($cookieDate.' '.$randomString);
		
		// set cookie
		if(setcookie('tributumSession', $cookieData, 0) !== true) {
			throw new SessionCookieNotSetException('Session cookie not set!');
		}
		
		// return session id
		return md5($cookieData);
	}
	
	
	/**
	 * checks if cookie is set and returns the session id or false
	 * @return bool|string
	 */
	private function getCookie() {
		
		// check if cookie is set
		if(!empty($_COOKIE['tributumSession'])) {
			
			// decode value
			$cookieData = base64_decode($_COOKIE['tributumSession']);
			
			// return session id
			return md5($cookieData);
		} else {
			return false;
		}
	}
}
