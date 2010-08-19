<?php


$uid = $_REQUEST['uid'];
$amount = $_REQUEST['amount'];
$time = $_REQUEST['time'];
$oid = $_REQUEST['oid'];
$title = $_REQUEST['title'];
$sig = $_REQUEST['sig'];

$api_key = 'CHANGEME';
$secret  = 'CHANGEME';

$db_ip = 'CHANGEME'; 

$db_user='piratewars';
$db_pass='CHANGEME';

$db_name = 'CHANGEME';

$facebook_canvas_url='CHANGEME';
$base_url = 'CHANGEME';
$site = '998';

require_once("adodb/adodb-exceptions.inc.php");
require_once("adodb/adodb.inc.php");

$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);


$DB->Execute("update users set buried_coin_total=buried_coin_total + ? where id = ?", array($amount, $uid));


$sql = 'insert into offers(user_id, offer_id, site, completed_at, currency) values (?, ?, ?, now(), ?)';
$r = $DB->Execute($sql, array($uid, $oid, $site, $amount));



?>
success
