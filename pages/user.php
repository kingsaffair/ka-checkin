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

if ($mode == 'auth-login') {

	/*
	 * 1.	Either read an existing session or try to create
	 *		a new one.
	 */
	if (!$session->createSession()) {
		trigger_error('Unable to create a new Session!', E_USER_ERROR);
		die();
	}

	redirect_to('');
	
	die();
	
} elseif ($mode == 'auth-logout') {
	
	/*
	 * Destroy the existing session and go back to home
	 */

	if ($session->readSession(false))
		$session->destroySession();
		
	$session->user_level = 0;
		
	KATemplate::displayGeneral('Logout Successful', 'You have been logged out successfully!');
	
} else {
	
	KATemplate::error404();
	
}

 ?>