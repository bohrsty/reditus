<?php

/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
use Reditus\Controller\MainController;
use Reditus\ExceptionHandler;

// require autoloader
require_once(__DIR__.'/../vendor/autoload.php');

// run main controller
try {
	
	$app = new MainController();
	$app-> run();
} catch(\Throwable $e) {
	
	$handler = new ExceptionHandler();
	$handler->handle($e);
}
