<?php

require_once 'includes.php';


$userMiles = get_miles_traveled($user);
$milesMax = get_max_miles($user);
if($userMiles >= $milesMax) { 
	   update_action($user, "NULL");
       $facebook->redirect("explore.php");
}
redirect_to_index_if_not($user, "found_merchant_ship");

$type= get_team($user);

//do not allow bad weather effects to happen
$ship_image_blue = get_ship_image_and_weather($type, $user, true);

//$r = explore($user);
//echo "r; $r";


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

$msg = "You spot a merchant ship in the distance...  What do ye want to do?";

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
<a style="color:#FFFFFF" href="clear_action.php">
<span style="font-size:125%">Sail away!</span>
</a>

</center>
</td>

<td  width='30%' style="text-align:center">
	<?php image($ship_image_blue); ?>  
</td>
<td width='30%'>
<center>
<a style="color:#FFFFFF" href="attack_ship_merchant.php">
<span style="font-size:125%">attack ship!</span></a>
<br><br><br>
<a style="color:#FFFFFF" href="trade_ship_merchant.php">
<span style="font-size:125%">trade with ship</span></a>
</center>
</td>
</tr>
</table>
<table width="90%" cellspacing="0" cellpadding="3">
<tr><td style="text-align:center;"><h5><?php echo get_pirate_tip(); ?></h5></td></tr>
</table>

<br>

<?php

require_once 'bottom_links.php';

?>


<?php require_once 'footer.php'; ?>