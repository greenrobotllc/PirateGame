<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;

$crew = $DB->GetOne('select level from upgrades where user_id = ? and upgrade_name = ?', array($user, 'crew'));

if($crew > 5) {
	$facebook->redirect('index.php');
}

$ids = $_REQUEST['ids'];
$sizeids = count($ids);

//echo "idsize $sizeids";

if($sizeids  > 0) {
	$DB->Execute('update users set pillage_invites = pillage_invites + ? where id = ?', array($sizeids, $user));
}
//print_r();
$facebook->redirect('cant_pillage_yet.php');
?>