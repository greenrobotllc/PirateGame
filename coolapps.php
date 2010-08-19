
<?php
include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';
global $DB;
require_once 'header.php';

$facebook_album_location = 9324337; //Brians ID

//icons
$addFavorites = 40815434;
$manageFavorites = 40815436;
$deleteFromFavorites = 40815435;
$nextImage = 40815437;
$browseIcon = 40815438;

//buttons
$saveToProfile = 40815432;
$nextButton = 40815439;
$previousButton = 40815440;
$helpKittensButton = 40820969;

//other apps
$lolcatsApp = 40816186;
$bikiniGirlsApp = 40816185;
$paradiseApp = 40816187;
$artApp = 40816184;




print dashboard();

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

?>

<fb:tabs>
	<fb:tab_item href="surveys.php" title="Surveys"/>
	<fb:tab_item href="offerpal.php" title="Offers"  />
	<fb:tab_item href="offers.php" title="More Offers" />
	<fb:tab_item href="coolapps.php" title="Cool Apps!"  selected='true' />
</fb:tabs><br>

<center>
<table>
	<tr>
		<td valign="top"><center><h1>Welcome to Pirate Booty Bonus!</h1><br>We think you should try these cool apps!  If you see an application you like, install it and we'll credit you <strong>100 Coins!</strong>!<br><br></center>
		</td>
	</tr>
</table>
</center>



<div style="padding: 10px;">
	<table cellpadding="10">
		<tr>
			<td>
				<a href="http://apps.facebook.com/artgallery/"><fb:photo pid="<?php echo $artApp; ?>" uid="<?php echo $facebook_album_location; ?>" /></a>
			</td>
			<td>
				<h2><a href="http://apps.facebook.com/artgallery/">Art</a></h2>Show people you've got some class by displaying pictures of fine art in your profile.<br><br>
				<a href="http://apps.facebook.com/artgallery/">Add Art</a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="http://apps.facebook.com/paradisepictures/"><fb:photo pid="<?php echo $paradiseApp; ?>" uid="<?php echo $facebook_album_location; ?>" /></a>
			</td>
			<td>
				<h2><a href="http://apps.facebook.com/paradisepictures/">Paradise</a></h2>Display exotic beaches, beautiful sunsets, and magnificent mountains in your profile.<br><br>
				<a href="http://apps.facebook.com/paradisepictures/">Add Paradise</a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="http://apps.facebook.com/bikinigirls/"><fb:photo pid="<?php echo $bikiniGirlsApp; ?>" uid="<?php echo $facebook_album_location; ?>" /></a>
			</td>
			<td>
				<h2><a href="http://apps.facebook.com/bikinigirls/">bikiniGirls</a></h2>Browse and display pictures of classic beauties in tasteful swimwear.<br><br>
				<a href="http://apps.facebook.com/bikinigirls/">Add bikiniGirls</a>
			</td>
		</tr>
		<tr>
			<td>
				<a href="http://apps.facebook.com/lolcats/"><fb:photo pid="<?php echo $lolcatsApp; ?>" uid="<?php echo $facebook_album_location; ?>" /></a>
			</td>
			<td>
				<h2><a href="http://apps.facebook.com/lolcats/">LOLcats</a></h2>Sort through tons of funny LOLcats, and add the best ones to your profile to share with friends.<br><br>
				<a href="http://apps.facebook.com/lolcats/">Add LOLcats</a>
			</td>
		</tr>
	</table>	
</div>
<br><br>




<br><br>
<center>
<br><br>

<p style='text-align:center'>Facebook Developers! Would you like your application listed here? <a href='coolapp_developers.php'>Try it out</a>
</p>



<?php

require_once 'footer_nolinks.php'; ?>