<?php

/*
 * This file is part of the Tributum project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tributum\Exceptions;

use Tributum\Exceptions;

class MethodFailedException extends Exceptions {
	
	/**
	 * constructor
	 * @param string $message
	 */
	public function __construct(string $message) {
		
		// parent constructor
		parent::__construct($message);
		
		// set status code
		$this->setStatusCode(500);
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
				'statusCode' => $this->getStatusCode(),
				'exception' => get_class($this),
				'message' => $this->getMessage(),
			),
		);
	}
}
