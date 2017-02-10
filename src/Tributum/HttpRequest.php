<?php

/*
 * This file is part of the Tributum project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Tributum;

use Tributum\Exceptions\JsonNotParsableException;
use Tributum\Exceptions\NoApiCallException;
use Tributum\Exceptions\NoValidServiceException;

class HttpRequest {
	
	/*
	 * class variables
	 */
	private $server;
	private $service;
	private $version;
	private $data;
	
	
	/*
	 * getter/setter
	 */
	public function getServer($field = null) {
		
		// check $field
		if(is_null($field) || !isset($this->server[$field])) {
			return $this->server;
		} else {
			return $this->server[$field];
		}
	}
	public function setServer(Array $server) {
		$this->server = $server;
	}
	public function getService() {
		return $this->service;
	}
	public function setService($service) {
		$this->service = $service;
	}
	public function getVersion() {
		return $this->version;
	}
	public function setVersion(string $version) {
		$this->version = $version;
	}
	public function getData() {
		return $this->data;
	}
	public function setData(Array $data) {
		$this->data = $data;
	}
	
	/**
	 * constructor
	 */
	public function __construct() {
		
		// set server array
		$this->setServer($_SERVER);
		
		// parse URI
		$this->parseRequestUri();
		
		// set data as array
		if(isset($_POST['data'])) {
			$data = json_decode($_POST['data'], true);
			// check if data was valid JSON
			if(!is_null($data)) {
				$this->setData($data);
			} else {
				
				// throw exception
				throw new JsonNotParsableException(json_last_error_msg());
			}
		} else {
			$this->setData(array());
		}
	}
	
	/**
	 * parses $_SERVER['REQUEST_URI'] to extract the service information
	 * @return void
	 */
	private function parseRequestUri() {
		
		// split in path and parameters
		list($path) = explode('?', $this->getServer('REQUEST_URI'));

		// check trailing ../ and /
		$path = str_replace('../', '', $path);
		if(substr($path, -1) === '/') {
			$path = substr($path, 0, -1);
		}
		if(substr($path, 0, 1) === '/') {
			$path = substr($path, 1);
		}
		
		// split path in version, service and rest
		$pathArray = explode('/', $path);
		if(count($pathArray) > 0 && $pathArray[0] !== '') {
			
			// check /v1/
			if($pathArray[0] !== 'v1') {
				throw new NoApiCallException('API calls must start with "v1"');
			} else {
				
				// check if service is set, one char long and contains only a-z,A-Z
				if(!isset($pathArray[1]) || !preg_match('/^[a-zA-Z]+$/', $pathArray[1])) {
					throw new NoValidServiceException('The service part is not given or containing other character than a-z, A-Z');
				} else {
					
					// set service
					$this->setService(ucfirst(strtolower($pathArray[1])));
				}
				
				// set version v1
				$this->setVersion('v1');
			}
		} else {
			// requesting /
			$this->setService(null);
		}
	}
}
