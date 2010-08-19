<?php

require_once 'includes.php';

$down = $memcache->get('shipyard_down');
if($down == 1){
	$facebook->redirect('index.php?msg=try-again-buy');
}

$upgrade_name = $_REQUEST['u'];

if($upgrade_name != 'sails' && $upgrade_name != 'cannons' && $upgrade_name != 'hull' && $upgrade_name != 'crew' && $upgrade_name != 'wenches' && $upgrade_name != 'rum' && $upgrade_name != 'ham' && $upgrade_name != '5ham' && $upgrade_name != 'level' && $upgrade_name != 'sextant')
{
    $facebook->redirect('index.php');
}

$type= get_team($user);

$upgrades = get_upgrades($user);

//print_r($upgrades);
//$cannon_level = $upgrades

foreach($upgrades as $key=>$value) {
	$upgrade_name = $value['upgrade_name'];
	$level = $value['level'];
	if($upgrade_name == 'cannons') {
		$cannon_level = $level;
	}
	else if($upgrade_name == 'sails') {
		$sail_level = $level;
	}
	else if($upgrade_name == 'hull') {
		$hull_level = $level;
	}
	else if($upgrade_name == 'crew') {
		$crew_level = $level;
	}
}


if(!isset($crew_level)) {
	$crew_level = 0;
}

if(!isset($hull_level)) {
	$hull_level = 0;
}
if(!isset($cannon_level)) {
	$cannon_level = 0;
}
if(!isset($sail_level)) {
	$sail_level = 0;
}

$crew_price = 400;
for($i = 0; $i < $crew_level; $i++) {
	$crew_price += $crew_price * .05;
}
$crew_price = round($crew_price);
if($crew_price > 10000) {
	$crew_price = 10000;
}

$hull_price = 300;
for($i = 0; $i < $hull_level; $i++) {
	$hull_price += $hull_price * .05;
}
$hull_price = round($hull_price);
if($hull_price > 10000) {
	$hull_price = 10000;
}

$cannon_price = 100;
for($i = 0; $i < $cannon_level; $i++) {
	$cannon_price += $cannon_price * .05;
}
$cannon_price = round($cannon_price);
if($cannon_price > 10000) {
	$cannon_price = 10000;
}

$sail_price = 200;
for($i = 0; $i < $sail_level; $i++) {
	$sail_price += $sail_price * .05;
}
$sail_price = round($sail_price);
if($sailprice > 10000) {
	$sailprice = 10000;
}

$wench_price = 25;
for($i = 0; $i < $crew_level; $i++) {
	$wench_price += $wench_price * .05;
}
$wench_price = round($wench_price);
if($wench_price > 1000) {
	$wench_price = 1000;
}

//$crew_price = ($crew_level + 1)  * 400;
//$hull_price = ($hull_level + 1)  * 300;
//$cannon_price = ($cannon_level + 1)  * 100;
//$sail_price = ($sail_level + 1)  * 200;

$upgrade_name = $_REQUEST['u'];

if($upgrade_name == 'cannons') {
	$cost = $cannon_price;
}
if($upgrade_name == 'level') {
	$cost = 50;
}
if($upgrade_name == 'sails') {
	$cost = $sail_price;
}
if($upgrade_name == 'hull') {
	$cost = $hull_price;
}
if($upgrade_name == 'crew') {
	$cost = $crew_price;
}
if($upgrade_name == 'wenches') {
	$cost = $wench_price;
}
if($upgrade_name == 'rum') {
	$cost = 50;
}
if($upgrade_name == 'ham') {
	$cost = 50;
}
if($upgrade_name == '5ham') {
	$cost = 200;
}
if($upgrade_name == 'sextant') {
	$cost = 150;
}

$coins = get_coin_total($user);

$ship_upgrade = false;
if($coins < $cost) {

	$buried_coins = get_coin_total_buried($user);
	if($buried_coins > 0) {
		
		$msg = error_msg_return("Sorry, you don't have enough coins to purchase that yet. <a " . href_return('retrieve_coins.php') . "'>Dig up coins</a>!");
	
	}
	else {
		error_msg_return("Sorry, you don't have enough coins to purchase that yet.<br>Go find more booty!!");
	
	}
	
}
else if($upgrade_name == "level") {
	$ship_upgrade = true;
	$level = get_level($user);
	//echo "level is: $level";
	
	if($level !=0) { 
		//fix for people trying to hack this and typing in buy.php?u=level directly, 
		//then going down in level.  LOL
		$facebook->redirect("index.php");
	}
	
	$sql = 'update users set level = 1 where id = ?';
	$DB->Execute($sql, array($user));
	
	update_coins($user, -$cost);
	log_coins($user, -$cost, 'bought level 1');
	log_levels($user, 'bought level 1');

	$coin_left = $coins - $cost;

	$msg = "<h1>Avast!  You purchased a level 1 Pirate upgrade for $cost coins! You have $coin_left coins left.</h1><h3 style='padding-bottom:10px'><a " . href_return('shipyard.php') . "'>Return to store</a></h3>";


}
else if($upgrade_name == "wenches" or $upgrade_name == "rum" or $upgrade_name == "ham"  or $upgrade_name == "5ham" or $upgrade_name == "sextant") {
	//update_coins($user, -$cost);
	//$coin_left = $coins - $cost;
	$msg = "<h1>Avast!  You purchased $upgrade_name for $cost coins! You have $coin_left coins left.";
	if($upgrade_name == "wenches") {
	
		
		$action = get_current_action($user);
        $no_captcha = get_no_captcha();
	    $pass = false;
	    if (in_array($user, $no_captcha )) {
	      $pass = true;
        }

		if($action != "captcha_complete" && $pass == false) {
			update_action($user, "captcha");
			$s = urlencode("buy.php?u=wenches");
			$facebook->redirect("$facebook_canvas_url/captcha_page.php?page=$s");
		}
		else {  //this means they've completed the captcha
			update_action($user, "NULL");
		}
		log_coins($user, -$cost, 'bought wenches');
		update_coins($user, -$cost);
		$coin_left = $coins - $cost;

		set_damage($user, 0); //heal completely
		set_miles_traveled($user, 0);
		$msg .= "<h3>Your crew is now rejuvenated and ready to hit the high seas.</h3><br>";


	}
	else if($upgrade_name == "rum") {

		log_coins($user, -$cost, 'bought rum');
		update_coins($user, -$cost);
		$coin_left = $coins - $cost;
	
		$stuff_id = 9; //rum
		$msg = "<h1>Avast!  You purchased some Rum for $cost coins! You have $coin_left coins left.</h1><br>";
	   $msg = $msg . "<br><h1><a " . href_return('item_action.php?item=rum') . "'>Drink Rum</a> or <a " . href_return('buy.php?u=rum') . "'>buy some more</a> rum.</h1></br>";

		$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1';
		$DB->Execute($sql, array($user, $stuff_id));
	}
	else if($upgrade_name == "ham") {
		log_coins($user, -$cost, 'bought ham');
		update_coins($user, -$cost);
		$coin_left = $coins - $cost;
	
		$stuff_id = 12; //ham
		$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1';
		$DB->Execute($sql, array($user, $stuff_id));
		$msg = "<h1>Avast!  You purchased Salted Ham for $cost coins! You have $coin_left coins left.</h1><br>";
	   $msg = $msg . "<br><h1><a href='item_action.php?item=ham'>Eat this ham</a> or <a href='buy.php?u=ham'>buy some more</a> ham.</br></br><a href='buy.php?u=5ham'>Buy 5 ham for 200 coins!</a></h1></br>";
	}
	else if($upgrade_name == "5ham") {
		log_coins($user, -$cost, 'bought 5 ham');
		update_coins($user, -$cost);
		$coin_left = $coins - $cost;
	
		$stuff_id = 12; //ham
		$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 5';
		$DB->Execute($sql, array($user, $stuff_id));
		$msg = "<h1>Avast!  You purchased 5 Salted Hams for $cost coins! You have $coin_left coins left.</h1><br>";
	   $msg = $msg . "<br><h1><a href='item_action.php?item=ham'>Eat this ham</a> or <a href='buy.php?u=ham'>buy some more</a> ham.</br></br><a href='buy.php?u=5ham'>Buy 5 ham for 200 coins!</a></h1></br>";
	}		
	else if($upgrade_name == "sextant") {
		log_coins($user, -$cost, 'bought sextant');
		update_coins($user, -$cost);
		$coin_left = $coins - $cost;

		$stuff_id = 13; //ham
		$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1';
		$DB->Execute($sql, array($user, $stuff_id));
		$msg = "<h1>Avast!  You purchased a Sextant for $cost coins! You have $coin_left coins left.</h1><br>";
	}	

}
else {
	
	

	$ship_upgrade = true;
	buy_upgrade_transaction($user, $upgrade_name, $cost);
	
	//update_coins($user, -$cost);
	//$DB->Execute('update bla set bla = bla');
	//$DB->FailTrans();
	//$DB->CompleteTrans();

	$coin_left = $coins - $cost;
	
	$action = "bought upgrade $upgrade_name";
	log_coins($user, -$cost, $action);
	
	$msg = "<h1>Avast!  You purchased $upgrade_name for $cost coins! You have $coin_left coins left.</h1><h3 style='padding-bottom:10px'><a " . href_return('shipyard.php') . "'>Return to store</a></h3>";

}

print dashboard();

?>

<?php if($ship_upgrade == true) { ?>
	<center>
	<h1 style="padding-bottom:10px">ARRRR you ready for your ship upgrade?</h1>
	</center>
<?php } ?>

<center>

<?php echo $msg; ?>

<?php image($big_flag_image); ?>

<br><br>


<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a <?php href('harbor.php'); ?> >Go to the  harbor</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a <?php href('index.php'); ?> ">Go back to sea</a>  -adventure, danger and treasure await</h3>

</center>

<?php

//require_once "my_pirate.inc.php";

//require_once "world_stats.inc.php";
//set_profile($user);





?>

<?php

require_once 'ad_bottom.inc.php';


 require_once 'footer.php'; ?>
