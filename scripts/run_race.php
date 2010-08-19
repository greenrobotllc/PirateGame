<?php

include_once 'script_config.php';

require_once("../adodb/adodb-exceptions.inc.php");
require_once("../adodb/adodb.inc.php");

$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);

set_time_limit(0);


//run the monkey race
//how many people are in monkey race?
$sql = "select count(*) from races where type = 'm' and completed = 0";
$monkey_entries = $DB->GetOne($sql);

$jackpot = $monkey_entries * 50;
if($jackpot < 2500) {
	$jackpot =2500;
}
//select a random user id to win
$sql = "select user_id from races where type = 'm' and completed = 0 order by rand()";
$monkey_winner = $DB->GetOne($sql);

//update all the completed to 1;
$DB->Execute("update races set race_time=now(), completed = 1 where completed = 0 and type='m'");

//insert user_id, type, jackpot, date into race_results
$DB->Execute("insert into race_results (user_id, type, jackpot, created_at, entrants) values(?, 'm', ?, now(), ?)", array($monkey_winner,$jackpot, $monkey_entries));


//add the jackpot coins for the monkey_winner
$DB->Execute("update users set buried_coin_total=buried_coin_total + ? where id = ?", array($jackpot, $monkey_winner));
//subtract the monkey from the winner
$DB->Execute("update stuff set how_many = how_many - 1 where stuff_id = 7 and user_id = ?", array($monkey_winner));





//run the parro race
//how many people are in monkey race?
$sql = "select count(*) from races where type = 'p' and completed = 0";
$parrot_entries = $DB->GetOne($sql);

$jackpot = $parrot_entries * 200;
if($jackpot < 5000) {
	$jackpot =5000;
}
//select a random user id to win
$sql = "select user_id from races where type = 'p' and completed = 0 order by rand()";
$parrot_winner = $DB->GetOne($sql);

//update all the completed to 1;
$DB->Execute("update races set race_time=now(), completed = 1 where completed = 0 and type = 'p'");

//insert user_id, type, jackpot, date into race_results
$DB->Execute("insert into race_results (user_id, type, jackpot, created_at, entrants) values(?, 'p', ?, now(), ?)", array($parrot_winner,$jackpot, $monkey_entries));


//add the jackpot coins for the parrot_winner
$DB->Execute("update users set buried_coin_total=buried_coin_total + ? where id = ?", array($jackpot, $parrot_winner));

$DB->Execute("update stuff set how_many = how_many - 1 where stuff_id = 11 and user_id = ?", array($parrot_winner));

//subtract the parrot from the winner


?>
