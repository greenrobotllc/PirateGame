<?php

include_once 'script_config_facebook.php';

set_time_limit(0);

include_once '../config_network.php';
global $network_id;
if($network_id == 1) {
  $num_per_chunk = 10;
}
else {
  $num_per_chunk = 50;
}

///////////////////////////////////////////////////////////////////////
// OVERALL ////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "select id from users where id not in(select id from banned) ORDER BY level * buried_coin_total DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql);

for($ii = 0; $ii < count($person_array); $ii++) {
	//echo "i: $ii";
	$user_id = $person_array[$ii][0];
	$y = $ii + 1;
	$sql = "insert into user_ranks (user_id, overall, updated_at) values($user_id, $y, now()) on duplicate key update updated_at = now(), overall = $y";
	$DB->Execute($sql);
} 

//print_r($person_array);
//exit();

$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_overall";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_overall (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":ldrovrall", "");
    	
 /*  		for($j = 0; $j < count($array_chunks[$y]); $j++) {
    		$current_user = $array_chunks[$y][$j + ($y * 100)]['id'];
			$memcache->set($current_user . ":" . $outlaws_memcache . ":ldrnotpos", "$y");
    	}*/
    }
}

echo "Done Overall...\n";

///////////////////////////////////////////////////////////////////////
// MOST COINS /////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) ORDER BY buried_coin_total DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql);

for($ii = 0; $ii < count($person_array); $ii++) {
	//echo "i: $ii";
	$user_id = $person_array[$ii][0];
	$y = $ii + 1;
	$sql = "insert into user_ranks (user_id, most_coins, updated_at) values($user_id, $y, now()) on duplicate key update updated_at = now(), most_coins = $y";
	$DB->Execute($sql);
} 

$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_most_coins";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_most_coins (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":ldrmstcoins", "");
    }
}

echo "Done Most Coins...\n";

///////////////////////////////////////////////////////////////////////
// HIGHEST LEVEL //////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) ORDER BY level DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql);

for($ii = 0; $ii < count($person_array); $ii++) {
	//echo "i: $ii";
	$user_id = $person_array[$ii][0];
	$y = $ii + 1;
	$sql = "insert into user_ranks (user_id, highest_level, updated_at) values($user_id, $y, now()) on duplicate key update updated_at = now(), highest_level = $y";
	$DB->Execute($sql);
} 

$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_highest_level";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_highest_level (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":ldrhghlvl", "");
    }
}

echo "Done Highest Level...\n";



/*****************************************************************************************/
// TEAM SPECIFIC LEADERBOARDS //

$num_teams = 3;
for($i = 0; $i < $num_teams; $i++) { 

	if($i == 0) {
		$team_name = "buccaneer";
		//memcaches
		$overall_memcache = "ldrovrall_buc";
		$mostcoins_memcache = "ldrmstcoins_buc";
		$highestlevel_memcache = "ldrhghlvl_buc";
	} else if($i == 1) {
		$team_name = "corsair";
		//memcaches
		$overall_memcache = "ldrovrall_cor";
		$mostcoins_memcache = "ldrmstcoins_cor";
		$highestlevel_memcache = "ldrhghlvl_cor";
	} else if($i == 2) {
		$team_name = "barbary";
		//memcaches
		$overall_memcache = "ldrovrall_bar";
		$mostcoins_memcache = "ldrmstcoins_bar";
		$highestlevel_memcache = "ldrhghlvl_bar";
	}
	
///////////////////////////////////////////////////////////////////////
// OVERALL ////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) and team = ? ORDER BY (level * buried_coin_total) DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql, array($team_name));

for($ii = 0; $ii < count($person_array); $ii++) {
	//echo "i: $ii";
	$user_id = $person_array[$ii][0];
	$y = $ii + 1;
	$sql = "insert into user_ranks (user_id, overall_team, updated_at) values($user_id, $y, now()) on duplicate key update updated_at = now(), overall_team = $y";
	$DB->Execute($sql);
} 


$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff

$sql = "TRUNCATE TABLE leader_" . $team_name . "_overall";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_" . $team_name . "_overall (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":$overall_memcache", "");
    }
}

echo "Done $team_name Overall...\n";

///////////////////////////////////////////////////////////////////////
// MOST COINS /////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) and team = ? ORDER BY buried_coin_total DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql, array($team_name));

for($ii = 0; $ii < count($person_array); $ii++) {
	//echo "i: $ii";
	$user_id = $person_array[$ii][0];
	$y = $ii + 1;
	$sql = "insert into user_ranks (user_id, most_coins_team, updated_at) values($user_id, $y, now()) on duplicate key update updated_at = now(), most_coins_team = $y";
	$DB->Execute($sql);
} 


$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_" . $team_name . "_most_coins";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_" . $team_name . "_most_coins (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":$mostcoins_memcache", "");
    }
}

echo "Done $team_name Most Coins...\n";

///////////////////////////////////////////////////////////////////////
// HIGHEST LEVEL //////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) and team = ? ORDER BY level DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql, array($team_name));

for($ii = 0; $ii < count($person_array); $ii++) {
	//echo "i: $ii";
	$user_id = $person_array[$ii][0];
	$y = $ii + 1;
	$sql = "insert into user_ranks (user_id, highest_level_team, updated_at) values($user_id, $y, now()) on duplicate key update updated_at = now(), highest_level_team = $y";
	$DB->Execute($sql);
} 


$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_" . $team_name . "_highest_level";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_" . $team_name . "_highest_level (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":$highestlevel_memcache", "");
    }
}

echo "Done $team_name Highest Level...\n";

}

// END TEAM SPECIFIC LEADERBOARDS
/*****************************************************************************************/

/*****************************************************************************************/
// WEEKLY LEADERBOARDS //

///////////////////////////////////////////////////////////////////////
// OVERALL MILES //////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) ORDER BY weekly_miles DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql);

$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_weekly_miles";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_weekly_miles (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":ldrweeklymiles", "");
    }
}

echo "Done Weekly Miles...\n";

///////////////////////////////////////////////////////////////////////
// WEEKLY MONEY ///////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) ORDER BY ((buried_coin_total + coin_total) - weekly_money) DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql);

$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_weekly_money";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_weekly_money (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":ldrweeklymoney", "");
    }
}

echo "Done Weekly Money...\n";

///////////////////////////////////////////////////////////////////////
// WEEKLY LEVEL ///////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "SELECT id FROM users where id not in(select id from banned) ORDER BY ((level) - weekly_level) DESC LIMIT 0,9999";
$person_array = $DB->GetArray($sql);

$array_chunks = array_chunk($person_array, $num_per_chunk, true);

//before we start inserting stuff, lets empty the db table of old stuff
$sql = "TRUNCATE TABLE leader_weekly_level";
$DB->Execute($sql);

for($y = 0; $y < count($array_chunks); $y++) {
	$sql = "insert into leader_weekly_level (array_data) values (?)";
	$DB->Execute($sql, array(serialize($array_chunks[$y])));
	
	if($memcache) {
    	$memcache->set($y . ":" . $outlaws_memcache . ":ldrweeklylevel", "");
    }
}

echo "Done Weekly Level...\n";

// END WEEKLY LEADERBOARDS
/*****************************************************************************************/

echo "\nDone Script...\n";
?>
