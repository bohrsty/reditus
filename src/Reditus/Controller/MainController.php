<?php

/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Reditus\Controller;

use Reditus\Controller;
use Reditus\HttpRequest;
use Reditus\Exceptions\ServiceNotFoundException;
use Reditus\HttpResponse;

class MainController extends Controller {
	
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
		
		// get request
		$request = new HttpRequest();
		
		// check requesting /
		if($request->getService() === null) {
			
			// prepare help
			$serviceName = 'Reditus\\Service\\'.ucfirst($request->getVersion()).'\\Help';
		} else {
			
			// prepare service
			$serviceName = 'Reditus\\Service\\'.ucfirst($request->getVersion()).'\\'.$request->getService();
		}
		
		// check service
		if(class_exists($serviceName)) {
			
			// create service
			$service = new $serviceName($request->getData());
			
			// get service result as array
			$serviceResult = $service->__toArray();
			// get status code
			$statusCode = $service->getStatusCode();
			
			// create response
			$response = new HttpResponse($serviceResult);
			$response->setStatusCode($statusCode);
			$response->send();
		} else {
			throw new ServiceNotFoundException('Service "'.$request->getService().'" does not exists in version "'.$request->getVersion().'"');
		}
	}
}
