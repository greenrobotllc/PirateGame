<?php
require_once 'includes.php';
global $DB;

redirect_to_index_if_not($user, "treasure_booty");

$treasure_map_count = get_map_count($user);

$type = get_team($user);

$ra = rand(1,10);

$miles_traveled = get_miles_traveled($user);

$your_coins = get_coin_total($user);

$memcache->set($user . 'quested', 1, false, rand(60*20, 60*60));

$amount = rand(3,5);

if($ra < 2) {
	$booty = "$amount gold bars";
	$booty_pic = $gold_image;
	$stuff_id = 2;
}
else if($ra < 3) {
	$booty = "$amount messages in a bottle";
	$booty_pic = $message_in_a_bottle_image;
	$stuff_id = 3;
}
else if($ra < 4) {
	$booty = "$amount pieces of dynamite";
	$booty_pic = $dynamite_image;
	$stuff_id = 4;

}
else if($ra < 5) {
	$booty = "$amount pirate flags";
	$booty_pic = $flag_image;
	$stuff_id = 5;

}
else if($ra < 6) {
	$booty = "$amount monkeys";
	$booty_pic = $monkey_350;
	$stuff_id = 7;

}
else if($ra < 7) {
	$booty = "$amount pistols";
	$booty_pic = $pistol_350;
	$stuff_id = 8;
}
else if($ra < 8) {
	$booty = "$amount swords";
	$booty_pic = $sword_350;
	$stuff_id = 10;
}
else if($ra < 9) {
	$booty = "$amount parrots";
	$booty_pic = $item_parrot_large;
	$stuff_id = 11;
}
else if($ra < 10) {
	$booty = "$amount jugs of rum";
	$booty_pic = $rum_350;
	$stuff_id = 9;
}
else if($ra < 11) {
	$booty = "a bomb";
	$booty_pic = $bomb_image;
	$stuff_id = 6;

} 

    $ra = rand(400, 700);
    $ra2 = rand(1,50);
    if($ra2 == 37) {
    	$r = rand(5,15);
    	$ra = $ra * $r;
    }

	$sql = 'update users set coin_total = coin_total + ? where id = ?';
	$DB->Execute($sql, array($ra, $user));
	$action = 'treasure coins';
	log_coins($user, $ra, $action);
	
	
	$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + ?';
	$DB->Execute($sql, array($user, $stuff_id, $amount));


?>

<center>

<?php

update_action($user, "NULL");

$DB->Execute('insert into treasure_booty (user_id, coins, booty_id, booty_amount, created_at) values(?, ?, ?, ?, now())', array($user, $ra, $stuff_id, $amount));

print dashboard();

if($booty != 'nothing' && !$user_blown_up) {
  //echo $gold_small_image;
  //echo $image_uid;

success_msg("Ahoy!! You found  $booty and $ra pirate coins in buried treasure!");

image($booty_pic);

}

?>



<br>


<?php 

print adsense_468($user);

?>

<h2 style='padding:20px' ><a href="index.php">return to sailin'</a></h2></center>

<?php

set_profile($user);


require_once 'footer.php'; ?>
