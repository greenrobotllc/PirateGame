<?php /*
<center>

	<div style="border: 1px solid lightgrey; width: 590px; padding: 2px 0px 2px 0px; text-align: center;">
		<ul style="margin: 0pt; list-style-type: none; padding-left: 0px;">
		
		<li class="menu_hover"><a href="CHANGEME/tlapd">
		<img src="http://75.126.76.149/lolcats/asterisk_orange.png" 
height="10"> 
	<b>Today is Talk Like A Pirate Day! Win a free Pirate t-shirt!</b></a></li>
		</ul>
	</div>


</center>
*/ ?>

<?php
global $memcache;

$pvp_toggle = $memcache->get($user . 'pvp');
//print "pvp toggle $pvp_toggle";
if($pvp_toggle == 'off') {
?>

<center>

<div style='padding-bottom:5px; margin-bottom:5px'>
<div style="font-size:125%; margin:0px; padding:0px">
*Sailing safe waters! 
</div>

Sail into enemy waters if you want to fight pirates and pillage towns!


</div>


<?php

}
else {
?>
<center>

<div style='padding-bottom:5px; margin-bottom:5px'>
<div style="font-size:125%; margin:0px; padding:0px">
*Exploring enemy waters! 
</div>
You may be attacked by enemy pirates at random! Arrr!
</div>
</div>


<?php

}

?>
<center>

<span style='font-size:100%'>

<?php

$ham_count = get_ham_count($user);

?>

*<a <?php href('retrieve_coins.php'); ?>>Dig up</a> buried coins  &nbsp;&nbsp;&nbsp; <?php if($ham_count > 0) {
?>
*Heal yourself by <a href='item_action.php?item=ham'> eating ham</a> (<?php echo get_ham_count($user); ?> left)<br>
<?

}
else {
?>

*Heal yourself by <a href='buy.php?u=ham'>buying some ham</a> (50 coins)<br>

<?php

}
?>

*<a <?php href('recruit.php'); ?>">Level Up</a> by recruiting <?php echo ucwords($type); ?> Pirates.<br>
*<a <?php href('user_profile.php'); ?>>View your stats</a> to see how strong of a pirate you are.
</h2>
</span>

<br><br>
<?php
//print adsense_468($user);
?>
<br>
