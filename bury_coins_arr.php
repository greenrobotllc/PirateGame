<?php
//facebook only trap
include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;
require_once 'header.php';

redirect_to_index_if_not($user, "island");

$DB->Execute('insert into bot_log (user_id, created_at) values(?, now())', array($user));

$type= get_team($user);

//$gold = rand(1, 100);

$your_coins = get_coin_total($user);


$sql = 'update users set buried_coin_total = buried_coin_total + ?, coin_total=0 where id = ?';
	$DB->Execute($sql, array($your_coins, $user));

//require_once "my_pirate.inc.php";

//require_once "world_stats.inc.php";
//set_profile($user);

$action = "buried coins";
log_coins($user, 0, $action);


//echo "You found $booty!!!";

update_action($user, "NULL");

print dashboard();

?>

<center>


<fb:success>
     <fb:message>Ahoy!<br>You buried <?php echo $your_coins; ?> coins</fb:message>
</fb:success><br>
<h4 style='padding:10px'>Burying your treasure prevents other pirates from stealing it.<br>Dig it up again if you want to spend it!</h4>
<br>
<table>
<tr>
<td>
	<fb:photo pid='<?php echo $pirate_coins_image; ?>' uid="<?php echo $image_uid; ?>" />
  
</td>
<td>
	<fb:photo pid='<?php echo $shovel_image; ?>' uid="<?php echo $image_uid; ?>" />
  
</td>
</tr>
</table>
<h1><a href="index.php">Arrr..... return to sailin'</a></h1>
<br>
<?php print adsense_468($user); ?>

<br>
</center>

<?php require_once 'footer.php'; ?>