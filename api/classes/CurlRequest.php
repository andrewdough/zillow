<?php

// class that handles cURL requests
class CurlRequest
{
	private $connectTimeout;
	private $responseTimeout;

	public function __construct($connectTimeout=5, $responseTimeout=5)
	{
		$this->connectTimeout = intval($connectTimeout);
		$this->responseTimeout = intval($responseTimeout);
	}

	/**
	 * call
	 *
	 * Sends a cURL request and returns the response
	 *
	 * @param string $url Endpoint URL
	 * @param string $method HTTP method for request
	 * @param array $params Parameters for request
	 * @return string
	 */
	protected function call($url, $method='GET', array $params=array())
	{
		$responseData = array();

		// configure session options
		$options = array(
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_SSL_VERIFYPEER  => false,
			CURLOPT_CONNECTTIMEOUT  => $this->connectTimeout,
			CURLOPT_TIMEOUT         => $this->responseTimeout
		);

		$method = strtoupper($method);
		if ($method == 'POST') {
			$options[CURLOPT_CUSTOMREQUEST] = 'POST';
			$options[CURLOPT_POST] = true;
			$options[CURLOPT_POSTFIELDS] = json_encode($params);
		} else if ($method == 'GET') {
			if (strstr($url, '?') !== false) {
				$url .= '&' . http_build_query($params);
			} else {
				$url .= '?' . http_build_query($params);
			}
		} else {
			throw new Exception('Unhandled request method ' . $method);
		}

		// initialize connection
		$urlConn = curl_init($url);
		if (!$urlConn) {
			throw new Exception('Curl initialization failed');
		}

		if (!curl_setopt_array($urlConn, $options)) {
			throw new Exception('Failed curl session configuration');
		}
		
		// execute request
		$response = curl_exec($urlConn);

		if (!$response) {
			throw new Exception('Failed cURL request: ' . curl_error($urlConn), curl_errno($urlConn));
		}
		
		// check API result
		$code = curl_getinfo( $urlConn, CURLINFO_HTTP_CODE );

		if ($code != 200 && $code != 201) {
			throw new Exception("Failed cURL request with HTTP code $code");
		}

		curl_close( $urlConn );

		return $response;
	}
}
