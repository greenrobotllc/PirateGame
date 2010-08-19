<?php

require_once 'includes.php';
redirect_to_index_if_not($user, "island");

$type= get_team($user);

$your_coins = get_coin_total($user);


$sql = 'update users set buried_coin_total = buried_coin_total + ?, coin_total=0 where id = ?';
	$DB->Execute($sql, array($your_coins, $user));


$action = "buried coins";
log_coins($user, 0, $action);


update_action($user, "NULL");

print dashboard();

?>

<center>



<?php
success_msg("Ahoy!<br>You buried $your_coins coins");

?>

<br>
<h4 style='padding:10px'>Burying your treasure prevents other pirates from stealing it.<br>Dig it up again if you want to spend it!</h4>
<br>
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
<h1><a <?php href('index.php'); ?> >Arrr..... return to sailin'</a></h1>
<br>
<?php print adsense_468($user); ?>

<br>
</center>

<?php require_once 'footer.php'; ?>