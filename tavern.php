<?php

require_once 'includes.php';

$team_request = $_REQUEST['team'];
if(isset($team_request)) {
	$type = $team_request;
}
else {
	$type= get_team($user);
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

if($upgrade_name == 'wenches') {
	$cost = $wench_price;
}

print dashboard();

?>




<center>
<table>
	<tr>
		<td valign="top"><center><h1>Welcome to the Tavern!</h1></center><br>Arr! This is a rough place, so be watchin your step.<br>What do ye be wantin to do today?<br><br>
			<center><h3>You're holding <?php echo number_format($coin_total); ?> coins<br>
			<?php if($buried_coin_total > 0) { ?>
				You also have <?php echo number_format($buried_coin_total); ?> buried coins.<br><a <?php href('retrieve_coins.php'); ?>">Retrieve em</a> to buy stuff here.<br>
			<?php } ?>
			</h3><br>
			<?php if($network_id == 0) { ?>
			<!-- h2><a <?php href('poker.php'); ?>">Poker Room</a> - Play Poker without leaving Pirates</h2><br --> 
			<?php
			}
			?>
			<h2><a <?php href('wenches.php'); ?>">Tavern Brothel</a> - Hire wenches to relax your crew</h2>
			<h2><a <?php href('gambling.php'); ?>>Over/Under Gambling</a> - Put your skills to the test</h2>
			
			<?php if($network_id == 0) { ?>
			<h2><a <?php href('races.php'); ?>>Pet Races</a> - Bet on monkey and parrot races</h2>
			<?php
			}
			?>
			<br>
			<h2><a <?php href('buy.php', '?u=rum'); ?>>Jug of Rum (50 coins)</a> - for those long sea journeys</h2>
			<h2><a <?php href('buy.php', '?u=ham'); ?>>Salted Ham (50 coins)</a> - heal your crew with some food</h2>
			
			</center>
		</td>
		<td valign="top">
		
		<?php image($rum_175); ?>
		
		</td>
	</tr>
</table>


<?php
if($network_id == 0) {
//require_once 'sgn_client.php';

//$API_KEY= 'CHANGEME';
//$SGN_SECRET='dff5487d9b0da0bdf81b0bab9928b0f7';
//$SGN = new SGN_Client( $API_KEY, $SGN_SECRET, 'facebook' );

//echo "<fb:iframe " . $SGN->get_bar() . "/>"; // for fb:iframe

}

?>


</center>

<?php 
	print '<br>';

?>

<?php
if($network_id == 0) {

$moderators = get_moderators();
$banned = get_banned();

if (in_array($user, $moderators )) {
	$candelete = 'true';
}
else {
	$candelete = 'false';
}


if (in_array($user, $banned )) {
	$canpost = 'false';
}
else {
	$canpost = 'true';
}


?>

<fb:comments showform="true" xid="<?php echo $type; ?>_tavern" canpost="<?php echo $canpost; ?>" candelete="<?php echo $candelete; ?>" returnurl="<?php echo $facebook_canvas_url; ?>/tavern.php">

<?php

?>

   <fb:title><?php echo ucwords($type); ?> Tavern</fb:title>
 </fb:comments>
<br><br>
<?php
	}
	//userplane_468_60();

//require_once 'tavernboard.php';
?>
<br><br>
<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a <?php href('harbor.php'); ?>>Go to the  harbor</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a <?php href('index.php'); ?>>Go back to sea</a>  -adventure, danger and treasure await</h3>

<?php



require_once 'ad_bottom.inc.php';
require_once 'footer.php'; ?>

