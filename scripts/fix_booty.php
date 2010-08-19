<?php

include_once '../../client/facebook.php';
// some basic library functions
include_once '../lib.php';
// this defines some of your basic setup
include_once '../config.php';
global $DB;
//booty mapping

//a treasure map =1
//some gold bars =2
//a message in a bottle =3 
//some dynamite =4
//a pirate flag =5
//a bomb =6
//some pirate coins =7

$DB->Execute("delete from stuff");

$sql = 'select distinct user_id from booty';
$users = $DB->GetArray($sql);

foreach($users as $key => $value) {
	$user_id = $value['user_id'];
	//print_r($user_id);
	//print "user_id: $user_id\n";
//stuff table
//id, user_id, stuff_id, how_many, updated_at
$sql = 'select user_id, booty_name, count(*) c from booty where user_id=? group by booty_name';

$stuff = $DB->GetArray($sql, $user_id);

//print_r($stuff);


foreach($stuff as $key => $value) {
	$booty_name = $value['booty_name'];
	$how_many = $value['c'];
	$user_id = $value['user_id'];
	
	if($booty_name == 'a treasure map') {
		$stuff_id = 1;
	}
	else if($booty_name == 'some gold bars') {
		$stuff_id = 2;
	}
	else if($booty_name == 'a message in a bottle') {
		$stuff_id = 3;
	}
	else if($booty_name == 'some dynamite') {
		$stuff_id = 4;
	}
	else if($booty_name == 'a pirate flag') {
		$stuff_id = 5;
	}
	else if($booty_name == 'a bomb') {
		$stuff_id = 6;
	}
	//else if($booty_name == 'some pirate coins') {
	//	break;
	//}
	
	if($booty_name != 'some pirate coins') {
		$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values (?, ?, ?, now())';
		$DB->Execute($sql, array($user_id, $stuff_id, $how_many));
	}
}



}



?>