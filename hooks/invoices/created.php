<?php

use WHMCS\Database\Capsule;
use Zend\API\SMS\Message;
use Zend\Support\Config as Zend_Config;

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

		$Invoice = Capsule::table("tblinvoices")->where("id", $params["invoiceid"])->first();
		$Client = Capsule::table("tblclients")->where("id", $Invoice->userid)->first();

		/**
		 * We need to format the phone number of the client to the
		 * E164 before start processing the request. We also need to
		 * verify if the country code is Sri Lanka or not.
		 */
		$phone = explode(".", str_replace(" ", "", $Client->phonenumber));
		if ( $phone[0] == "+94" ):

			/**
			 * We need to replace the strings that need to be modified
			 * before sending the SMS to the client.
			 */
			$template_parts = [
				"{first_name}"		=> $Client->firstname,
				"{last_name}"		=> $Client->lastname,
				"{invoice_id}"		=> $Invoice->id,
				"{due_date}"		=> $Invoice->duedate,
				"{subtotal}"		=> $Invoice->subtotal
			];
			$Text = str_replace(array_keys($template_parts), $template_parts, $Template->message);

			/**
			 * We need a new instance of Zend configuration to
			 * continue sending SMS notifications to user.
			 */
			$Config = Zend_Config::create([
				"token"		=> Capsule::table('tbladdonmodules')->select('value')->where('module', 'zend')->where('setting', 'api_token')->first()->value,
				"sender"	=> Capsule::table('tbladdonmodules')->select('value')->where('module', 'zend')->where('setting', 'sender_id')->first()->value
			]);

			$Message = Message::compose($Config, [
				"to"	=> [ $phone[0].$phone[1] ],
				"text"	=> $Text
			]);
			$Message->send();

		endif;

	endif;

});

?>
