<?php

use WHMCS\Database\Capsule;
use Zend\Support\Config as Zend_Config;
use Zend\API\Messages as Message;

add_hook('InvoiceCreated', 1, function($params) {

	/**
	 * Check if the addon have active notification
	 * configured for the following hook.
	 *
	 * Hook: InvoiceCreated
	 * Target: Client
	 *
	 */
	$Template = Capsule::table("mod_zend_templates")->where([ ["name", "On_NewInvoice"], ["type", "invoice"] ])->first();
	if ( $Template->is_active ):

		/**
		 * We need a new instance of Zend configuration to
		 * continue sending SMS notifications to user.
		 */
		$Config = new Zend_Config([
			"token"     => Capsule::table('tbladdonmodules')->select('value')->where('module', 'zend')->where('setting', 'api_token')->first()->value,
			"sender"	=> Capsule::table('tbladdonmodules')->select('value')->where('module', 'zend')->where('setting', 'sender_id')->first()->value,
			"version"	=> "1.0",
		]);

		$Invoice = Capsule::table("tblinvoices")->where("id", $params["invoiceid"])->first();
		$Client = Capsule::table("tblclients")->where("id", $Invoice->userid)->first();

		$Message = new Message($Config);
		$Message->to("sms");
		$Message->to(["+94777120160"]);
		$Message->text("Sample SMS Message");
		$Message->send();

		logModuleCall('zend', 'hook::InvoficeCreated', $Invoice, $Client);

	endif;

});

?>
