<?php

include_once 'script_config.php';

require_once("../adodb/adodb-exceptions.inc.php");
require_once("../adodb/adodb.inc.php");

$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);

require_once '../memcache_wrapper.php';

require_once '../config_network.php';


if($network_id == 1) {
	$memcache = new MemcacheWrapper('mt');
	$memcache->addServer('10.12.198.194', 11213);
	//just flush the temporary stuff

	$memcache_nontemp = new MemcacheWrapper('my');
	$memcache_nontemp->addServer('10.12.198.194', 11211);
}
else {

	$memcache = new Memcache(); //use temp for mile limits, gambing limits
    $memcache->addServer('10.12.198.194', 11219);
    //$memcache->addServer('10.8.50.196', 11211);

    $memcache_nontemp = new Memcache();
    $memcache_nontemp->addServer('10.12.198.194', 11211);

    



}

$memcache_nontemp->set('shipyard_down', 1, 0, 30);

set_time_limit(0);

//todo least() here with damage always positive
$sql = 'update users set damage = greatest(0, damage - 50) where damage > 0';
$DB->Execute($sql);

$memcache->flush();

$memcache_nontemp->set('shipyard_down', 0);
?>
