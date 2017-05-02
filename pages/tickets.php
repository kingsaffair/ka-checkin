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

/*
 * Lookup the ticket
 */
    
$hash = trim(strtolower($mode));
    
$corrected_hashes = array(
                          "b" => "b"
                        );

$corrected_hashes_old = array(
                          "bqigjnaa" => "bqixaqpv",
                          "bqigjmdn" => "bqwsqicq",
                          "bqibvqbs" => "btfbvqbs",
                          "akigtzab" => "akiaidcs",
                          "akiaidxp" => "akwbzcry",
                          "akiokclo" => "ayfkuwmd",
                          "yknlacmd" => "aloxicnd",
                          "evgbwjdq" => "evgaimre",
                          "evgbwjnt" => "evlgttxs",
                          "evgkutpp" => "evkpembh",
                          "kgmouwdd" => "butkidcg",
                          "lfxsttgp" =>	"bmrxemah",
                          "lfxbihnc" =>	"bmrgwjxd",
                          "ljrgzcnx" =>	"butkkcmx",
                          "ljrkkchs" =>	"butbiflb",
                          "exabwmlo" => "exzkvjhd",
                          "exaajtgr" =>	"exzoyhdx",
                          "ojioizrb" =>	"bqtlezgt",
                          "ataxyibj" =>	"atyxyibj",
                          "ataokjdz" =>	"atvokjdz",
                          "bsbbvhsg" =>	"bsebvhsg",
                          "bsbbvhpr" =>	"bsogzjlx",
                          "lmqoaxsb" =>	"lxxxexct",
                          "lmqgwwbp" =>	"lxxkywlp",
                          "kkqoudpl" =>	"kydoudpl",
                          "kkqkicaj" =>	"kyhoudga",
                          "ajqbjcsl" =>	"azdkozlp",
                          "ajqpadmv" =>	"azhbjcpa"
                        );
    
if (array_key_exists($hash, $corrected_hashes))
{
    $hash = $corrected_hashes[$hash];
}

if (strlen($hash) != 8) {
	if ($session->user_level == 0) {
		KATemplate::error404();
	} else {
		KATemplate::displayGeneral('Error','Ticket could not be found.');
	}
	die();
}

$guest_sections_full = array(
	0	=> '',
	1	=> 'Clare',
	2	=> 'Emma',
	3	=> 'Robinson',
	4	=> 'Tab',
	5	=> 'Varsity',
	6	=> 'TCS',
	7	=> 'Music',
8	=> 'Ents',
	9	=> 'Food & Drink',
	10	=> 'Other',
	12	=> 'Ex-Committee',

);

$guest_sections_m = array(
	0	=> '',
	1	=> '1',
	2	=> '2',
	3	=> '3',
	4	=> 'N',
	5	=> 'N',
	6	=> 'N',
	7	=> 'M',
	8	=> 'E',
	9	=> 'F&D',
	10	=> 'O',
	12	=> 'C',
);

$case = substr($hash,0,1);
if ($case == 'r') {
	// Reentry ticket
	
	if ($session->user_level == 0) {
		KATemplate::error404();
		die();
	}
	
	$ticket = $db->querySingle($db->selectStatement('reentry','*','WHERE hash="' . $db->escape($hash) . '"'));
	
	if ($ticket === false) {
		KATemplate::displayGeneral('Error','Ticket could not be found.');
		die();
	}
	
	if ($ticket['activated'] == 0 && $config['t']['mode'] == 2) {
		
		/*
		 * We need to activate the ticket first.
		 */
		 
		if (isset($_POST['ahash']) && $_POST['ahash'] == $hash) {
			
			if ($db->queryUpdate('reentry',array('name' => $_POST['name'], 'activated' => time()),'id="' . $db->escape($ticket['id']) . '" LIMIT 1') === false) {
				KATemplate::displayGeneral('Error', 'An error occured updating the database.');
				die();
			}
			
			KATemplate::displayGeneral('Success!', 'The re-entry ticket was successfully activated!');
			
			die();
			
		}
		
		$template = new KATemplate();
		
		$template->assign('page_name', 'Ticket ' . $hash);
		
		$template->assign('hash', $hash);
		
		$template->display('ticket-reentry.tpl');
		
		die();
		
	} else {
		
		if ($config['t']['mode'] == 2 && isset($_POST['hash']) && $_POST['hash'] == $hash) {
			/*
			 * Retrieve information about all tickets related to this one
			 */
			
			$errorflag = false;
			$list = array();
			
			if (isset($_POST['t_' . $ticket['id']]) && $_POST['t_' . $ticket['id']] == '1') {
									
				if ($ticket['entered'] != 0) {
					$errorflag = true;
					break;
				}
				
				$list[] = 'id="' . $db->escape($ticket['id']) . '"';
				
			}
			
			if ($errorflag) {
				
				KATemplate::displayGeneral('Error', array(
					'One or more of the tickets were already ' . ($config['t']['mode'] == 1 ? 'collected' : 'checked in') . '.',
					'Click <a href="' . mask_url($hash) . '">here</a> to go back.'));
					
			} elseif (count($list) == 0) {
				print_r($_POST);
				KATemplate::displayGeneral('Error', array(
					'No tickets were selected.',
					'Click <a href="' . mask_url($hash) . '">here</a> to go back.'));
					
			} else {
			
				if ($db->queryUpdate('reentry', array(($config['t']['mode'] == 1 ? 'collected' : 'entered') => time()), implode(' OR ', $list)) === false) {
					KATemplate::displayGeneral('Error', 'An error occured while updating the database.');
					die();
				}
				
				KATemplate::displayGeneral('Success!', 'The tickets were successfully marked as ' . ($config['t']['mode'] == 1 ? 'collected' : 'checked in') . '!');
			
			}
			
			die();
		
		}
		
		$data = array();
		$complete = ($ticket['entered'] != 0) || ($config['t']['mode'] == 1);
		$separate = false;
		
		$data[] = array(
				'id'		=> $ticket['id'],
				'hash'		=> $hash,
				'name'		=> ($config['t']['mode'] == 1 ? 'Reentry Ticket' : $ticket['name']),
				'collected'	=> 1,
				'committee'	=> 0,
				'entered'	=> $ticket['entered'],
				'type'		=> 'r',
				'primary'	=> 1,
				'selected'	=> 1,
				'entrance'	=> 'Porter&rsquo;s Lodge Entrance'
				);
		
		$template = new KATemplate();
		
		$template->assign('page_name', 'Ticket ' . $hash);
		
		$template->assign('hash', $hash);
		
		$template->assign('complete', $complete);		
		$template->assign('separate', $separate);
		
		$template->assign('guest_sections_full', $guest_sections_full);
		$template->assign('guest_sections_m', $guest_sections_m);
		
		$template->assign('tickets', $data);
		
		if ($config['t']['mode'] == 1)
			$template->display('ticket-collection.tpl');
		else
			$template->display('ticket-entry.tpl');
		
	}

} elseif ($case == 'g') {
	// Guest ticket
	
	$ticket = $db->querySingle($db->selectStatement('guest_list','*','WHERE hash="' . $db->escape($hash) . '"'));
	
	if ($session->user_level == 0) {
		
		if ($ticket === false) {
			KATemplate::error404();
			die();
		}
		
		/*
		 * Just display information about this ticket
		 */
		
		$template = new KATemplate();
		
		$template->assign('page_name', 'Ticket ' . $hash);
		
		$template->assign('ticket', array(
				'hash'		=> $hash,
				'name'		=> $ticket['fname'] . ' ' . $ticket['lname'],
				'type'		=> 'Guest List',
				'primary'	=> ($ticket['primary_id'] == 0 ? 'Yes' : 'No')
				));
		
		$template->display('ticket-basic.tpl');
		
		die();
		
	} else {
	
		if ($ticket === false) {
			KATemplate::displayGeneral('Error','Ticket could not be found.');
			die();
		}
		
		$primary_id = ($ticket['primary_id'] == 0 ? $ticket['id'] : $ticket['primary_id']);
		
		if ($config['t']['mode'] == 2 && isset($_POST['hash']) && $_POST['hash'] == $hash) {
			/*
			 * Retrieve information about all tickets related to this one
			 */
			
			$tickets = $db->query($db->selectStatement('guest_list','*','WHERE primary_id="' . $db->escape($primary_id) . '" OR id="' . $db->escape($primary_id) . '" ORDER BY primary_id ASC, id ASC'));
		
			if ($tickets === false) {
				KATemplate::displayGeneral('Error', 'An error occured accessing the database.');
				die();
			}
			
			$errorflag = false;
			$list = array();
			
			while (($r = $db->fetchResult($tickets)) !== false) {
				
				if (isset($_POST['t_' . $r['id']]) && $_POST['t_' . $r['id']] == '1') {
										
					if ($r['entered'] != 0) {
						$errorflag = true;
						break;
					}
					
					$list[] = 'id="' . $db->escape($r['id']) . '"';
					
				}
				
			}
			
			if ($errorflag) {
				
				KATemplate::displayGeneral('Error', array(
					'One or more of the tickets were already ' . ($config['t']['mode'] == 1 ? 'collected' : 'checked in') . '.',
					'Click <a href="' . mask_url($hash) . '">here</a> to go back.'));
					
			} elseif (count($list) == 0) {
				print_r($_POST);
				KATemplate::displayGeneral('Error', array(
					'No tickets were selected.',
					'Click <a href="' . mask_url($hash) . '">here</a> to go back.'));
					
			} else {
			
				if ($db->queryUpdate('guest_list', array(($config['t']['mode'] == 1 ? 'collected' : 'entered') => time()), implode(' OR ', $list)) === false) {
					KATemplate::displayGeneral('Error', 'An error occured while updating the database.');
					die();
				}
				
				KATemplate::displayGeneral('Success!', 'The tickets were successfully marked as ' . ($config['t']['mode'] == 1 ? 'collected' : 'checked in') . '!');
			
			}
			
			die();
		
		}
		
		/*
		 * Retrieve information about all tickets related to this one
		 */
		
		$tickets = $db->query($db->selectStatement('guest_list','*','WHERE primary_id="' . $db->escape($primary_id) . '" OR id="' . $db->escape($primary_id) . '" ORDER BY primary_id ASC, id ASC'));
		
		if ($tickets === false) {
			KATemplate::displayGeneral('Error', 'An error occured accessing the database.');
			die();
		}
		
		$data = array();
		$complete = true;
		$separate = true;
		
		while (($r = $db->fetchResult($tickets)) !== false) {
			
			if ($r['section'] == 11) {
				$entrance = 'QueueJump Entrance';
			} elseif ($r['section'] == 12 || $r['section'] == 13) {
				$entrance = 'Normal Entrance';
			} else {
				$entrance = 'Porter&rsquo;s Lodge Entrance';
			}
			
			$data[] = array(
					'id'		=> $r['id'],
					'hash'		=> $r['hash'],
					'name'		=> $r['fname'] . ' ' . $r['lname'],
					'collected'	=> 1,
					'committee'	=> 0,
					'entered'	=> $r['entered'],
					'type'		=> 'g',
					'gl'		=> $r['section'],
					'primary'	=> ($r['primary_id'] == 0),
					'selected'	=> ($r['id'] == $ticket['id'] ? true : false),
					'entrance'	=> $entrance
					);
			
			if ($config['t']['mode'] == 2) {
				if ($r['entered'] == 0)
					$complete = false;
			}
			
		}
		
		$template = new KATemplate();
		
		$template->assign('page_name', 'Ticket ' . $hash);
		
		$template->assign('hash', $hash);
		
		$template->assign('complete', $complete);		
		$template->assign('separate', $separate);
		
		$template->assign('guest_sections_full', $guest_sections_full);
		$template->assign('guest_sections_m', $guest_sections_m);
		
		$template->assign('tickets', $data);
		
		if ($config['t']['mode'] == 1)
			$template->display('ticket-collection.tpl');
		else
			$template->display('ticket-entry.tpl');
			
	
	}
		
} else {
	
	$ticket = $db->querySingle($db->selectStatement('tickets','*','WHERE hash="' . $db->escape($hash) . '" AND waiting=0'));
	
	if ($session->user_level == 0) {
		
		if ($ticket === false) {
			KATemplate::error404();
			die();
		}
		
		/*
		 * Just display information about this ticket
		 */
		
		$template = new KATemplate();
		
		$template->assign('page_name', 'Ticket ' . $hash);
		
		$template->assign('ticket', array(
				'hash'		=> $hash,
				'name'		=> $ticket['fname'] . ' ' . $ticket['lname'],
				'type'		=> ($r['guest_list'] != 0 ? 'Guest List' : ($r['premium'] ? 'Queue Jump' : 'Normal')),
				'primary'	=> ($ticket['primary_ticket'] ? 'Yes' : 'No')
				));
		
		$template->display('ticket-basic.tpl');
		
		die();
		
	} else {
				
		if ($ticket === false) {
			KATemplate::displayGeneral('Error','Ticket could not be found.');
			die();
		}
		
		if (isset($_POST['hash']) && $_POST['hash'] == $hash) {
			/*
			 * Retrieve information about all tickets related to this one
			 */
			
			$tickets = $db->query($db->selectStatement('tickets','*','WHERE crsid="' . $db->escape($ticket['crsid']) . '" AND waiting=0 ORDER BY primary_ticket DESC, id ASC'));
			
			if ($tickets === false) {
				KATemplate::displayGeneral('Error', 'An error occured accessing the database.');
				die();
			}
			
			$errorflag = false;
			$list = array();
			
			while (($r = $db->fetchResult($tickets)) !== false) {
				
				if (isset($_POST['t_' . $r['id']]) && $_POST['t_' . $r['id']] == '1') {
					
					if ($config['t']['mode'] == 1) {
						if ($r['collected'] != 0) {
							$errorflag = true;
							break;
						}
					} else {
						if ($r['entered'] != 0) {
							$errorflag = true;
							break;
						}
					}
					
					$list[] = 'id="' . $db->escape($r['id']) . '"';
					
				}
				
			}
			
			if ($errorflag) {
				
				KATemplate::displayGeneral('Error', array(
					'One or more of the tickets were already ' . ($config['t']['mode'] == 1 ? 'collected' : 'checked in') . '.',
					'Click <a href="' . mask_url($hash) . '">here</a> to go back.'));
					
			} elseif (count($list) == 0) {
				print_r($_POST);
				KATemplate::displayGeneral('Error', array(
					'No tickets were selected.',
					'Click <a href="' . mask_url($hash) . '">here</a> to go back.'));
					
			} else {
			
				if ($db->queryUpdate('tickets', array(($config['t']['mode'] == 1 ? 'collected' : 'entered') => time()), implode(' OR ', $list)) === false) {
					KATemplate::displayGeneral('Error', 'An error occured while updating the database.');
					die();
				}
				
				if ($ticket['paid'] == 0) {
					if ($db->queryUpdate('tickets', array('paid' => time()), 'id="' . $ticket['id'] .  '" LIMIT 1') === false) {
						KATemplate::displayGeneral('Error', 'An error occured while updating the database.');
						die();
					}
				}
				
				KATemplate::displayGeneral('Success!', 'The tickets were successfully marked as ' . ($config['t']['mode'] == 1 ? 'collected' : 'checked in') . '!');
			
			}
			
			die();
		
		}
		
		/*
		 * Retrieve information about all tickets related to this one
		 */
		
		$tickets = $db->query($db->selectStatement('tickets','*','WHERE crsid="' . $db->escape($ticket['crsid']) . '" AND waiting=0 ORDER BY primary_ticket DESC, id ASC'));
		
		if ($tickets === false) {
			KATemplate::displayGeneral('Error', 'An error occured accessing the database.');
			die();
		}
		
		$data = array();
		$complete = true;
		$separate = false;
		$committee = false;
		$guest_list = false;
		$paid = 0;
		
		while (($r = $db->fetchResult($tickets)) !== false) {

			if ($r['primary_ticket']) {
				if ($r['paid'] > 0) {
					$paid = 0;
				} else {
					$paid = $r['amount'];
				}
				if ($r['committee'] > 1) {
					$separate = true;
					if ($r['committee'] == 3) {
						$committee = true;
					}
				}
				$guest_list = $r['guest_list'] || ($r['committee'] == 1);
			}
			
			if ($r['committee'] == 3) {
				$entrance = 'Committee';
			} elseif ($r['committee'] == 2) {
				$entrance = 'Shadow Committee';
			} elseif ($committee) {
				$entrance = 'Any Entrance';
			} elseif ($guest_list) {
				$entrance = 'Porter&rsquo;s Lodge Entrance';
			} elseif ($r['premium']) {
				$entrance = 'QueueJump Entrance';
			} else {
				$entrance = 'Normal Entrance';
			}

			$data[] = array(
					'id'		=> $r['id'],
					'hash'		=> $r['hash'],
					'name'		=> $r['fname'] . ' ' . $r['lname'],
					'collected'	=> $r['collected'],
					'committee'	=> $r['committee'],
					'entered'	=> $r['entered'],
					'type'		=> ($r['guest_list'] != 0 ? 'g' : ($r['premium'] ? 'q' : 'n')),
					'gl'		=> $r['guest_list'],
					'primary'	=> $r['primary_ticket'],
					'selected'	=> ($r['id'] == $ticket['id'] ? true : false),
					'entrance'	=> $entrance
					);
			
			if ($config['t']['mode'] == 1) {
				if ($r['collected'] == 0)
					$complete = false;
			} else {
				if ($r['entered'] == 0)
					$complete = false;
			}
			
		}
		
		$template = new KATemplate();
		
		$template->assign('page_name', 'Ticket ' . $hash);
		
		$template->assign('hash', $hash);
		
		$template->assign('paid', $paid);
		
		$template->assign('complete', $complete);		
		$template->assign('separate', $separate);
		
		$template->assign('guest_sections_full', $guest_sections_full);
		$template->assign('guest_sections_m', $guest_sections_m);
		
		$template->assign('tickets', $data);
		
		if ($config['t']['mode'] == 1)
			$template->display('ticket-collection.tpl');
		else
			$template->display('ticket-entry.tpl');
			
	}
	
}
	
?>
