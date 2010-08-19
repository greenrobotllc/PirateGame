<?php

require_once 'includes.php';

global $DB;

$type= get_team($user);

//$enemy = random_enemy($type);
$enemy = get_was_bombed($user);
//echo "enemy is: $enemy";
//don't belong here...
if($enemy == "" || $enemy == 0) {
	$facebook->redirect("clear_was_attacked.php");
}

//$r = explore($user);
//echo "r; $r";


//$ra = rand(1,100);

//$gold = rand(1, 100);

$your_coins = get_coin_total($user);
//$enemy= $user;
//print dashboard();
require_once 'style.php';

?>



<center>
<br><br>

<?php image($pirate_head_300px_image); ?>


<?php

//$result = "You won 25 gold and scared <fb:userlink uid='$enemy' away.<br><br>That'll teach em to attack you!";
//$result = "<br>You lost 30 gold in this battle.<br><br>Arrrr this be the life of a pirate....";

$enemy_coin_total = get_coin_total($enemy);

if($enemy_coin_total == '' || $enemy_coin_total < 1 || $enemy_coin_total  == false) {
	$enemy_coin_total = '??';
}




     
     $msg = "Avast! the Pirate <a href='$facebook_canvas_url/user_profile.php?user=$enemy'>";
     global $network_id;
     if($network_id == 0) {
     	$msg .= "<fb:name linked='false' uid='$enemy' ifcantsee='(facebook id: $enemy)' />";
     }
     else {
     	$msg .= get_name_for_id($enemy);
     }
     
     
     $msg .= '</a> (Level ' . get_level($enemy) . ') bombed you! They stole all the coins on yer ship!<br><br>';
      
      
     
     if($network_id == 0) {
    	$msg .= "<fb:name linked='false' firstnameonly='true' uid='$enemy' />";  
    
     }
     else {
     	$msg .= get_name_for_id($enemy);
     }
     
     $msg .= " has $enemy_coin_total coins onboard.";
     
    
     ?>
     <center>
     <?php //echo $result; ?>


<?php

//print adsense_468($user);

?>



<div class="standard_message has_padding">
<h1 class="status">Avast! the Pirate <a href="user_profile.php?user=<?php echo $enemy; ?>"><?php echo get_name_for_id($enemy); ?></a> (Level <?php echo get_level($enemy); ?>) bombed you!  They stole all the coins on yer ship!<br/><br/> 
     <?php echo get_first_name_for_id($enemy); ?> has <?php echo $enemy_coin_total; ?> coins onboard.  
          <center>
          </center>
<p>
     </p></h1></div>
     
     
<br>

<div style="padding-bottom:10px; padding-top:10px">



		<h1><a href="clear_was_attacked.php">Arrrr..... Keep on sailin</a></h1>
	</div>

</center>
<br>

<?php 

print adsense_468($user);


?>

<br>

<?php

//require_once "my_pirate.inc.php";


//require_once "world_stats.inc.php";
//set_profile($user);


?>

<?php 
require_once 'footer.php'; 

?>
