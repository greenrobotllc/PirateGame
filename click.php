<?php


//inbound clicks
//lee lorenzen = 
//gunter = 3
//david happy hour = 4
//cashcliques = 5
include_once '../client/facebook.php';

//require_once("config_click.php");
//require_once("lib.php");
//require_once("adodb/adodb-exceptions.inc.php");
//require_once("adodb/adodb.inc.php");

//$connect= "mysql://CHANGEME:CHANGEME@10.8.50.198/piratewars?persist";
//$DB = NewADOConnection($connect);

//id 1 is pirates


global $DB;

$from_id = $_REQUEST['y'];
$facebook_id = $_REQUEST['z'];
if($facebook_id == NULL) {
	$facebook_id = 0;
}

if($from_id == NULL) {
	$from_id = 0;
}

//if(isset($from_id)) {
//	$DB->Execute("insert into clicks (from_id, facebook_id, created_at) VALUES (?, ?, now())", array($from_id, $facebook_id));
//}

header('Location: http://www.facebook.com/apps/application.php?id=2342084241');

if($from_id == 0) {
  header('Location: CHANGEME');
}


//if from_id is 0, then go to apps.facebook.com/pirates
//piratewarsonline.com/click.php?o=999&z=234234



?>
