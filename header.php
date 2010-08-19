
<?php 



include_once 'config.php';
include_once '../client/facebook.php';

global $base_url;
$user = $facebook->require_login();

$was_bombed = get_was_bombed($user);
 
if($was_bombed != "" && $was_bombed != 0) {
      $facebook->redirect("you_were_bombed.php");
}
$was_attacked = get_was_attacked($user);
 
if($was_attacked != "" && $was_attacked != 0) {
      $facebook->redirect("you_were_attacked.php");
}


?>

