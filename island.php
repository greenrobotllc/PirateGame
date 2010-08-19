<?php
require_once 'includes.php';
global $island_image;

global $DB;
$type= get_team($user);

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}

$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

print dashboard();
?>

<center>

<?php 

$coin_total = get_coin_total($user);

$dynamite_total = get_dynamite_count($user);
$monkey_total = get_monkey_count($user);

$bomb_on = get_bomb_on($user);
	//echo $bomb_on;		
success_msg("Avast! You found a small deserted island...<br>Arrrr...What do ye want to do?");

?>



<table><tr><td>
<?php if($coin_total !=0): ?>
<h1 style="text-align:center; padding-bottom: 0px;margin-bottom:0px"><a <?php href('bury_coins_arrr.php'); ?> > Bury yer <?php echo $coin_total; ?> coins</a></h1>
<span style='text-align: center; padding-top:0px; margin-top:0px; padding-left:10px;padding-right:10px'>Buried treasure can't be stolen!</span>
<br><br>
<?php endif; ?>

<h1 style="text-align:center; padding-bottom: 0px; margin-bottom:0px"><a <?php href('island_booty.php'); ?> > Search for some BOOTY</a></h1>
<p style='text-align: center; padding-top:0px; margin-top:0px; padding-left:10px;padding-right:10px'>Look for booty on this island</p>


<?php if($network_id == 0) {

?>
<?php if($dynamite_total !=0 && $monkey_total !=0 && $bomb_on == 1): ?>
<h1 style="text-align:center; padding-bottom: 0px; margin-bottom:0px"><a <?php href('leave_dynamite_pick.php'); ?> >Leave Dynamite!</a></h1>
<p style='text-align: center; padding-top:0px; margin-top:0px; padding-left:10px;padding-right:10px'>Leave a dynamite/monkey booby trap</p>
<?php endif; ?>

<?php 
} 
?>

<h1 style="text-align:center; padding-bottom: 0px;margin-bottom:0px"><a <?php href('clear_action.php'); ?> >Leave Island</a></h1>
<p style='text-align: center; padding-top:0px; margin-top:0px; padding-left:10px;padding-right:10px'>Arrr....</p>
</td><td>

<?php 
$random_island_pic = rand(0,11);
$s = $island_image[$random_island_pic];
//echo "random island pic $random_island_pic";
//print_r($s);
image($s);

?>


</td></tr></table>

</center>	

<?php


?>

<br>
<br>
<br>
<center>
<div style='width:468px'>
</div>
</center>

<?php 
print adsense_468($user);

require_once 'footer_nolinks.php'; ?>
