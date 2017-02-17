<?php

/*
 * This file is part of the Tributum project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tributum\Service\V1;

use Tributum\Exceptions\MethodNotFoundException;
use Tributum\Exceptions\ServiceNotFoundException;

class Service implements ServiceInterface {
		
	/*
	 * class variables
	 */
	private $data;
	private $apiArray;
	
	
	/*
	 * getter/setter
	 */
	public function getData() {
		return $this->data;
	}
	public function setData(Array $data) {
		$this->data = $data;
	}
	public function getApiArray() {
		return $this->apiArray;
	}
	public function setApiArray(Array $apiArray) {
		$this->apiArray = $apiArray;
	}
	
	
	/**
	 * constructor
	 * @param Array|null $data the data given in POST request
	 */
	public function __construct($data) {
		
		// set class variables
		$this->setData($data);
		
		//  check data
		if($data === null || empty($data)) {
			
			// default method
			$this->defaultMethod();
		} else {
			
			// call user function
			if(method_exists($this, $data['method']) === true) {
				if(call_user_func_array(array($this, $data['method']), $data['params']) === false) {
					throw new MethodFailedException('The execution of the method "'.$data['method'].'" failed.');
				}
			} else {
				throw new MethodNotFoundException('The method "'.$data['method'].'" does not exists in service "'.substr(strrchr(get_class($this), '\\'), 1).'" in version v1.');
			}
		}
	}
	
	
	/**
	 * default method called if no valid POST data is provided
	 * @return void
	 */
	public function defaultMethod() {
		
	}
	
	
	/**
	 * returns an array containing all existing services
	 * @return Array
	 */
	public static function helpGetServices() {
		
		// defined services
		return array(
			'Help',
			'Session',
		);
	}
	
	/**
	 * dummy
	 * @return Array
	 */
	public static function getServiceDocs() {
		return array();
	}
	
	
	/**
	 * dummy
	 * @return int
	 */
	public function getStatusCode() {
		return 200;
	}
	
	
	/**
	 * dummy
	 * @return Array
	 */
	public function __toArray() {
		return array();
	}
}
