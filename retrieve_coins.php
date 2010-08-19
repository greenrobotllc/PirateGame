
<?php

require_once 'includes.php';

$type= get_team($user);
$your_coins = get_coin_total($user);
$buried_coins = get_coin_total_buried($user);

global $query_string, $network_id;


?>

<script>

function dig_up(e, f) {
	<?php 
	
	global $network_id;
	
	if($network_id == 0) {
	?>
		e.setDisabled(true); 
		e.setStyle("background", "gray"); 
		var dig_amount = document.getElementById('c').getValue();
	<?php 
	}
	else {
	?>
		e.disabled=true;
		e.style.background = 'gray';
		var dig_amount = document.getElementById('c').value;
	<?php
	
	}
	?>
	
	if(dig_amount != undefined) {
		<?php if($network_id == 0) { 
		$neworold = $_REQUEST['fb_sig_in_new_facebook'];
		echo $neworold;
		if($neworold == 1) {
		?>

		  document.setLocation("http://apps.new.facebook.com/pirates/retrieve_coins.php?c=" + dig_amount);
		
		<?php
		}
		else {
		?>
			document.setLocation("<?php echo $facebook_canvas_url; ?>/retrieve_coins.php?c=" + dig_amount);
		<?php
		
		}
		?>


		<?php 
		}
		
		else { ?>
		  document.location = "<?php echo $facebook_canvas_url; ?>/retrieve_coins.php?c=" + dig_amount;
		
		<?php
		}
		 ?>
	}

	return false;
	
}
	
</script>

<?php

print dashboard();


$how_many_to_dig = $_REQUEST['c'];


if(isset($how_many_to_dig)) {

	$how_many_to_dig = (int)$how_many_to_dig; //protect against xss

	
	if($how_many_to_dig < 0) {
		$how_many_to_dig = 0;
	}	
	else if($how_many_to_dig > $buried_coins) {
		$how_many_to_dig = $buried_coins;
	}

	$action = "retrieved $how_many_to_dig coins";
	log_coins($user, 0, $action);



	$msg = "Ahoy!<br>You dug up $how_many_to_dig coins.";

	$sql = 'update users set coin_total = coin_total + ?, buried_coin_total=buried_coin_total - ? where id = ?';

	$DB->Execute($sql, array($how_many_to_dig, $how_many_to_dig, $user));

}
else {
	$msg = " Arrr, you have $buried_coins coins buried. How many coins do ya need to dig up?";
}
?>

<center>

<?php echo success_msg($msg); ?>

<h3>You can buy stuff at the harbor with these coins!<br>BEWARE matey, If you go fightin' you might lose em'.</h3>

<?php

if(!isset($how_many_to_dig)) {
?>
<form name='myform' id='myform' method='post' action = 'retrieve_coins.php?<?php echo $query_string; ?>' style='padding:10px'>
<input id='c'  name = 'c' type='text' size=5 value='<?php echo $buried_coins; ?>' />
<input onclick = 'dig_up(this, this.parent); return false;' type='button' value = 'DIG' />

</form>

<?
}

?>

<table>
<tr>
<td>
  <?php image($pirate_coins_image); ?>
</td>
<td>
  <?php image($shovel_image); ?>
</td>
</tr>
</table>
<?php

if(isset($how_many_to_dig)) {
?>
<h2 style='padding:10px'><a <?php href('retrieve_coins.php'); ?>">Dig up some more coins</a></h2>
<?
}
print '<br>';
print adsense_468($user); 
?>

<h2 style='padding:10px'><a <?php href('harbor.php'); ?>">Arrr..... return to the harbor to be purchasin upgrades'</a></h2>

<br>
</center>

<?php require_once 'footer.php'; ?>