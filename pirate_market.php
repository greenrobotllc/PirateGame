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

print dashboard();

$my_level = get_level($user);

$booty = get_booty($user); 

$sextant_count = 0;
foreach($booty as $key=>$value) {
	$stuff_id = $value['stuff_id'];
	
	if($stuff_id == 13) {
		$sextant_count = $value['how_many'];
	}
}

?>


<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	


<center>
<h1>Welcome to the <?php echo ucwords($type); ?> Market!</h1>
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

<h2 style="text-align:center">What can I do for ya?</h2>

	<div style="background-image: url(<?php echo $base_url; ?>/images/scroll_background_vertical.jpg); height: 700px; width: <?php echo $image_width; ?>px;"> 
	
		<div style="position: relative; top: 100px; left: <?php echo $offset; ?>px; text-align:center">
		
			<br>			
			<h1 style="padding-bottom: 10px">You're holding <?php echo $coin_total; ?> coins</h1>
			
			<?php if ($buried_coin_total != 0): ?>	
				<h1 style="padding-bottom: 10px">You also have <?php echo $buried_coin_total; ?> buried coins.  <br><a <?php href('retrieve_coins.php'); ?>">Retrieve em</a> to buy stuff here.</h1>	
			<?php endif; ?>
	
		 	<br>

<?php

$level = get_level($user);

					
$level_upgraded = $memcache->get($user . 'upgraded_level');

if($level_upgraded == 1 && $level > 3000) {
?>
			<h2>Level upgrades</h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>You recently purchased a level upgrade and are now level <?php echo $my_level; ?></strong>.<br>Wait at least 10 minutes before trying to buy another level.</p>
			<?php
}

else {
?>


			<h2><a <?php href('level_upgrade.php'); ?>">Trade Booty for Level Upgrades</a></h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>Your current level is level <?php echo $my_level; ?></strong>.<br>Higher levels will earn you more hit points giving you a better chance of surviving an attack!</p>

<?php
} ?>
			<h2><a <?php href('buy.php', '?u=sextant'); ?>">Buy a Sextant</a> (150 coins)</h2>
			<p style="margin-top: 0px; padding-top: 0px; margin-left: auto; margin-right:auto; width:200px"><strong>Your have <?php echo $sextant_count; ?> sextants</strong>.<br>Sextants can be used by your ship to help navigate around storms!</p>

		<div style='position: relative; left: 10px; padding:10px; margin:10px;'>
		</div>
		</div>
	
	</div>


<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a <?php href('harbor.php'); ?>">Go to the  harbor</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a <?php href('index.php'); ?>">Go back to sea</a>  -adventure, danger and treasure await</h3>

</center>


<?php 

require_once 'ad_bottom.inc.php';
require_once 'footer.php'; ?>
