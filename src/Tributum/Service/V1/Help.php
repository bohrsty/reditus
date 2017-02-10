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
use Tributum\Exceptions\MethodNotFoundException;
use Tributum\Exceptions\ServiceNotFoundException;

class Help extends Service implements ServiceInterface {
		
	/*
	 * class variables
	 */
	private $helpArray;
	
	
	/*
	 * getter/setter
	 */
	public function getHelpArray() {
		return $this->helpArray;
	}
	public function setHelpArray(Array $helpArray) {
		$this->helpArray = $helpArray;
	}
	
	
	/**
	 * constructor
	 * @param Array|null $data the data given in POST request
	 */
	public function __construct($data) {
		
		// parent constructor
		parent::__construct($data);
		
		//  check data
		if(is_null($data) || empty($data)) {
			
			// main help
			$this->mainHelp();
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
				'docs' => $this->getHelpArray(),
			),
		);
	}
	
	
	/**
	 * sets the main help as response
	 */
	private function mainHelp() {
		
		// prepare json
		$json = array(
			'method' => 'getHelp',
			'params' => array('<Service>',),
		);
		// prepare help
		$this->setHelpArray(array(
			'services' => Service::helpGetServices(),
			'info' => 
				'For available services see "services" key, to get help per service send '
				.json_encode($json).' as POST key "data" to "/v1/Help/", where <Service> '
				.'is one the service names from the "services" key.',
		));
	}
	
	
	/**
	 * sets the response according to the topic given in $service
	 * @param string $service the service name to request help
	 */
	private function getHelp(string $service) {
		
		// check if $service exists
		$serviceName = '\\Tributum\\Service\\V1\\'.ucfirst(strtolower($service));
		if(class_exists($serviceName) === true) {
			$this->setHelpArray($serviceName::getServiceDocs());
		} else {
			throw new ServiceNotFoundException('Service "'.$service.'" does not exists in version "v1"');
		}
	}
}
