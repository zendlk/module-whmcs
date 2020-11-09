<?php

if (!defined("WHMCS")) die("This file cannot be accessed directly");

/**
 * Include Zend PHP SDK to perform actions when there is new
 * event happend from action hook.
 */
include_once dirname(__FILE__)."/libs/zendlk/php-sdk/support/Config.php";
include_once dirname(__FILE__)."/libs/zendlk/php-sdk/api/SMS/Message.php";


/**
 * We need to get a list of items in the hooks directory
 * and then load them into the WHMCS known space to run
 * them according to the WHMCS.
 */
include_once dirname(__FILE__)."/hooks/invoices/created.php";
include_once dirname(__FILE__)."/hooks/tickets/open.php";

?>