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

interface ServiceInterface {
	
	/**
	 * default method called if no valid POST data is provided
	 */
	public function defaultMethod();
	
	/**
	 * provide documentation information for the service
	 */
	public static function getServiceDocs();
	
	
	/**
	 * returns the response as array
	 */
	public function __toArray();
	
	
	/**
	 * returns the HTTP status code of the service response
	 */
	public function getStatusCode();
	
}
