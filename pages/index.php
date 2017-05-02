<?php
/*
 * King's Affair 2012 Ticketing System
 *
 * Developed and designed by Andrew Lee 2011-2012.
 * Copyright 2011 Andrew Lee. All rights reserved.
 *
 *
 * User page
 *
 */
 
if (!defined('IN_KA'))
	exit('Not supposed to be here!');

$session->readSession(false);

if ($session->user_level == 0) {
	KATemplate::error404();
	die();
}

$template = new KATemplate();
		
$template->assign('page_name', 'Ticket System');

$template->assign('footer', false);
		
$template->display('ticket-general.tpl');

?>