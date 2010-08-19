<?php

require_once 'includes.php';
global $DB;

$type=$_REQUEST['type'];


//check if they have a monkey or a parrot
if($type== 'm') {
	$animal_count = get_monkey_count($user);
	$price = 100;
	$item_id=7;
}
else {
	$animal_count = get_parrot_count($user);
	$price = 250;
	$item_id=11;
}

if($animal_count < 1) {
	$facebook->redirect("races.php?msg=not-enough-$type&item=$item_id");

}

//check if they got the money
if(get_coin_total($user) < $price) {
	$facebook->redirect("races.php?msg=not-enough-money&item=$item_id");
}

//check if they already entered, if not redirect back to races.php
if(!user_can_enter_race($user, $type)) {
	$facebook->redirect("races.php?msg=already-entered-$type&item=$item_id");

}


//deduct racing fee
set_coins($user, get_coin_total($user) - $price);
log_coins($user, -$price, 'bought race entry');



//insert the race entry
$sql = 'insert into races (user_id, type, completed, created_at) values(?, ?, 0, now())';
$DB->Execute($sql, array($user, $type));

//print_r($_REQUEST);

$facebook->redirect("races.php?msg=$type-entered-in-race&item=$item_id");

//print $friend_selector_id;





 ?>