<?php

include_once "templates/Dashboard.php";
use WHMCS\Database\Capsule;

/**
 * Terminate the process if this script is called outside
 * of WHMCS system.
 */
if (!defined("WHMCS")) die("This file cannot be accessed directly");



/**
 * Here we configure the parameters for the module
 * that need to filled from the WHMCS administration
 * area.
 */
function zend_config() {
	return [
		"name"          => "Zend",
		"description"   => "Send notification to your customers with ease",
		"author"		=> "Avanciro (PVT) LTD",
		"version"		=> "1.0.0",
		"language"		=> "english",
		"fields"		=> [
			"api_token"			=> [
				"FriendlyName"	=> "API Token",
				"Type"			=> "text"
			],
			"sender_id"			=> [
				"FriendlyName"	=> "Sender ID",
				"Type"			=> "text",
				"Default"		=> "DEMO"
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

	try {

		/**
		 * Create database schemas as per our need on the
		 * activation method. Followings are the schemas
		 * created during this activation process.
		 *
		 *    - mod_zend_templates
		 *
		 */
		Capsule::schema()->create(
			'mod_zend_templates',
			function($schema) {
				$schema->increments('id');
				$schema->string('hook');
				$schema->text('message');
				$schema->boolean('is_active');
			}
		);

		/**
		 * We also need to seed some important data into
		 * the template schema. Administrator need to have
		 * some predefined templates to work with.
		 */
		Capsule::table('mod_zend_templates')->insert([
			"hook" => "InvoiceCreated",
			"message" => "Hi {firstname}, You have new invoice generated with id {invoice_id}. The last day of payment is {due_date}. Kindly pay your bill before due date to use services without interruption."
		]);

		return [
			"status" => "success",
			"description" => "Zend module successfully activated"
		];

	} catch (\Exception $e) {

		return [
            "status" => "error",
            "description" => "Unable to create schema: ".$e->getMessage()
        ];

	}
}



/**
 * This method will invoked in an event of module deactivation
 * from the WHMCS system. We can use this to de-initialize
 * things from the WHMCS system.
 */
function zend_deactivate() {

	try {

		Capsule::schema()->dropIfExists('mod_zend_templates');

		return [
			"status" => "success",
			"description" => "Zend module successfully deactivated"
		];

	} catch (\Exception $e) {
		return [
			"status" => "error",
			"description" => "Unable to drop schema".$e->getMessage()
		];
	}

}


/**
 * Identify the requested tab and dispatch the request
 * according to the tab controller. A simple routing
 * mechanisam that helps to load relevent controller
 * method.
 */
function zend_output($vars) {
	$Dashboard = new Dashboard();
	$tab = (isset($_REQUEST['tab'])) ? $_REQUEST['tab'] : 'overview';
	$Dashboard->$tab($vars);
}

?>