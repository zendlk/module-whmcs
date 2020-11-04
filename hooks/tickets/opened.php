<?php

use WHMCS\Database\Capsule;
use Zend\Support\Config as Zend_Config;
use Zend\API\Messages as Message;

/**
 * This hook is responsible for sending out notifications
 * to both administrators and user who initiate the request
 *
 * Hook: TicketOpen
 * Target: Client, Administrators
 *
 */
// add_hook("TicketOpen", 1, function($params) {
add_hook("AdminAreaViewTicketPage", 1, function($params) {

	/**
	 * Query any extra information about the hook params
	 * before processing with the request.
	 */

	/**
	 * We need a new instance of Zend configuration to
	 * continue sending SMS notifications to user.
	 */
	$Config = new Zend_Config([
		"token"     => Capsule::table('tbladdonmodules')->select('value')->where('module', 'zend')->where('setting', 'api_token')->first()->value,
		"sender"	=> Capsule::table('tbladdonmodules')->select('value')->where('module', 'zend')->where('setting', 'sender_id')->first()->value,
		"version"	=> "1.0",
	]);

	$Admin_Template = Capsule::table("mod_zend_templates")->where([ ["type", "admin"], ["name", "On_NewTicket"] ])->first();
	if ( $Admin_Template->is_active ):
		error_log("admin");
	endif;

	logModuleCall('zend', 'hook::TicketOpen', $Config, $Template);

});

?>
