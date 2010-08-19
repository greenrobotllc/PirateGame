<?php

include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';
global $DB;

//print_r($_REQUEST);

$facebook_id = $_REQUEST['id'];

if(!in_array($user, get_moderators())) {
	$facebook->redirect("logged_users.php?msg=not-authorized");
}

global $DB, $facebook;
try {
  $DB->Execute('delete from logged_users where id = ?', array($facebook_id));

}
catch(ADODB_Exception $e) {
	//$facebook->redirect('start_log.php?msg=already-logged');
}

$facebook->redirect("logged_users.php?msg=log-removed&id=$facebook_id");

?>
