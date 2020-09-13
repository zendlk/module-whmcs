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



/**
 * This method is invoked in the event of installation
 * of this module in the WHMCS system. We can use this
 * to bootstrap module and migrate any database schemas.
 */
function zend_activate() {
	return [
		"status" => "success",
		"description" => "Zend module successfully activated"
	];
}



/**
 * This method will invoked in an event of module deactivation
 * from the WHMCS system. We can use this to de-initialize
 * things from the WHMCS system.
 */
function zend_deactivate() {
	return [
		"status" => "success",
		"description" => "Zend module successfully deactivated"
	];
}


function zend_output($vars) {

	echo "<pre>";
		var_dump( $vars );
	echo "</pre>";

}

?>