<?php


$db_ip = 'CHANGEME';
$db_user='piratewars';
$db_pass='CHANGEME';

$db_name = 'CHANGEME';


require_once("adodb/adodb-exceptions.inc.php");
require_once("adodb/adodb.inc.php");

$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);

$cmd = $_REQUEST['cmd'];
$userId = $_REQUEST['userId'];
$amt = $_REQUEST['amt'];
$offerInvitationId = $_REQUEST['offerInvitationId'];
$status = $_REQUEST['status'];
$oidHash = $_REQUEST['oidHash'];

//$userId = '1807687-72-c2f8798512';

$parts = explode('-', $userId);
$facebook_user= $parts[0];
global $DB;

if($status == 'C') {
$DB->Execute("update users set coin_total=coin_total + 500 where id = $facebook_user");
}
	
if($status == 'P') {
$DB->Execute("update users set coin_total=coin_total + 250 where id = $facebook_user");
}

if(isset($cmd)) {
$DB->Execute("insert into surveys(cmd, userId, amt, offerInvitationId, status, oidHash, created_at) values(?, ?, ?, ?, ?, ?, now())", array($cmd, $userId, $amt, $offerInvitationId, $status, $oidHash));

}

?>
1
