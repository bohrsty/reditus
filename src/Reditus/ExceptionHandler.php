<?php

/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Reditus;

use Reditus\Exceptions;
use Reditus\HttpResponse;

class ExceptionHandler {
	
	/*
	 * class variables
	 */
	private $statusCode;
	
	
	/*
	 * getter/setter
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}
	public function setStatusCode(int $statusCode) {
		$this->statusCode = $statusCode;
	}
	
	/**
	 * constructor
	 */
	public function __construct() {
		
	}
	
	/**
	 * handles thrown exceptions
	 * 
	 * @param Throwable $e
	 * @return void
	 */
	public function handle(\Throwable $e) {
		
		// check status code
		if(is_a($e, '\\Error') === true) {
			$this->setStatusCode(500);
		} else {
			switch($e->getStatusCode()) {
				
				case 401:
					// 401 unauthorized
					$this->setStatusCode(401);
					break;
				
				case 403:
					// 403 forbidden
					$this->setStatusCode(403);
					break;
				
				case 404:
					// 404 not found
					$this->setStatusCode(404);
					break;
				
				default:
					// everything else is 500 server error
					$this->setStatusCode(500);
					break;
			}
		}
		
		// check if custom "Exceptions", or other Throwable
		if(is_a($e, 'Reditus\\Exceptions') === true) {
			
			// get exception response array
			$json = $e->__toArray();
		
			// extract exception from namespace
			$json['data']['exception'] = substr(strrchr($json['data']['exception'], '\\'), 1);
		} else {
			
			// predefined Exception or Error
			$json = array(
				'status' => 'ERROR',
				'data' => array(
					'statusCode' => $this->getStatusCode(),
					'exception' => get_class($e),
					'message' => $e->getMessage().', ['.$e->getFile().' in line '.$e->getLine().']',
				),
			);
		}
		
		// create response and send it
		$response = new HttpResponse($json);
		$response->setStatusCode($this->getStatusCode());
		$response->send();
	}
}
