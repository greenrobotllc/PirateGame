<?
require_once 'lib.php';
global $DB;

echo '<center>';
echo "<h3 style='padding:10px'>ARrrr Pirate Clicks Sent Out: ";
echo $DB->GetOne("select count(*) from clicks_out");
echo "</h3>";



echo "<h3 style='padding:10px'>Last 100 clicks:";
echo "</h3>";
?>

<table cellspacing = 2 style='text-align: center; padding:10px; border:1px solid gray'><tr><th>Text</th><th>Facebook Id</th><th>Date</th></tr>
<?php 

$total = $DB->GetArray("select * from clicks_out order by created_at desc limit 100");
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
