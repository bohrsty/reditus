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

namespace Tributum\Service\V1;

class Service implements ServiceInterface {
		
	/*
	 * class variables
	 */
	private $data;
	
	
	/*
	 * getter/setter
	 */
	public function getData() {
		return $this->data;
	}
	public function setData(Array $data) {
		$this->data = $data;
	}
	
	
	/**
	 * constructor
	 * @param Array|null $data the data given in POST request
	 */
	public function __construct($data) {
		
		// set class variables
		$this->setData($data);
	}
	
	
	/**
	 * returns an array containing all existing services
	 * @return Array
	 */
	public static function helpGetServices() {
		
		// defined services
		return array(
			'Help',
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
