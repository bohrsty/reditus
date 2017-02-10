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

class Exceptions extends \Exception {
	
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
	 * @param string $message
	 */
	public function __construct(string $message) {
		
		// parent constructor
		parent::__construct($message);
		
		// initially set class variables
		$this->setStatusCode(200);
	}
	
	/**
	 * __toArray prepares the object for JSON response
	 * 
	 * @return Array
	 */
	public function __toArray() {
		
		// empty template
		return array(
			'status' => 'ERROR',
			'data' => array(
				'statusCode' => 0,
				'exception' => '',
				'message' => '',
			),
		);
	}
}
