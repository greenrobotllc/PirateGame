
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
	<fb:tab_item href="gambitoffers.php" title="Gambit Offers" selected='true' />
	<fb:tab_item href="surveys.php" title="Surveys" />
</fb:tabs>

<br>

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
When you fill out offers, we get money and give you pirate gold!</div>
</center>

<?php

?>
<center>
<fb:iframe src="http://getgambit.com/panel?k=658e0fd744559d5e38feafdb2f909879&uid=<?php echo $user; ?>" frameborder="0" width="630" height="1600"  scrolling="no" frameborder="no" allowtransparency="true"></fb:iframe>

</center>






<br><br>
<center>
<br><br>

<?php 
//require_once 'ad_bottom.inc.php'; 
?>


<?php

require_once 'footer.php'; ?>