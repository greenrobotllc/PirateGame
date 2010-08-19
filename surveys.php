
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
	<fb:tab_item href="gambitoffers.php" title="Gambit Offers"  />
	<fb:tab_item href="surveys.php" title="Surveys" selected='true' />
	<fb:tab_item target='_blank' href="http://charitygamegoods.com/choosecredits.php?gameid=5&charityid=8&userid=<?php echo $user; ?>" title="Buy Credits and Donate to Charity" />
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
When you fill out surveys, we get money and give you prizes.  For each survey you complete, we'll give you <b>500 gold coins!</b><br><br>
A few minutes of your time can help Pirates get custom graphics and fast servers!</div>
</center>

<?php
$publisher_id = 72;
$security_key ='fb742f77f89b78817bfe5c0ddb6dde13';

//echo "user $user<br>";
//echo "publisher_id $publisher_id<br>";
//echo "security_key $security_key<br>";

$user_go =  substr(md5($user . $publisher_id . $security_key), 0, 10);

//echo "user_go $user_go<br>";

$user_id = "$user-$publisher_id-$user_go";

//echo "user_id $user_id<br>";


//$url = 'http://peanutlabs.com/userGreeting.php?userId=1807687-63-260dc1befe';
$url = "http://peanutlabs.com/userGreeting.php?userId=$user_id";

?>
<center>
<fb:iframe src="<?php echo $url; ?>" style="height:800px; width: 600px; border: none;" height="800" width="600" scrolling="no" frameborder="no"></fb:iframe>
</center>






<br><br>
<center>
<br><br>

<?php 
//require_once 'ad_bottom.inc.php'; 
?>


<?php

require_once 'footer.php'; ?>