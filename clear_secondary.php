<?php

require_once 'includes.php';
global $DB;

$action = get_current_action($user);
$secondary = get_secondary_action($user);

if($secondary == 'monster_attack_result') {
	update_secondary_action($user, 'NULL');
}

$facebook->redirect("$facebook_canvas_url/index.php");

?>