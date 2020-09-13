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
		"language"		=> "english"
	];
}

function zend_activate() {}
function zend_deactivate() {}
function zend_upgrade() {}

?>