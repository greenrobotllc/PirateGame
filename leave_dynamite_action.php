<?php

require_once 'includes.php';
global $DB;



//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

$level = get_level($user);

//$how_many_bombs = get_how_many_bombs($user);

//print dashboard();

$friend_selector_id = $_REQUEST['friend_selector_id'];
$facebook->redirect("leave_dynamite_for.php?id=$friend_selector_id");
//print $friend_selector_id;

?>

