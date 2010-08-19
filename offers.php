
<?php
include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';
global $DB;
require_once 'header.php';

print dashboard();

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

?>

<fb:tabs>
	<fb:tab_item href="surveys.php" title="Surveys"/>
	<fb:tab_item href="offerpal.php" title="Offers"/>
	<fb:tab_item href="offers.php" title="More Offers" selected='true' />
</fb:tabs><br>



<center>
<table>
	<tr>
		<td valign="top"><center><h1>Welcome to Pirate Booty Bonus!</h1><br>We need money to improve the site, you help us get it.<br>We give you huge prizes!<br><br></center>
		</td>
	</tr>
</table>
</center>

<center>
<div padding='10px'>
When you fill out offers, we get money and give you prizes!  Getting your bonus coins is automatic but may take a few hours!
</div>

<center>
<div padding='10px'>
<?php

$offer_id = 2454;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=57246&subid=<?php echo $user; ?>'>Review airlines and get a gift certificate</a><br>(US Only)
<?
}

$offer_id = 2456;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=57291&subid=<?php echo $user; ?>'>Vote for your favorite detergent and get a gift card!</a><br>(US Only)
<?
}


$offer_id = 2451;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=57218&subid=<?php echo $user; ?>'>Home Depot Gift Card!</a><br>(US Only)
<?
}


$offer_id = 2453;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=57232&subid=<?php echo $user; ?>'>Southwest Airlines Gift Card!</a><br>(US Only)
<?
}






$offer_id = 2463;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=57426&subid=<?php echo $user; ?>'>Free case of Monster Energy Drink</a><br>(US Only)
<?
}





$offer_id = 394;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=11859&subid=<?php echo $user; ?>'>Download ZWINKIES</a><br>(US, UK, CAN, AUS, IRE, NZ Only)
<?
}




$offer_id = 1549;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=32763&subid=<?php echo $user; ?>'>Take a survey to get a $500 Ikea gift card!</a><br>(US Only)
<?
}



$offer_id = 2455;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=57265&subid=<?php echo $user; ?>'>Starbucks Gift Card</a><br>(US Only)
<?
}

$offer_id = 2442;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=56861&subid=<?php echo $user; ?>'>12 Free Case of Pepsi Max</a><br>(US Only)
<?
}



$offer_id = 1444;

if(false) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://hdbvinu.com/click/?s=8081&c=32846&subid=<?php echo $user; ?>'>Download the Webfetti Toolbar</a><br>(USA, CAN, AUS, NZ, IRE, UK Only)
<?
}




$offer_id = 38;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=11855&subid=<?php echo $user; ?>'>Download free cursors</a><br>(US, UK, CAN, AUS, IRE, NZ Only)
<?
}


$offer_id = 556;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8039&c=23234&subid=<?php echo $user; ?>'>Register at Fastweb for free scholarships</a><br>(US Only)
<?
}

$offer_id = 46;

if(user_eligible_for_offer($user, $offer_id)) { ?>
<center>
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=11866&subid=<?php echo $user; ?>'>Install Popular Screensavers</a><br>(US, UK, CAN, AUS, IRE, NZ Only)
<?
}





/*
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=27399&subid=<?php echo $user; ?>'>$250 Grocery Zipcard</a><br>(United States Only!)
*/

/*
<p style='font-size:125%; padding:20px; margin:20px; text-align:center'>
<strong>GET 250 PIRATE COINS!</strong> <a href='http://nbjmp.com/click/?s=8081&c=55061&subid=<?php echo $user; ?>'>Free Nestle Fun size candy bars</a><br>(United States Only!)
*
shitty offers dont use
*/
?>







</p>

<p style='font-size:125%'>You only get the coins if the offer company notifies us that you completed the offer.  Usually this is <strong>a few pages into the form</strong>.  Please use correct information if you want to participate in this!</p>

<p style='font-size:125%'>Depending on your location, some offers may redirect to different offers. <strong>You will still be credited the 250 coins for completing the different offer.</strong>  Please msg Andy with any questions.</p>

<p style='font-size:125%'>Pirate coins have no cash value.</p>

</center>
</div>
</center>
</center>




<br><br>
<center>
<br><br>

<?php 
//require_once 'ad_bottom.inc.php'; 
?>


<?php

require_once 'footer_nolinks.php'; ?>