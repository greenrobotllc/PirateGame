<?php

require_once 'includes.php';

$type= get_team($user);


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
$buried_coin_total = get_coin_total_buried($user);

$my_level = get_level($user);

$upgrades = get_upgrades($user);

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


print dashboard();

?>



<?php 

global $network_id;

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


<h2 style="text-align:center">What can I do for ya?</h2>



	<div style="background-image: url(<?php echo $base_url; ?>/images/scroll_background_vertical.jpg); height: 700px; width: <?php echo $image_width; ?>px"> 
	
		<div style="position: relative; top: 100px; left: <?php echo $offset; ?>px; text-align:center">
		
<br>			
	
	<h1 style='font-size:150%; text-align:center'>Purchase Level Upgrade</h1>
	
	<?php $booty = get_booty($user); 
	

	$gold_count =0;
	$message_count=0;
	$flag_count=0;
	$pistol_count=0;
    $sword_count=0;

	?>


<?php foreach($booty as $key=>$value) {
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

		<h1 style="padding-bottom: 10px">You're holding <?php echo $coin_total; ?> coins</h1>

		<?php if ($buried_coin_total != 0) { ?>	
			<h1 style="padding-bottom: 10px">You also have <?php echo $buried_coin_total; ?> buried coins.  <br><a href="retrieve_coins.php">Retrieve em</a> to buy stuff here.</h1>	
			
		<?php } ?>
					
					
					
		<?php if ($my_level == 0) { ?>	
		
		<h1><a <?php href('buy.php', '?u=level'); ?> >Upgrade to a Level 1 Pirate</a> (50 coins)</h1>
		<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px">Arr....You can increase your level by recruitin your mates, or by trading in special items.</p>
	
		<?php } else if(enough_to_level($user, $gold_count, $message_count, $flag_count, $coin_total, $sword_count,$pistol_count)) { ?> 
			<p style="color: #333333; margin-top: 0px; font-size:120%; padding-top: 10px; padding-bottom: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>Purchase a level increase for the price of:
			</p>
			<center>
	
	<?php } else { ?> 
			<h1 style='font-size: 150%; text-align:center; padding:5px'>Not enough booty to level up yet!<br>Arrrrrr.... 
</h1>
	
	<?php } ?>
<?php
$gold_cost = $upgrade_costs[0];
$message_cost = $upgrade_costs[1];
$flag_cost = $upgrade_costs[2];
$level = $my_level;
//$ra2 = rand(2,4);
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
	
    
//$rum_cost = $my_level;

$money_cost = $my_level * $gold_cost;
?>
	<center>
			<table cellpadding="5px">

			<tr><td><h2><?php echo $gold_cost; ?> gold bars</h2></td><td>You Have <?php echo $gold_count; ?> 
			<?php if($gold_cost <= $gold_count): ?>
				<h2 style='color:green'>OK</h2>
			<?php else: ?>
				<h2 style='color:red'>NEED MORE</h2>	
			<?php endif; ?>
			</td></tr>
			<tr><td><h2><?php echo $message_cost; ?> message in a bottle</h2></td><td>You Have <?php echo $message_count; ?>  
			<?php if($message_cost <= $message_count): ?>
				<h2 style='color:green'>OK</h2>
			<?php else: ?>
				<h2 style='color:red'>NEED MORE</h2>	
			<?php endif; ?>

			</td></tr>
			<tr><td><h2><?php echo $flag_cost; ?> pirate flag</h2></td><td>You Have <?php echo $flag_count; ?>  
			<?php if($flag_cost <= $flag_count): ?>
				<h2 style='color:green'>OK</h2>
			<?php else: ?>
				<h2 style='color:red'>NEED MORE</h2>	
			<?php endif; ?>
		
			</td></tr>


<?php

if($my_level > 300) {

?>

			<tr><td><h2><?php echo $sword_cost; ?> swords</h2></td><td>You Have <?php echo $sword_count; ?>  
			<?php if($sword_cost <= $sword_count): ?>
				<h2 style='color:green'>OK</h2>
			<?php else: ?>
				<h2 style='color:red'>NEED MORE</h2>	
			<?php endif; ?>
		
			</td></tr>


			<tr><td><h2><?php echo $pistol_cost; ?> pistols</h2></td><td>You Have <?php echo $pistol_count; ?>  
			<?php if($pistol_cost <= $pistol_count): ?>
				<h2 style='color:green'>OK</h2>
			<?php else: ?>
				<h2 style='color:red'>NEED MORE</h2>	
			<?php endif; ?>
		
			</td></tr>

<?php

}

?>





			<tr><td><h2><?php echo $money_cost; ?> coins</h2></td><td>You Have <?php echo $coin_total; ?> coins  
			<?php if($money_cost <= $coin_total): ?>
				<h2 style='color:green'>OK</h2>
			<?php else: ?>
				<h2 style='color:red'>NEED MORE</h2>	
			<?php endif; ?>
	
			</td></tr>
			
			<?php if(enough_to_level($user, $gold_count, $message_count, $flag_count, $coin_total, $sword_count, $pistol_count)): ?>
			<tr><td colspan=4>

			<h1 style='font-size: 150%; text-align:center; padding-top:10px'><a <?php href('level_up.php'); ?>>Level Up (LEVEL <?php echo $my_level + 1; ?>)! Arrrrrr.... </a></h1>
			</td></tr>
			<?php endif; ?>
			
			</table>

			
			</center>
	
		</div>
	
	</div>

	



<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a <?php href('harbor.php'); ?> >Go to the  harbor</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a <?php href('index.php'); ?> >Go back to sea</a>  -adventure, danger and treasure await</h3>

<?php

require_once 'ad_bottom.inc.php';

require_once 'footer.php'; ?>

</center>

