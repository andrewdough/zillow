<?php

// class that handles interaction with Zillow REST API
// overrides the call method to inject Zillow specific needs
class ZillowAPI extends CurlRequest
{
	const BASE_URL = 'http://www.zillow.com/webservice';
	const API_KEY = 'X1-ZWz1dyb53fdhjf_6jziz';
	const CONNECT_TIMEOUT = 10;
	const RESPONSE_TIMEOUT = 10;

	private $uri;

	public function __construct($uri)
	{
		$this->uri = $uri;
		parent::__construct(self::CONNECT_TIMEOUT, self::RESPONSE_TIMEOUT);
	}

	/**
	 * call
	 *
	 * Calls the Zillow API with a given HTTP method and parameters
	 * Returns raw response
	 *
	 * @param string $method HTTP method for API call
	 * @param array $params Parameters for API call
	 * @return string
	 */
	public function call($method, array $params=array())
	{
		$url = self::BASE_URL . $this->uri;
		$params['zws-id'] = !empty($params['zws-id']) ? $params['zws-id'] : self::API_KEY;

		try {
			return parent::call($url, $method, $params);
		} catch (Exception $e) {
			// TODO: better error handling
			http_response_code(404);
			echo $e->getMessage();

			return false;
		}
	}
}
