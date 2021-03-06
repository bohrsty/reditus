<?php

/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Reditus\Exceptions;

use Reditus\Exceptions;

class NoApiCallException extends Exceptions {
	
	/**
	 * constructor
	 * @param string $message
	 */
	public function __construct(string $message) {
		
		// parent constructor
		parent::__construct($message);
		
		// set status code
		$this->setStatusCode(404);
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
