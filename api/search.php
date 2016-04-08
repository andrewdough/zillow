<?php

require 'classes/CurlRequest.php';
require 'classes/ZillowAPI.php';

// TODO: some kind of routing to support more APIs
// ... validation and caching
$searchAPI = new ZillowAPI('/GetSearchResults.htm');

$cityStateZip = '';
if (!empty($_GET['city'])) {
	$cityStateZip .= $_GET['city'] . ' ';
}
if (!empty($_GET['state'])) {
	$cityStateZip .= $_GET['state'] . ' ';
}
if (!empty($_GET['zip'])) {
	$cityStateZip .= $_GET['zip'] . ' ';
}
$response = $searchAPI->call('GET', array(
	'address'		=> !empty($_GET['address']) ? $_GET['address'] : null,
	'citystatezip'	=> $cityStateZip
));

if ($response !== false) {
	// create DOM object from XML response
	$xml = new DOMDocument();
	$xml->loadXML($response);

	// create DOM object from XSL file
	$xsl = new DOMDocument();
	$xsl->load('searchresults.xsl');

	if ($xml !== false && $xsl !== false) {
		// process XSL
		$proc = new XSLTProcessor();
		$proc->importStylesheet($xsl);
		$newdom = $proc->transformToDoc($xml);

		// respond with markup
		echo $newdom->saveXML();
	} else {
		http_response_code(404);
	}
}
