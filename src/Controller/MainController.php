<?php

/*
 * This file is part of the Tributum project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Controller;

class MainController extends \Controller {
	
	/**
	 * constructor
	 */
	public function __construct() {
		
		// parent constructor
		parent::__construct();
	}
	
	/**
	 * runs the app
	 * 
	 * @return void
	 */
	public function run() {
		
		echo 'Controller\\MainController::run()';
	}
}
