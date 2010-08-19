
<?php
require_once 'includes.php';


$type= get_team($user);


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
$buried_coin_total = get_coin_total_buried($user);

$level = get_level($user);

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
	$$crew_price = 10000;
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


//$crew_price = ($crew_level + 1)  * 400;
//$hull_price = ($hull_level + 1)  * 300;
//$cannon_price = ($cannon_level + 1)  * 100;
//$sail_price = ($sail_level + 1)  * 200;

print dashboard();


?>

<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	


<center>



<h1>Welcome to the <?php echo ucwords($type); ?> Shipyard!</h1>


<h2 style="text-align:center">What can I do for ya?</h2>

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

	<div style="background-image: url(<?php echo $base_url; ?>/images/scroll_background_vertical.jpg); height: 700px; width: <?php echo $image_width; ?>px;"> 


	<?php
	//20
	 ?>
	
			<div style="position: relative; top: 100px; left: <?php echo $offset; ?>px; text-align:center">


				
			<h1 style="padding-bottom: 10px">You're holding <?php echo $coin_total; ?> coins</h1>
			
		<?php if ($buried_coin_total != 0): ?>	
			<h1 style="padding-bottom: 10px">You also have <?php echo $buried_coin_total; ?> buried coins.  <br><a <?php href('retrieve_coins.php'); ?>">Retrieve em</a> to buy stuff here.</h1>	
		<?php endif; ?>
					
		<?php if ($level == 0): ?>	
			<h2><a <?php href('buy.php', '?u=level'); ?>">Upgrade to a Level 1 Pirate</a> (50 coins)</h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px">Arr....You can increase your level by recruitin your mates, or by spendin' some cash here.  Once you're a level 1 pirate you'll be able to fight other pirates</p>
		<?php endif; ?>
		
			<h2><a <?php href('buy.php', '?u=cannons'); ?>">Upgrade your Cannons to level <?php echo $cannon_level + 1; ?></a> (<?php echo $cannon_price; ?> coins)</h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>Your current cannons are level <?php echo $cannon_level; ?></strong>. Better cannons will give you more power in battles.</p>

			<h2><a <?php href('buy.php', '?u=sails'); ?>">Upgrade your Sails to level <?php echo $sail_level + 1; ?></a> (<?php echo $sail_price; ?> coins)</h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>Your current sails are level <?php echo $sail_level; ?>. </strong>Better sails will allow you to travel longer distances more quickly.</p>

			<h2><a <?php href('buy.php', '?u=hull'); ?>">Upgrade your Hull to level <?php echo $hull_level + 1; ?></a> (<?php echo $hull_price; ?> coins)</h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>Your current hull is level <?php echo $hull_level; ?>. </strong>A larger hull will allow you to hold more BOOTY!</p>

			<h2><a <?php href('buy.php', '?u=crew'); ?>">Buy a <?php echo ordinal_suffix($crew_level + 1); ?> crew member</a> (<?php echo $crew_price; ?> coins)</h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>You currently have <?php echo $crew_level; ?> crew members.</strong> Additional crew members will give you more power in battles, and allow you to sail for longer periods of time.</p>
		
		</div>
	
	</div>
	
	</center>


<?
	

?>

<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a <?php href('harbor.php'); ?>">Go to the  harbor</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a <?php href('index.php'); ?>">Go back to sea</a>  -adventure, danger and treasure await</h3>

<?php

require_once 'ad_bottom.inc.php';
require_once 'footer.php'; ?>