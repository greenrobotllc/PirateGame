<?php

require_once 'includes.php';

$type = get_team($user);

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


<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	

<center>
<table>
	<tr>
		<td valign="top"><center><h1>Tavern Brothel</h1></center><br>Every bilge rat needs to relax after hardships on the seas!<br>Help your crew recover from their wounds and miles sailed.<br><br>
			<center><h3>You're holding <?php echo number_format($coin_total); ?> coins<br>
			<?php if($buried_coin_total > 0) { ?>
				You also have <?php echo number_format($buried_coin_total); ?> buried coins.<br><a <?php href('retrieve_coins.php'); ?>">Retrieve em</a> to buy stuff here.<br>
			<?php } ?>
			<br>Since you have <?php echo $crew_level; ?> crew your cost will be:<br><?php echo $wench_price; ?> Coins</h3><br><br><h1><a <?php href('buy.php', '?u=wenches'); ?>">Hire Wenches (<?php echo $wench_price; ?> coins)</a></h1></center></td>
		<td valign="top">
		
		<?php image($hotwench); ?>
		</td>
	</tr>
</table>
</center>

<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a <?php href('tavern.php'); ?>">Back to Tavern</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a <?php href('index.php'); ?>">Go back to sea</a>  -adventure, danger and treasure await</h3>


<?php

//require_once "my_pirate.inc.php";

//require_once "world_stats.inc.php";
//set_profile($user);





?>


<?php 

require_once 'ad_bottom.inc.php';
?>
<br>
<?php
require_once 'footer.php'; ?>
