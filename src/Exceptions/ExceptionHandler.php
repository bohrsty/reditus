<?php

/*
 * This file is part of the Tributum project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exceptions;

class ExceptionHandler extends \Exceptions {
	
	/**
	 * constructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
	}
	
	/**
	 * handles thrown exceptions
	 * 
	 * @param \Exception $e
	 * @return void
	 */
	public function handle(\Exception $e) {
		
		echo 'Exceptions\\ExceptionHandler::handle()';
	}
}
