<?php

require_once 'includes.php';

global $DB;

update_action($user, "NULL");
//$DB->Execute("update users set user_was_attacked = '0' where id = ?", array($user));
//set_was_attacked($user, 0);
//set_was_bombed($user, 0);
//clear the in progress ship battles

$DB->Execute('update users set battling_enemy_id = 0 where id = ?', array($user));

//print_r($result);

reset_round($user);

$memcache->set($user . 'merchant_ship', false);
$memcache->set($user . 'msbh', false);

$result = $DB->Execute("delete from battles where user_id = ? and result='p'", array($user));

$facebook->redirect("$facebook_canvas_url/clear_action.php");


?>