<?php
require_once 'includes.php';

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$houseItem = get_item_from_memcache($user);

print dashboard();
	
	$house1 = $_REQUEST['house1'];
	$house2 = $_REQUEST['house2'];
	$house3 = $_REQUEST['house3'];
	$house4 = $_REQUEST['house4'];
	$house5 = $_REQUEST['house5'];
	$house6 = $_REQUEST['house6'];
	$house7 = $_REQUEST['house7'];
	$house8 = $_REQUEST['house8'];
	$house9 = $_REQUEST['house9'];
	$house10 = $_REQUEST['house10'];
	$house11 = $_REQUEST['house11'];
	$house12 = $_REQUEST['house12'];
	$house13 = $_REQUEST['house13'];
	$house14 = $_REQUEST['house14'];
	$house15 = $_REQUEST['house15'];
	$stuff_id = $_REQUEST['stuffid']; 
	
	$house_total = $house1 + $house2 + $house3 + $house4 + $house5 + $house6 + $house7 + $house8 + $house9 + $house10 + $house11 + $house12 + $house13 + $house14 + $house15;
	
	if($stuff_id != "none") {
		$booty_array = get_booty_data_from_id($stuff_id);
		$booty_name = $booty_array[0];
		$booty_thumb_image = $booty_array[1];
		$booty_large_image = $booty_array[2];
	}

function process_result($houseResult) {
	if($houseResult == "nothing") {
		$ra = rand(1,5);
		if($ra == 1) {
			echo "Yar, didn't attack this house";
		}
		if($ra == 2) {
			echo "Your loyal dogs be skipping this one";
		}
		if($ra == 3) {
			echo "This house wasn't worth the effort";
		}
		if($ra == 4) {
			echo "No Captain attack order for this one";
		}
		if($ra == 5) {
			echo "Only landlubbers would attack this house";
		}
	}
	else if($houseResult == "item") {
		echo "Your crew found an item!";
	}
	else if($houseResult == "killed") {
		echo "<span style=\"color:red;\">The enemy captured and KILLED one of your crew!</span>";
	}
	else if($houseResult == "escapes") {
		echo "Your crew fail, but escape with their lives";
	}
	else if($houseResult > 0) {
		echo "Your crew pillaged $houseResult coins";
	}
	else {
		$lostAmount = $houseResult * -1;
		echo "Your crew lose and the enemy steals $lostAmount of your coins";
	}
}

function process_highlight($houseResult) {
	if($houseResult == "nothing") {
		echo "";
	}
	else if($houseResult == "item") {
		echo "style=\"background-color:#fff9d7;\"";
	}
	else if($houseResult == "killed") {
		echo "style=\"background-color:#fff9d7;\"";
	}
	else if($houseResult == "escapes") {
		echo "style=\"background-color:#fff9d7;\"";
	}
	else if($houseResult > 0) {
		echo "style=\"background-color:#fff9d7;\"";
	}
	else {
		echo "style=\"background-color:#fff9d7;\"";
	}
}

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

?>


<?php if($msg):?>
<?php success_msg($msg); ?>

<?php endif; ?>	

<center>


<table><tr><td valign='top'>
<h1><center>Pillaging Results</center></h1><br><br><br>
<h2>Ah, let's see how your scurvy scallywags have done!</h2><br>
<br>
<h3>You now have <?php echo $coin_total; ?> coins</h3>
<?php if($buried_coin_total > 0) { ?>
	<h3><?php echo $buried_coin_total; ?> coins are buried</h3>
<?php } ?><br>
<?php

?>

</td><td>
<?php
print adsense_200_200($user); 
?>
</td>

</tr>
<tr><td colspan = '3'>
<h2 style="text-align:center; padding-bottom: 10px"><a href="index.php">Back to Sailin</a></h2>
</td></tr>

<tr><td valign="top">
<table cellpadding="6" cellspacing="0" style="padding: 10px;border:solid #D8DFEA 1px;background-color:#ffffff;" border="0">
	<tr>
		<td><h2>House</h2></td>
		<td>
			<h2>
			Pillage Result
			</h2>
		</td>
	</tr>
	<?php if($house1 != "nohouse") { ?>
	<tr <?php process_highlight($house1); ?>>
		<td>House 1</td>
		<td>
			<?php
				process_result($house1);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house2 != "nohouse") { ?>
	<tr <?php process_highlight($house2); ?>>
		<td>House 2</td>
		<td>
			<?php
				process_result($house2);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house3 != "nohouse") { ?>
	<tr <?php process_highlight($house3); ?>>
		<td>House 3</td>
		<td>
			<?php
				process_result($house3);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house4 != "nohouse") { ?>
	<tr <?php process_highlight($house4); ?>>
		<td>House 4</td>
		<td>
			<?php
				process_result($house4);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house5 != "nohouse") { ?>
	<tr <?php process_highlight($house5); ?>>
		<td>House 5</td>
		<td>
			<?php
				process_result($house5);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house6 != "nohouse") { ?>
	<tr <?php process_highlight($house6); ?>>
		<td>House 6</td>
		<td>
			<?php
				process_result($house6);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house7 != "nohouse") { ?>
	<tr <?php process_highlight($house7); ?>>
		<td>House 7</td>
		<td>
			<?php
				process_result($house7);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house8 != "nohouse") { ?>
	<tr <?php process_highlight($house8); ?>>
		<td>House 8</td>
		<td>
			<?php
				process_result($house8);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house9 != "nohouse") { ?>
	<tr <?php process_highlight($house9); ?>>
		<td>House 9</td>
		<td>
			<?php
				process_result($house9);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house10 != "nohouse") { ?>
	<tr <?php process_highlight($house10); ?>>
		<td>House 10</td>
		<td>
			<?php
				process_result($house10);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house11 != "nohouse") { ?>
	<tr <?php process_highlight($house11); ?>>
		<td>House 11</td>
		<td>
			<?php
				process_result($house11);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house12 != "nohouse") { ?>
	<tr <?php process_highlight($house12); ?>>
		<td>House 12</td>
		<td>
			<?php
				process_result($house12);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house13 != "nohouse") { ?>
	<tr <?php process_highlight($house13); ?>>
		<td>House 13</td>
		<td>
			<?php
				process_result($house13);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house14 != "nohouse") { ?>
	<tr <?php process_highlight($house14); ?>>
		<td>House 14</td>
		<td>
			<?php
				process_result($house14);
			?>
		</td>
	</tr>
	<?php } ?>
	<?php if($house15 != "nohouse") { ?>
	<tr <?php process_highlight($house15); ?>>
		<td>House 15</td>
		<td>
			<?php
				process_result($house15);
			?>
		</td>
	</tr>

	
	<?php } ?>
	
	<tr>
		<td><strong>Total</strong></td>
		<td>
			<?php echo $house_total;  ?> coins pillaged.
		</td>
	</tr>
	
	</table>
</td><td>

<?php if($stuff_id != "none") { ?>
	<table cellpadding="6" cellspacing="0" style="padding: 10px;border:solid #D8DFEA 1px;background-color:#ffffff;" border="0">
	<tr>
	<td valign="top">
	<h2>You won an item!</h2><br>
	<center><h5><?php echo $booty_name; ?></h5><br>
	<?php image($booty_thumb_image); ?>
	</td>
	</tr>
	</table>
<?php } ?>


</td></tr>
</table>

<br>
<h2 style="text-align:center; padding-bottom: 10px"><a href="index.php">Back to Sailin</a></h2>


</center>	

<br><?
print adsense_468($user);

require_once 'footer.php'; ?>
