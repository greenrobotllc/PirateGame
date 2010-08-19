<?
//decrease all users damage taken by 1 each hour

include_once '../lib.php';
include_once '../config.php';
set_time_limit(0);
ini_set("memory_limit","100M"); 


global $DB;

//every hour
$sql = 'update users set user_in_battle = 0 ';
$DB->Execute($sql);


$sql = 'update users set coin_total = 0 where coin_total < 0';
$DB->Execute($sql);

$sql = "delete from battles where result = 'p'";
$DB->Execute($sql);

//every 24 hours
//--clear the distance from home values in memcache for all users
//--delete all battles older then 24 hours

?>