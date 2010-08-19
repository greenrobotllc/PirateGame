<?php

require_once 'includes.php';

$type= get_team($user);

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

redirect_to_index_if_not($user, "enemy_base");


function get_house_count_omg($user) {
	
	$upgrades = get_upgrades($user);

	foreach($upgrades as $key => $value) {
		$upgrade_name = $value['upgrade_name'];
		$level = $value['level'];
		if($upgrade_name == 'crew') {
			$crew_level = $level;
		}
	}
	
	if($crew_level <= 15) {
		return $crew_level;
	}
	else {
		return 15;
	}
}

$houseCount = get_house_count_omg($user);
if($houseCount == 0) {
	$facebook->redirect("$facebook_canvas_url/cant_pillage_yet.php");
}

$select_all = $_REQUEST['selectall'];
if(!isset($_REQUEST['selectall'])) {
	$select_all = true;
}

$houseArray = get_house_array($houseCount,$select_all);
$houseItem = assign_items_to_houses($houseCount);
set_item_to_memcache($user, $houseItem);

print dashboard();


$msg = $_REQUEST['msg'];

if($msg == 'selecterror') {
	$msg = "yarr, you have to select at least one house to attack!";
}

?>

<center>
<?php if($msg):
success_msg($msg);
?>
<?php endif; ?>	
</center>

<center>
<?php $crew_count = get_crew_count($user); 

if($crew_count < 1) {
    $facebook->redirect('shipyard.php');
}

?>
<h1>Enemy Island Map</h1><br>
<h2>Your spies have gotten ya a map of the enemy island! Yarrr!</h2><br>

<form action='process_pillaging_results.php' method='post'>

<div style="height: 495px; width: 590px;position:relative;">
<?php image($pillage_map); ?>

<div style="position:absolute;left:150px;top:20px;"><h3>Choose which houses you want to attack</h3><h5>Your crew will be evenly divided amoungst the houses you attack<br>The more crew per house, the better chance they have of winning</h5></div>

<?php 
	for($i = 0; $i < $houseCount; $i++) {
		echo $houseArray[$i];
	}
?>

<?php if($select_all == true) { ?>
	<div style="position:absolute;left:100px;top:440px;"><h2><a <?php href('pillage_town.php', '?selectall=0'); ?> ">unselect all houses</a></h2></div>
<?php } else { ?>
	<div style="position:absolute;left:100px;top:440px;"><h2><a<?php href('pillage_town.php', '?selectall=1'); ?>>select all houses</a></h2></div>
<?php } 

global $network_id;
if($network_id == 1) {

?>
<div style="position:absolute;left:140px;top:90px;">

<input type="submit" value="Attack!" />

</div>
<?php } ?>

<div style="position:absolute;left:270px;top:440px;">

<input type="submit" value="Attack!" />

</div>

</div>

<input type="hidden" name="houseCount" value="<?php echo $houseCount; ?>">

</form>
<br>

<?php print adsense_468($user); 

?>

<br>

<h2 style="text-align:center; padding-bottom: 10px"><a <?php href('clear_action.php'); ?> >Nevermind, too scared...maybe another time</a></h2>


</center>	

<?php

//require_once "my_pirate.inc.php";
//require_once "world_stats.inc.php";
//set_profile($user);


?>

<?php require_once 'footer.php'; ?>
