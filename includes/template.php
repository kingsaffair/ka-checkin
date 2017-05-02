<?php
/*
 * King's Affair 2012 Ticketing System
 *
 * Developed and designed by Andrew Lee 2011-2012.
 * Copyright 2011 Andrew Lee. All rights reserved.
 *
 *
 * Template class which extends smarty
 *
 */

if (!defined('IN_KA'))
	exit('Not supposed to be here!');

require('includes/Smarty/Smarty.class.php');
require('includes/mobile_detect.php');

$GLOBALS['mobile_detect'] = new Mobile_Detect();

class KATemplate extends Smarty {

	function __construct($enable_cache = false)
	{
		
		global $config, $mobile_detect, $session;
		
		parent::__construct();
		
		$prefix = '';
		if ($mobile_detect->isMobile()) {
			$prefix = 'm_';
		}
		
		$this->setTemplateDir($config['template']['template_dir'] . $prefix . 'templates' . DS);
		$this->setCompileDir($config['template']['template_dir'] . $prefix . 'compile' . DS);
		$this->setConfigDir($config['template']['template_dir'] . $prefix . 'config' . DS);
		$this->setCacheDir($config['template']['template_dir'] . $prefix . 'cache' . DS);
		
		// Set to false on production
		$this->compile_check = !$config['general']['production'];
		
		if ($enable_cache) {
			
			//$this->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
			
		}
		
		$this->assign($config['template_assign']);
		$this->assign('md', $mobile_detect);
		
		if ($session->isLoaded())
			$this->assign('user', $session);
		
		$this->registerPlugin('function','url','KATemplate::mask_url', true);
		$this->registerPlugin('function','resource','KATemplate::mask_resource', true);
		
		// Built-in date format is not very good
		$this->registerPlugin('modifier','formatdate','KATemplate::formatdate', true);
		
	}
	
	
	static function mask_resource($params, $smarty) {
	
		if (!isset($params['src'])) {
			$params['src'] = '';
		}
	
		return mask_resource($params['src']);
	
	}
	
	static function mask_url($params, $smarty) {
		
		if (!isset($params['mode'])) {
			$params['mode'] = '';
		}
		
		if (!isset($params['arg']))
			$params['arg'] = '';
			
		return mask_url($params['mode'], $params['arg']);
		
	}
	
	static function formatdate($timestamp, $format='j D Y') {
		
		return date($format, $timestamp);
		
	}
	
	static function displayGeneral($title, $description) {
		
		global $session;
		
		$template = new KATemplate();
		
		$template->assign('page_name', $title);
		
		$template->assign('description', $description);
		
		$template->display('general.tpl');
	}
	
	static function error404() {
	
		$template = new KATemplate();
		
		$template->assign('page_name', $title);
		
		$template->display('error404.tpl');
		
	}

}

?>