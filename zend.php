<?php

/**
 * Terminate the process if this script is called outside
 * of WHMCS system.
 */
if (!defined("WHMCS")) die("This file cannot be accessed directly");

function zend_config() {
	return [
		"name"          => "Zend",
		"description"   => "Send notification to your customers with ease",
		"author"		=> "Avanciro (PVT) LTD",
		"version"		=> "1.0.0",
		"language"		=> "english",
		"fields"		=> [
			"api_key"			=> [
				"FriendlyName"	=> "API Key",
				"Type"			=> "text"
			],
			"api_secret"		=> [
				"FriendlyName"	=> "API Secret",
				"Type"			=> "text"
			],
			"sender_id"			=> [
				"FriendlyName"	=> "Sender ID",
				"Type"			=> "text",
				"Default"		=> "Zend"
			]
		]
	];
}

function zend_activate() {
	return [
		"status" => "success",
		"description" => "Zend module successfully activated"
	];
}
function zend_deactivate() {
	return [
		"status" => "success",
		"description" => "Zend module successfully deactivated"
	];
}

?>