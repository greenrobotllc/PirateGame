<?php

require_once 'includes.php';
global $DB;

//update_action($user, "NULL");
//$DB->Execute("update users set user_was_attacked = '0' where id = ?", array($user));
set_was_attacked($user, 0);
set_was_bombed($user, 0);

update_action($user, "NULL");

$action = $_REQUEST['action'];

if($action == 'eatham') {
    $facebook->redirect("$facebook_canvas_url/item_action.php?item=ham");
}
else if($action == 'buyham') {
    $facebook->redirect("$facebook_canvas_url/buy.php?u=ham");
}
else {
    $facebook->redirect("$facebook_canvas_url/index.php");
}

?>