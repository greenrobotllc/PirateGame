<?php

require_once 'includes.php';
global $DB;


if(get_level($user) > 3000) {

$level_upgraded = $memcache->get($user . 'upgraded_level');

if($level_upgraded == 1) {
    $facebook->redirect('pirate_market.php?msg=wait-to-upgrade');
}

}

if($_REQUEST['sent'] == "1") {
	$msg = "Yer Pirate invitations have been sent!";
}


if($_REQUEST['msg'] == "send-limit") {
	$msg = "Your requests were not sent.  You're over the limit for today.  Try again later. :(";
}

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$coin_total = get_coin_total($user);
if($coin_total < 0) {
	$facebook->redirect('level_upgrade.php');
}

$buried_coin_total = get_coin_total_buried($user);

$my_level = get_level($user);

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


$upgrade_costs = get_upgrade_costs($user);

//print "upgrade cost";
//print_r($upgrade_costs);

print dashboard();

?>


<?php if($msg):?>
	<?php success_msg($msg); ?>
<?php endif; ?>	

<?php

if($network_id == 1) {
	$image_width = "650";
	$offset = "0";
}
else if($networkd_id == 0) {
	$image_width = "600";
	$offset = "20";
}
?>

<center>
<h1>Welcome to the <?php echo ucwords($type); ?> Market!</h1>


<h2 style="text-align:center">Arrrrr.....</h2>



	<div style="background-image: url(<?php echo $base_url; ?>/images/scroll_background_vertical.jpg); height: 700px; width: <?php echo $image_width; ?>px;"> 
	
		<div style="position: relative; top: 100px; left: <?php echo $offset; ?>px; text-align:center">
		
<br>			
	
	<h1 style='font-size:150%; text-align:center'>Purchase Level Upgrade</h1>
	
	<?php $booty = get_booty($user); 
	
	?>


<?php 

	$gold_count =0;
	$message_count=0;
	$flag_count=0;
	$pistol_count=0;
    $sword_count=0;


foreach($booty as $key=>$value) {
	//echo "key: $key value:";
	//print_r($value);
	//$id = $value['id'];
	$stuff_id = $value['stuff_id'];
	
	if($stuff_id == 1) {
		$booty_name = 'a treasure map';
		$map_count = $value['how_many'];
	}
	else if($stuff_id == 2) {
		$booty_name = 'some gold bars';
		$gold_count = $value['how_many'];
	}
	else if($stuff_id == 3) {
		$booty_name = 'a message in a bottle';
		$message_count = $value['how_many'];
	}
	else if($stuff_id == 4) {
		$booty_name = 'some dynamite';
		$dynamite_count = $value['how_many'];
	}
	else if($stuff_id == 5) {
		$booty_name = 'a pirate flag';
		$flag_count = $value['how_many'];
	}
	else if($stuff_id == 6) {
		$booty_name = 'a bomb';
		$bomb_count =$value['how_many'];
	}
	else if($stuff_id == 10) {
		$sword_name = 'sword';
		$sword_count =$value['how_many'];
	}
	else if($stuff_id == 8) {
		$pistol_name = 'pistol';
		$pistol_count =$value['how_many'];
	}
				
	
	//$booty_name = $value['booty_name'];
	
	$count = $value['how_many'];
	//echo "<p style='text-align:center; font-size:120%'>$booty_name (x $count)</p>";

}

function enough_to_level($user, $gold_count, $message_count, $flag_count, $coin_count, $sword_count, $pistol_count) {
	$upgrade_costs = get_upgrade_costs($user);
	$gold_cost = $upgrade_costs[0];
	$message_cost = $upgrade_costs[1];
	$flag_cost = $upgrade_costs[2];
	$level = get_level($user);
	//print_r($level);
	$coin_cost = $coin_cost = get_level($user) * $gold_cost;
	if($level > 5000) {
		$coin_cost = get_level($user) * $gold_cost * 1000;
	}

    if($level > 5000) {
        $sword_cost = $upgrade_costs[0] * $upgrade_costs[1];
        $pistol_cost = $upgrade_costs[1] * $upgrade_costs[1];
    }
    else if($level > 1000) {
        $sword_cost = $upgrade_costs[0];
        $pistol_cost = $upgrade_costs[1] * $upgrade_costs[2];
    }
    else if($level > 500) {
        $sword_cost = $upgrade_costs[0];
        $pistol_cost = $upgrade_costs[1];
    } 
    else if($level > 300) {
        $sword_cost = 1;
        $pistol_cost = 1;
    }        
    else {
        $sword_cost = 0;
        $pistol_cost = 0;
    }
	
	
        //echo $gold_cost <= $gold_count;
	//echo $message_cost <= $message_count;
	//echo $flag_cost <= $flag_count;
	//echo $coin_cost <= $coin_count;
	
	if($gold_cost <= $gold_count && $message_cost <= $message_count && $flag_cost <= $flag_count && $coin_cost <= $coin_count  && $sword_cost <= $sword_count  && $pistol_cost <= $pistol_count) {
		return 1;
	}
	else {
		return 0;
	}
}

 ?>
 	<br>
	<?php
$gold_cost = $upgrade_costs[0];
$message_cost = $upgrade_costs[1];
$flag_cost = $upgrade_costs[2];
$coin_cost = $my_level * $gold_cost;

$gold_id = 2;
$message_id = 3;
$flag_id =  5;

$sword_id = 10;
$pistol_id =  8;

$level = $my_level;
    if($level > 5000) {
        $sword_cost = $upgrade_costs[0] * $upgrade_costs[1];
        $pistol_cost = $upgrade_costs[1] * $upgrade_costs[1];
    }
    else if($level > 1000) {
        $sword_cost = $upgrade_costs[0];
        $pistol_cost = $upgrade_costs[1] * $upgrade_costs[2];
    }
    else if($level > 500) {
        $sword_cost = $upgrade_costs[0];
        $pistol_cost = $upgrade_costs[1];
    } 
    else if($level > 300) {
        $sword_cost = 1;
        $pistol_cost = 1;
    }        
    else {
        $sword_cost = 0;
        $pistol_cost = 0;
    }
	
 
 

if(enough_to_level($user, $gold_count, $message_count, $flag_count, $coin_total, $sword_count, $pistol_count)): ?> 
			<h2 style="margin-top: 0px; padding-top: 10px; padding-bottom: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>You purchased a level upgrade!  You're now a level 
			
<?php 
//update flag count
$sql = "update stuff set how_many = how_many - ? where user_id = ? and stuff_id = ?";
$DB->Execute($sql, array($flag_cost, $user, $flag_id));

//update message count
$sql = "update stuff set how_many = how_many - ? where user_id = ? and stuff_id = ?";
$DB->Execute($sql, array($message_cost, $user, $message_id));

//update gold count
$sql = "update stuff set how_many = how_many - ? where user_id = ? and stuff_id = ?";
$DB->Execute($sql, array($gold_cost, $user, $gold_id));


//update sword count
$sql = "update stuff set how_many = how_many - ? where user_id = ? and stuff_id = ?";
$DB->Execute($sql, array($sword_cost, $user, $sword_id));

//update pistol count
$sql = "update stuff set how_many = how_many - ? where user_id = ? and stuff_id = ?";
$DB->Execute($sql, array($pistol_cost, $user, $pistol_id));

//update coin count
$sql = "update users set coin_total = coin_total - ? where id = ?";
$DB->Execute($sql, array($coin_cost, $user));

log_levels($user, 'bought level');
log_coins($user, -$coin_cost, 'bought level');


set_level($user, get_level($user) + 1);

$new_level = get_level($user);

if($new_level > 3000) {
  global $memcache;
  $memcache->set($user . 'upgraded_level', 1, false, 60 * 10); //ten minutes
  }

//decrement message count, flag count, coin total


echo get_level($user); ?> Pirate</h2>
<?php else: 
	//$facebook->redirect("index.php");
endif; ?>



		<div style='position: relative; left: 10px; padding:10px; margin:10px;'>
		<?php print adsense_300_250($user); ?>
		</div>


<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a href="harbor.php">Go to the  harbor</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a href="index.php">Go back to sea</a><br>  -adventure, danger and treasure await</h3>

<?php


?>
<br>
<br>
<div style='clear:both'>

<?php 

require_once 'footer.php'; 

?>
</center>
