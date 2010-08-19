<?php
//used for facebook only at the moment
include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';
//include_once 'fbexchange.php';

global $DB;

print dashboard_loggedout();
?>

	
	
	
	
<?php

//$team = $type;
//$coins = get_coin_total($user);
//$buried_coins = get_coin_total_buried($user);
//$health=get_health($user);
//$my_level=get_level($user);
//$real_health = $my_level - $health;
?>

<center>

<div style='width:93%'>

<fb:success>
<fb:message>
<font>Ahoy! Become a Pirate and sail the high seas of Facebook looking for treasure!</font>
</fb:message>

<font>But watch out for other Pirates who might try to steal your treasure or make you walk the plank! Shiver me timbers!<br><br>
Once you have some booty, trade it in for cool upgrades like bigger sails, stronger cannons, and more. Arrrr!<br>
</font>

</fb:fb:success>



</div>
</center>

<center>

<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: white; margin-top:0px; padding:10px;" width="90%" border="0">
<tr>
<td><center>
<?php
	$userMiles = get_miles_traveled($user);
?>
	<a style="color:white;" href="playnow.php">
	<span style="font-size:125%;">&lt;-- <?php echo $type; ?> harbor </span>
	<?php 
		echo "<br>$userMiles miles away<br>";
	?>
	(get ship upgrades!)

	</a>

	</center>
	</td>

	<td style="text-align:center;">
		<center>
<img src='<?php echo $base_url; ?>/images/ship_sun.jpg'>
		</center>
	</td>
	<td>
	<center>

	<a style="color:white;" href="playnow.php">
	<span style="font-size:125%;">explore the open sea --></span><br>
	(adventure, treasure, danger)</a>



</center>
</td>
</tr>
</table>

<table width="90%" cellspacing="0" cellpadding="3">
<tr>

<td style="text-align:center;" colspan='2'>

<h5><?php echo get_pirate_tip(); ?></h5>
</td></tr>
</table>
</center>
<br>


<?php
 //echo adsense_468();

 print "<center><fb:iframe id='ad_468' name='ad_468'
src='CHANGEME/ads/adsense_pirates_468_60.html'
framespacing='0' frameborder='no' scrolling='no' width='468' height='70'></fb:iframe></center>";


require_once 'footer_nolinks.php';
?>

