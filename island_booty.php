<?php
require_once 'includes.php';


redirect_to_index_if_not($user, "island");

$type = get_team($user);

$ra = rand(1,625);

$miles_traveled = get_miles_traveled($user);

$your_coins = get_coin_total($user);


if ($ra < 50) {
	$booby_trap = 1;
	
	$bomb_on = get_bomb_on($user);
	if($bomb_on == 0) {
		$booby_trap = 0;
	}
			
}

if($ra < 33) {
	$booty = "a treasure map";
	$booty_pic = $treasure_map_image;
	$stuff_id = 1;
	$map_count = get_map_count($user);
	if($map_count > rand(5,10)) {
		$rand = rand(1,10);
		if($rand != 2) {
			$booty = "some gold bars";
			$booty_pic = $gold_image;
			$stuff_id = 2;	
		}
	}
	
}

else if($ra < 55) {    	$booty = "some gold bars";
	$booty_pic = $gold_image;
	$stuff_id = 2;

}
else if($ra < 80) {
	$booty = "a message in a bottle";
	$booty_pic = $message_in_a_bottle_image;
	$stuff_id = 3;
}
else if($ra < 105) {
	$booty = "some dynamite";
	$booty_pic = $dynamite_image;
	$stuff_id = 4;

}
else if($ra < 120) {
	$booty = "a pirate flag";
	$booty_pic = $flag_image;
	$stuff_id = 5;

}
else if($ra < 125) {
	$booty = "a monkey";
	$booty_pic = $monkey_200;
	$stuff_id = 7;

}
else if($ra < 158) {
	$booty = "a pistol";
	$booty_pic = $pistol_350;
	$stuff_id = 8;
}
else if($ra < 170) {
	$booty = "a sword";
	$booty_pic = $sword_350;
	$stuff_id = 10;
}
else if($ra < 180) {
	$booty = "a parrot";
	$booty_pic = $parrot_200;
	$stuff_id = 11;
}
else if($ra < 190) {
	$booty = "some rum";
	$booty_pic = $rum_200;
	$stuff_id = 9;
}
else if($ra < 212) {
	$booty = "a bomb";
	$booty_pic = $bomb_image;
	$stuff_id = 6;

} 
else if($ra < 530) {
	//give possibly more coins if you traveled real far
	if($miles_traveled > 10 && $miles_traveled < 100) {
		$ra = rand($miles_traveled, 80);
		
		//rarely give them 5x or 10x bonus on coins
		$ra2 = rand(1,50000);
		if($ra2 == 125) {
			$ra = $ra * 3;
		}
		else if($ra2 == 200) {
			$ra = $ra * 6;
		}
	
	}
	
	else {
		$ra = rand(10, 70);		
	}
	
    $pvp_toggle = $memcache->get($user . 'pvp');
    // echo "PVP TOGGLE $pvp_toggle";
    //print "pvp toggle $pvp_toggle";
    if($pvp_toggle != 'off') {
        // echo 'COOL';
        $ra = $ra + rand(10,50);
        
        if($ra < 70) {
            $ra = rand(70,100);
        }
	
	
	
		//rarely give them 5x or 10x bonus on coins
		$ra2 = rand(1,1000);
		if($ra2 == 125) {
			$ra = $ra * 3;
		}
		else if($ra2 == 200) {
			$ra = $ra * 6;
		}


	
	}
	
	
	$booty = "$ra pirate coins";
	$booty_pic = $pirate_coins_image;
	$found_coins = TRUE;
}
else {
	$booty = "nothing";
}
if($booby_trap) {
	//is there a booby trap for this user
	$dynamite = is_dynamite_set($user);
	//echo 'dynamite: ';
	//echo $dynamite;
	if($dynamite < 1) { //no dynamite for this user
		$ra = rand(3, 100);
		

		$found_coins = true; // no booty or coins found they blew up
		//$booty = "nothing";
		$booty = "$ra pirate coins";
		$booty_pic = $pirate_coins_image;
		$stuff = 'nothing';
	}
	else {
		$user_blown_up = blow_up_user($user);
		$booty = "nothing"; // no booty or coins found they blew up
		$found_coins = false;

		 //assumes there is at least 1 dynamite for this user
		//unsets dynamite set for this user and takes away 20% of their highest item collection and 10% of their next highest
		
	}
	
}



if($found_coins) {
	$sql = 'update users set coin_total = coin_total + ? where id = ?';
	$DB->Execute($sql, array($ra, $user));
	$action = 'island coins';
	log_coins($user, $ra, $action);
	
}
else if($booty != 'nothing' && $stuff != 'nothing') { 
	$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1';
	$DB->Execute($sql, array($user, $stuff_id));
}

?>


<?php

update_action($user, "NULL");
print dashboard();
?>
<center>

<?php

if($booty != 'nothing' && !$user_blown_up) {

success_msg("Ahoy!<br>You found $booty");
?>
<br>

<?php image($booty_pic); ?><br>
<?php 
}
else if($user_blown_up) {

//print "BLOW UP";

print $user_blown_up;

}

else {

?>

<?php

//$last_treasure_mile = $DB->GetOne('select last_treasure_mile from users where id = ?', array($user));


$map_count = get_map_count($user);

$already_quested = $memcache->get($user . 'quested');

$pvp_off = $memcache->get($user . 'pvp');
//echo "pvp off $pvp_off";
$weekly_miles = $DB->GetOne('select weekly_miles from users where id = ?', array($user));

$crew_count = get_crew_count($user);

$sails = get_sails($user);

//echo "sails $sails";
if($sails < 25) {
	$s = false;
}
else if($sails > 150) {
	$s = true;
}
else {
	$t = rand(0,1);
	if($t == 0) {
		$s = true;
	}
	else {
		$s = false;
	}
}

//$sails = 350;
//$weekly_miles = 100;
//$s =true;
//$already_quested = 0;

if($map_count > 0 && $already_quested != 1 && $pvp_off != 'off' && $weekly_miles > 50 && $crew_count > 2 && $s == true) {
//if(true) {
//echo $weekly_miles;
update_action($user, 'island_treasure');
//print_r($map_count);


if($map_count == 1) {
	$map_text = 'map';
}
else {
	$map_text = 'maps';
}
?>

<div class="standard_message has_padding"><h1 class="status">

<span style='font-size:125%; padding:10px;'>Yer crew found a spot on this island matching one of yer maps!!</span>

<table style='background-color:#FFFFFF; padding:10px; margin:10px' cellpadding='10px'><tr>
<td>

<?php image($item_treasuremap_small); ?>

</td>

<td>
<span style='font-size:115%'>
<br>If you go on the quest, your treasure map will be destroyed!
<br><br>Watch out for enemies - if you can't defeat them you won't get the treasure and won't get yer map back!
<br><br>Stock up on ham and rum before going on the quest!<br>
</span>
</td>

</tr></table>

<br>

<span style='font-size:125%'>Do you want to go <a <?php href('treasure_map_action.php'); ?> >on a quest for treasure?</a>
</span>
<br><br>Use treasure maps to find BOOTY (<?php echo $map_count; ?> <?php echo $map_text; ?> left)<br>

<?php

}
else {
?>
     <div class="standard_message has_padding"><h1 class="status">ARRR!<br>You searched around the island but didn't find any booty!
<?php
}

?>

<p>
     
</p></h1></div>





<?php } ?>




<br>
<br><br>



<?php 
/*
<fb:iframe src="http://ec2-67-202-31-235.compute-1.amazonaws.com/promotion.php" width="650" height="60" border="0" frameborder="0" scrolling="no" />
*/

print adsense_468($user);

?>
<br>
<h2 style='padding:20px' ><a <?php href('index.php'); ?>>Arrr..... return to sailin'</a></h2></center>

<?php

set_profile($user);


require_once 'footer.php'; ?>
