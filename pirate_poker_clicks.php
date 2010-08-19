<h1 style='text-align:center; padding:10px'>Pirate Clicks for Texas Holdem</h1>
<?
require_once 'lib.php';
global $DB;

echo '<center>';
echo "<h3 style='padding:10px'>Total Clicks Sent to Texas Hold em: ";
echo $DB->GetOne("select count(*) from clicks_out where offer_id = 1 or offer_id = 2");
echo "</h3>";



echo "<h3 style='padding:10px'>Last 100 clicks:";
echo "</h3>";
?>

<table cellspacing = 2 style='text-align: center; padding:10px; border:1px solid gray'><tr><th>Text</th><th>Facebook Id</th><th>Date</th></tr>
<?php 

$total = $DB->GetArray("select * from clicks_out where offer_id = 1 or offer_id = 2 order by created_at desc limit 100");
foreach($total as $key => $value) {
	$text=$value['offer_text'];
	$fb_id=$value['fb_id'];
	$created_at=$value['created_at'];
	
	print "<tr><td>$text</td><td>$fb_id</td><td>$created_at</td></tr>";

}
	//print_r($total);


?>

</table>

</center>