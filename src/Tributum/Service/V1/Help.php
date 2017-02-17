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

use Tributum\Service\V1\Service;
use Tributum\Exceptions\MethodFailedException;

class Help extends Service implements ServiceInterface {
		
	/*
	 * class variables
	 */
	
	
	/*
	 * getter/setter
	 */
	
	
	/**
	 * constructor
	 * @param Array|null $data the data given in POST request
	 */
	public function __construct($data) {
		
		// parent constructor
		parent::__construct($data);
	}
	
	
	/**
	 * default method called if no valid POST data is provided
	 * @return void
	 */
	public function defaultMethod() {
		
		// main help
		$this->mainHelp();
	}
	
	
	/**
	 * service documentation for the Help service
	 * @return Array
	 */
	public static function getServiceDocs() {
		
		// return documentation
		return array(
			'service' => 'Help',
			'methods' => array(
				'getHelp' => 'array(servicename)',
			),
			'httpStatus' => array(200,404,500),
			'responses' => array(
				'getHelp' => 'Array containing keys "service" = (string) the service name, "methods" = (Array) the available methods and their parameters, "httpStatus" = (Array) the possible HTTP status returnable by the service, "responses" = (Array) explaining the methods and their responses, "info" (string) description of the service',
			),
			'info' => 'Provides help and documentations for the API and the available services.',
		);
	}
	
	
	/**
	 * default status code without exception
	 * @return int
	 */
	public function getStatusCode() {
		return 200;
	}
	
	
	/**
	 * returns the objects response as array to use in HttpResponse
	 * @return Array
	 */
	public function __toArray() {
		
		return array(
			'status' => 'OK',
			'data' => array(
				'statusCode' => $this->getStatusCode(),
				'docs' => $this->getApiArray(),
			),
		);
	}
	
	
	/**
	 * sets the main help as response
	 */
	private function mainHelp() {
		
		// prepare help json
		$helpJson = array(
			'method' => 'getHelp',
			'params' => array('<Service>',),
		);
		// prepare post json for calling methods
		$postJson = array(
			'method' => '<methodName>',
			'params' => array('[<param1>[, <param2>[, ...]]]',),
		);
		// prepare help
		$this->setApiArray(array(
			'services' => Service::helpGetServices(),
			'info' => 
				'For available services see "services" key, to get help per service send '.json_encode($helpJson).' as POST key "data" to "/v1/Help/", where <Service> is one the service names from the "services" key.'.PHP_EOL
				.'To call a method send POST key "data" with '.json_encode($postJson).' to service URI, where "<methodName>" name of the method and "<paramX>" the parameter for the method.'.PHP_EOL
				.'Service responses are available in key "data", with keys "statusCode" for HTTP status code and "response" containing the data returned by service method.',
		));
	}
	
	
	/**
	 * sets the response according to the topic given in $service
	 * @param string $service the service name to request help
	 */
	public function getHelp(string $service) {
		
		// check if $service exists
		$serviceName = '\\Tributum\\Service\\V1\\'.ucfirst(strtolower($service));
		if(class_exists($serviceName) === true) {
			$this->setApiArray($serviceName::getServiceDocs());
		} else {
			throw new ServiceNotFoundException('Service "'.$service.'" does not exists in version "v1"');
		}
	}
}
