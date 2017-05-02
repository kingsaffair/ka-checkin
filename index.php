<?php
/*
 * King's Affair 2012 Ticketing System
 *
 * Developed and designed by Andrew Lee 2011-2012.
 * Copyright 2011 Andrew Lee. All rights reserved.
 *
 */

define('IN_KA', true);
define('INCLUDE_PATH', './');

/*
 * Read the config files.
 */
$GLOBALS['config'] = parse_ini_file(INCLUDE_PATH . 'config.ini', true);


/*
 * Set custom php.ini settings
 */
ini_set('error_log', $config['error']['log']);

ini_set('session.save_path', $config['session']['save_path']);
ini_set('session.name', $config['session']['name']);
ini_set('session.gc_maxlifetime',7200);
ini_set('session.cookie_lifetime', 43200);

ini_set('date.timezone', $config['general']['timezone']);


/*
 * Include files.
 */

// Load the custom error handler
require(INCLUDE_PATH . 'includes/error.php');

// Load global functions
require(INCLUDE_PATH . 'includes/functions.php');

// Load the template handler
require(INCLUDE_PATH . 'includes/template.php');

// Load the authentication files
require(INCLUDE_PATH . 'includes/database.php');
require(INCLUDE_PATH . 'includes/session.php');

$mode = '';

if (isset($_GET['mode']))
	$mode = $_GET['mode'];

if ($mode == 'auth-login' || $mode == 'auth-logout') {
	require(INCLUDE_PATH . 'pages/user.php');
} elseif ($mode == '') {
	require(INCLUDE_PATH . 'pages/index.php');
} else {
	require(INCLUDE_PATH . 'pages/tickets.php');
}

?>