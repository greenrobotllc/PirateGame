<?php

include_once 'script_config_facebook.php';

set_time_limit(0);

///////////////////////////////////////////////////////////////////////
// RESET TIMER ////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "insert into generic_data (unique_identifier, generic_date) values(?, now()) on duplicate key update generic_date = now()";
$DB->Execute($sql, "weekly_refresh_date");

///////////////////////////////////////////////////////////////////////
// AWARD WINNERS //////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "select array_data from leader_weekly_miles where uid = 1";
try {
	$person_array = $DB->GetArray($sql);
} catch (Exception $e) { return false; }
if($person_array != false and count($person_array) != 0) {
	$array_data = unserialize($person_array[0]['array_data']);
	
	$gold = $array_data[0]['id'];  //winner
	$silver = $array_data[1]['id'];  //2nd place
	$bronze = $array_data[2]['id'];  //3rd place
	
	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($gold, "miles_gold"));
	
	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($silver, "miles_silver"));

	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($bronze, "miles_bronze"));
}

$sql = "select array_data from leader_weekly_money where uid = 1";
try {
	$person_array = $DB->GetArray($sql);
} catch (Exception $e) { return false; }
if($person_array != false and count($person_array) != 0) {
	$array_data = unserialize($person_array[0]['array_data']);
	
	$gold = $array_data[0]['id'];  //winner
	$silver = $array_data[1]['id'];  //2nd place
	$bronze = $array_data[2]['id'];  //3rd place
	
	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($gold, "money_gold"));
	
	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($silver, "money_silver"));

	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($bronze, "money_bronze"));
}

$sql = "select array_data from leader_weekly_level where uid = 1";
try {
	$person_array = $DB->GetArray($sql);
} catch (Exception $e) { return false; }
if($person_array != false and count($person_array) != 0) {
	$array_data = unserialize($person_array[0]['array_data']);
	
	$gold = $array_data[0]['id'];  //winner
	$silver = $array_data[1]['id'];  //2nd place
	$bronze = $array_data[2]['id'];  //3rd place
	
	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($gold, "level_gold"));
	
	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($silver, "level_silver"));

	$sql = 'insert into leader_weekly_winners (uid, award_won, date_won) values(?, ?, now())';
	$DB->Execute($sql, array($bronze, "level_bronze"));
}

///////////////////////////////////////////////////////////////////////
// CLEAR MILES ////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "update users set weekly_miles = 0";
$DB->Execute($sql);

echo "Done Clear Miles...\n";

///////////////////////////////////////////////////////////////////////
// COPY OVER MONEY ////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "update users set weekly_money = (buried_coin_total + coin_total)";
$DB->Execute($sql);

echo "Done Copy Money...\n";

///////////////////////////////////////////////////////////////////////
// COPY OVER LEVELS ///////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
$sql = "update users set weekly_level = level";
$DB->Execute($sql);

echo "Done Copy Level...\n";

echo "\nDone Script...\n";

?>