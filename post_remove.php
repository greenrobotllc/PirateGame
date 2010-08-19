<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';

// this defines some of your basic setup
include_once 'config.php';
global $DB;

$user = $_REQUEST['fb_sig_user'];

//print_r($_REQUEST);
$DB->Execute("update users set inactive =1 where user = $user");

//print_r($_REQUEST['add']);
//$facebook->redirect($_REQUEST['add'] . "&just-bitten=1");
?>