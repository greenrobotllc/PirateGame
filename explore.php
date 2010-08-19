
<?php
require_once 'includes.php';

global $DB;

redirect_if_action($user);

$type = get_team($user);

$r = explore($user);

if($r == "land") {
	$facebook->redirect("$facebook_canvas_url/found_land.php");
}
else if($r == "bottle") {
	$facebook->redirect("$facebook_canvas_url/index.php?msg=bottle");
}
else if($r == "parrot") {
	$facebook->redirect("$facebook_canvas_url/index.php?msg=parrot");
}
else if($r== "ship") {
	$facebook->redirect("$facebook_canvas_url/found_ship.php");
}
else if($r== "found_merchant_ship") {
	$facebook->redirect("$facebook_canvas_url/found_merchant_ship.php");
}
else if($r== "bomb") {
	$facebook->redirect("$facebook_canvas_url/throw_bomb.php");
}
else if($r== "seamonster") {
	$facebook->redirect("$facebook_canvas_url/found_disturbance.php");
}
if($_REQUEST['sent'] == "1") {
	$msg = "Yer Pirate invitations have been sent!";
}

if($_REQUEST['msg'] == "send-limit") {
	$msg = "Your requests were not sent.  You're over the limit for today.  Try again later. :(";
}

//allow bad things to happen since this is only explore page
$ship_image_blue = get_ship_image_and_weather($type, $user, false);
$dangerMsg = processes_weather_effects($user, $ship_image_blue);

$converted = false;
if($dangerMsg != "" and get_health($user) <= 0) {
	$enemy = random_enemy($type);
	$w = ucwords($enemy);
	update_team($user, $enemy);
	$dangerMsg = "Bad weather has destroyed your ship!<br>You are fished out by $w pirates and you become one of them.";
	$converted = true;
	$type = get_team($user);
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


print dashboard();

$team = $type;
$coins = get_coin_total($user);
$buried_coins = get_coin_total_buried($user);
$health=get_health($user);
$my_level=get_level($user);

?>

<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	


<?php if($dangerMsg) { ?>
<center>
<fb:error>
     <fb:message><?php echo $dangerMsg; ?></fb:message>
</fb:error>
</center>
<?php } else { ?>
<center>
<h1>You went sailing</h1>
</center>
<h2 style="text-align:center">ocean as far as the eye can see...</h2>
<?php } ?>

<br>
<center>
<table  width="90%" cellspacing="0" cellpadding="3">
	<tr>
		<td width='30%'><h5 style="text-align:left">Level <?php echo $my_level; echo " " . ucwords($team); ?> Pirate</h5></td>
		<td width='30%'><h5 style="text-align:center">Hit Points: <?php echo $health; ?></h5></td>
		<td width='30%'><h5 style="text-align:right">Coins: <?php echo number_format($coins); ?> Buried: <?php echo number_format($buried_coins); ?></h5></td>
	</tr>
</table>
<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: #FFFFFF; margin-top:0px; padding:10px" width="90%" border="0">
<tr>
<td width='30%'><center>
<?php
	$userMiles = get_miles_traveled($user);
	$milesMax = get_max_miles($user);

	if($userMiles >= $milesMax and $dangerMsg == "") { 

		print get_too_many_miles_msg($user);
	
	 } else { ?>
	<div id='harborcontainer' style='width:180px'>
	<a style='color:#FFFFFF' <?php href('harbor.php'); ?> >
	<span style="font-size:125%">&lt;- <?php echo $type; ?> harbor </span>

	<?php 
		echo "<br>$userMiles miles away<br>";
		if($ship_image_blue == $barbary_stormycloud or $ship_image_blue == $barbary_stormycloud or $ship_image_blue == $barbary_stormycloud)
		{
			echo "warning clouds sighted<br>return to harbor!";
		}
		else {
			echo "(get ship upgrades!)";
		}
	?>

	</a>
	</div>

	</center>
	</td>

	<td  width='30%' style="text-align:center">
		<?php image($ship_image_blue); ?>

	</td>
	<td width='30%'>
	<center>
	<br><br>
	<?php if($converted == false) { ?>
	<?php $ra = rand(1,4); ?>
	
	<?php if ($ra == 1): ?>
	<div id = 'explorecontainer' style='width:180px'>
	<a style="color:#FFFFFF" <?php href('explore.php'); ?> >
	<span style="font-size:125%">explore a little further -&gt;</span><br>
	(adventure, treasure, danger)</a>
	</div>

	<?php elseif($ra == 2): ?>
	<div id = 'explorecontainer' style='width:180px'>
	<a style="color:#FFFFFF" <?php href('explore.php'); ?> >
	<span style="font-size:125%">row, row, row yer boat -&gt;</span><br>
	(arrrrrrrr......)</a>
	</div>

	<?php elseif($ra == 3): ?>
	<div id = 'explorecontainer' style='width:180px'>
	<a  style="color:#FFFFFF" <?php href('explore.php', '?d=north'); ?> >
  <span style="font-size:125%">explore north</span>	</a>		
<br><br><br><br>
	<a style="color:#FFFFFF"  <?php href('explore.php', '?d=south'); ?>>
  <span style="font-size:125%">explore south</span>	</a>
  	</div>

	<?php elseif(true): ?>
	<div id='explorecontainer' style='width:180px'>
	<a  style="color:#FFFFFF"  <?php href('explore.php', '?d=north'); ?>>
  <span style="font-size:125%">keep exploring</span>	</a>
  	</div>
	<?php endif; ?>

<?php } } ?>

</center>
</td>
</tr>

</table>
<table width="90%" cellspacing="0" cellpadding="3">
<tr><td colspan='2' style="text-align:center;"><h5><?php echo get_pirate_tip(); ?></h5></td></tr>



</table><br>


<?php
if($userMiles >= $milesMax and $dangerMsg == "") {
?>

<fb:iframe src="CHANGEME/ads/?site=pirates&user=<?php echo $user; ?>'" style="border:0px;" width="626" height="80" scrolling="no" frameborder="0"/>

<?php
require_once 'footer_nolinks.php'; 

}
else {
require_once 'bottom_links.php';
require_once 'footer.php'; 

}

?>