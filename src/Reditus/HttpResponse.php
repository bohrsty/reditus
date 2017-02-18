<?php

/*
 * This file is part of the Reditus project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Reditus;

use Reditus\Exceptions\JsonNotParsableException;

class HttpResponse {
	
	/*
	 * class variables
	 */
	private $data;
	private $type;
	private $statusCode;
	private $headers;
	
	/*
	 * getter/setter
	 */
	public function getData() {
		return $this->data;
	}
	public function setData(Array $data) {
		$this->data = $data;
	}
	public function getType() {
		return $this->type;
	}
	public function setType(string $type) {
		$this->type = $type;
	}
	public function getStatusCode() {
		return $this->statusCode;
	}
	public function setStatusCode(int $statusCode) {
		$this->statusCode = $statusCode;
	}
	public function getHeaders() {
		return $this->headers;
	}
	public function setHeaders(Array $headers) {
		$this->headers = $headers;
	}
	
	
	/**
	 * constructor
	 * @param array $data the response data as array
	 * 		[
	 * 			'status' => 'ERROR|OK',
	 * 			'data' => [],
	 * 		]
	 * @param string $type the response type (default: 'json')
	 */
	public function __construct(Array $data, string $type = 'json') {
		
		// set class variables
		$this->setData($data);
		$this->setType($type);
		// set initial status code
		$this->setStatusCode(200);
		// add default headers
		$this->defaultHeaders();
	}
	
	
	/**
	 * translates the numeric status code in the text send by webservers
	 * @param int $statusCode the numeric status code
	 * @return string the text send by webservers
	 */
	public static function statusCodeToText(int $statusCode) {
		
		// codes
		$codes = array(
			200 => 'OK',
			401 => 'Unauthorized',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
		);
		
		// return text
		return $codes[$statusCode];
	}
	
	
	/**
	 * prepares the response and send it
	 */
	public function send() {
		
		// check $this->type
		$output = '';
		switch($this->getType()) {
			
			default:
				$output = $this->sendJson();
				break;
		}
		
		// send status code header
		header('HTTP/1.1 '.$this->getStatusCode(). ' '.HttpResponse::statusCodeToText($this->getStatusCode()));
		// send the other headers
		foreach($this->getHeaders() as $header => $value) {
			header($header.': '.$value);
		}
		
		// send output
		echo $output;
	}
	
	
	/**
	 * adds $header and set its $value to the response, if $header already
	 * set and $replace is true, it replaces the current value
	 * @param string $header the name of the header
	 * @param string $value the value of the header
	 * @param bool $replace if true, replaces existing values, if false ignores the header (default: true)
	 */
	public function addHeader(string $header, string $value, bool $replace = true) {
		
		// get current headers
		$headers = $this->getHeaders();
		
		// set (or reset) the header
		if($replace === true || !isset($headers[$header])) {
			$headers[$header] = $value;
		}
		
		// set headers back
		$this->setHeaders($headers);
	}
	
	
	/**
	 * adds the default headers
	 */
	private function defaultHeaders() {
		
		// set Content-Type (according to $this->type)
		$contentType = '';
		switch($this->getType()) {
			
			default:
				// JSON
				$contentType = 'application/json';
				break;
		}
		$this->addHeader('Content-Type', $contentType);
	}
	
	
	/**
	 * prepares the response as JSON
	 * @return string $this->data as JSON
	 */
	private function sendJson() {
		
		// get data
		$data = $this->getData();
		
		// add general fields to answere (api information)
		$data['version'] = 'v1';
		$data['uri'] = $_SERVER['REQUEST_URI'];
		
		// return JSON data
		return json_encode($data);
	}
}
