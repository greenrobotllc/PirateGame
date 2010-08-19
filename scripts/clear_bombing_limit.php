<?php

include_once 'script_config.php';

require_once("../adodb/adodb-exceptions.inc.php");
require_once("../adodb/adodb.inc.php");

$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);


set_time_limit(0);


$sql = 'update bombing_limits set amount = 0';
$DB->Execute($sql);


?>
