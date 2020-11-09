<?php

use WHMCS\Database\Capsule;
use Zend\API\SMS\Message;
use Zend\Support\Config as Zend_Config;

/**
 * This hook is responsible for sending out notifications
 * to both administrators and user who initiate the request
 *
 * Hook: TicketClose
 * Target: Client, Administrators
 *
 */
add_hook("TicketClose", 1, function($params) {

	$Template = Capsule::table("mod_zend_templates")->where([ ["hook", "TicketClose"], ["type", "client"] ])->first();
	if ( $Template->is_active ):

        $Ticket = Capsule::table("tbltickets")->where("id", $params["ticketid"])->first();
		$Client = Capsule::table("tblclients")->where("id", $Ticket->userid)->first();

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
				"{ticket_number}"	=> $Ticket->tid
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
