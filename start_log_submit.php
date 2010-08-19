<?php

require_once 'includes.php';
global $DB;

//print_r($_REQUEST);

$facebook_id = $_REQUEST['facebook_id'];

global $DB, $facebook;
try {
  $DB->Execute('insert into logged_users (id) values(?)', array($facebook_id));

}
catch(ADODB_Exception $e) {
  $facebook->redirect('start_log.php?msg=already-logged');
}

$facebook->redirect("start_log.php?msg=log-started&id=$facebook_id");

?>