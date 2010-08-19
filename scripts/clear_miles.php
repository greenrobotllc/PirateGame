<?php

//include_once '../lib.php';
include_once 'script_config.php';

require_once("../adodb/adodb-exceptions.inc.php");
require_once("../adodb/adodb.inc.php");

$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);

//set_time_limit(0);
//ini_set("memory_limit","100M"); 

//$userList = get_user_list();

//$count = count($userList);

//for($i = 0; $i < $count; $i++) {
	//echo $userList[$i][0];
//	set_miles_traveled($userList[$i][0], 0);
//}

//global $memcache;
//global $DB;


$sql = 'update users set damage = damage - 10 where damage > 0';
$DB->Execute($sql);

$sql = 'update users set damage = 0 where damage < 0';
$DB->Execute($sql);

memcache_flush($memcache);


?>
