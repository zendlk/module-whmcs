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
add_hook("TicketOpen", 1, function($params) {

	$Template = Capsule::table("mod_zend_templates")->where([ ["hook", "TicketOpen"], ["type", "client"] ])->first();
	if ( $Template->is_active ):

		logModuleCall('zend', 'hook::TicketOpen', $params, $Template);

	endif;

});

?>
