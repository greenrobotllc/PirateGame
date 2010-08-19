<?php
require_once 'includes.php';

$userMiles = get_miles_traveled($user);
$milesMax = get_max_miles($user);
if($userMiles >= $milesMax) { 
	   update_action($user, "NULL");

       $facebook->redirect("explore.php");
}

$type= get_team($user);

$ship_image_blue = get_ship_image_and_weather($type, $user, true);

//$r = explore($user);
//echo "r; $r";



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

print dashboard();

$team = get_team($user);
$coins = get_coin_total($user);
$buried_coins = get_coin_total_buried($user);
$health=get_health($user);
$my_level=get_level($user);

?>



<?php 

$msg = "You see an enemy in the distance...  Wanna throw a bomb?";

?>
<center>
<h1>Avast!</h1>
</center>


<h2 style="text-align:center"><?php echo $msg; ?></h2><br>

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

<div id = 'harborcontainer' style='width:180px'>
<a style="color:#FFFFFF" <?php href('clear_action.php'); ?> ">
<span style="font-size:125%">Run away!<br>(arrr.... you chicken)</span>
</a>
</div>

</center>
</td>

<td width='30%' style="text-align:center">
	<?php image($ship_image_blue); ?>
</td>
<td width='30%'>
<center>
<div id='explorecontainer'  style='width:180px'>

<a style="color:#FFFFFF" <?php href('throw_bomb_which.php'); ?> ">
<span style="font-size:125%">throw a bomb!</span><br>
(level up, get gold)</a>
</div>
</center>
</td>
</tr>
</table>
<table width="90%" cellspacing="0" cellpadding="3">
<tr>
<td style="text-align:center;"><h5><?php echo get_pirate_tip(); ?></h5></td></tr>
</table>
<br>
<?php

require_once 'bottom_links.php';


?>


<?php require_once 'footer.php'; ?>