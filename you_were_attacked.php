<?php

require_once 'includes.php';

global $DB;
$type= get_team($user);

$enemy = get_was_attacked($user);

//echo $enemy;

$sql = 'select * from battles where user_id = ? and user_id_2 = ? order by id desc';

//todo switch this in real life
$battle = $DB->GetRow($sql, array($enemy, $user));
$result = $battle['result'];
$gold_change = $battle['gold_change'];

//result is recorded for user 1, and this user 2
if($result == 'l') {
	$msg = '  You defended yourself and stole some coins from them!!  Arrrrrrrr....';
}
else if($result == 'w') {
  $msg = '<br><br>Yer crew fired yer cannons back but they lost.<br><br>They stole all the coins on yer ship!<br><br>Yer health was healed by 50% after losing!';
}
else if($result == 'p') {
	$msg = '<br><br>Arrr... the battle is going on!  Yer crew are defending the ship for you right NOW!';
}
else  {
	$msg = '<br><br>Arrr... yer crew defended the ship and the pirate gave up the attack!';
}


//print_r($battle);

if($enemy == "" || $enemy == 0) {
	$facebook->redirect("index.php");
}


$your_coins = get_coin_total($user);

require_once 'style.php';
?>


<center>
<br><br>
<table><tr><td>
<?php image($pirate_head_300px_image); ?>
</td><td>

<?php
print adsense_200_200($user);

?>

</td></tr></table>

<?php
//$result = "You won 25 gold and scared <fb:userlink uid='$enemy' away.<br><br>That'll teach em to attack you!";
//$result = "<br>You lost 30 gold in this battle.<br><br>Arrrr this be the life of a pirate....";

$my_health=get_health($user);
$my_level=get_level($user);

//print "health $my_health level $my_level";
$my_health_percent = round(($my_health / $my_level) * 100);


//$enemy = 431885;

$enemy_health=get_health($enemy);
$enemy_level=get_level($enemy);

$ham_count = get_ham_count($user);


$enemy_health_percent = round(($enemy_health / $enemy_level) * 100);

?>


<div style='width:620px'>
<div class="standard_message has_padding">
<h1 class="status">

Avast! the <a href='user_profile.php?user=<?php echo $enemy; ?>'>Pirate <?php echo get_name_for_id($enemy); ?></a> (Level <?php echo get_level($enemy); ?>) attacked you!<?php echo $msg; ?><br>Yer health: <?php echo $my_health_percent; ?>%<br>Enemy health: <?php echo $enemy_health_percent; ?>%<br>
     <?php if($ham_count > 0) {
?>
<br>Heal yourself by <a href='clear_was_attacked.php?action=eatham'>eating ham</a> (<?php echo get_ham_count($user); ?> left)<br>
<?

}
else {
?>

<br>Heal yourself by <a href='clear_was_attacked.php?action=buyham'>buying some ham</a> (50 coins)<br>

<?php

}
?>
<br><a href="clear_was_attacked.php">Arrrr..... Keep on sailin</a>
     
     <p>
     
     </p><center>
          </center>
</h1></div>
</div>

<center>


<?php

?>
<br>

<div style="padding-bottom:10px; padding-top:10px">


	</div>

</center>

<?php


$d = $memcache->get($user . 'defender_pvp_attack_history');
if(!empty($d)) {
	echo "<center><h1>Battle History</h1></center>";
	echo $d;
}
?>
<br>

<?php print adsense_468($user); ?>

<br>

<?php require_once 'footer.php'; ?>
