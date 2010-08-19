<?php
//header('Location:http://www.facebook.com/add.php?api_key=CHANGEME');

$api_key = 'CHANGEME';
$secret  = 'CHANGEME';

//DEVELOPMENT
//$api_key = '2cec64d22091dfc9ff0ccf9a0804057d';
//$secret = '3d0c0b17f4b12607de6f7a27f7a45755';


include_once '../client/facebook.php';

$facebook = new Facebook($api_key, $secret);

//$user = $facebook->require_add();
$facebook->redirect('http://www.facebook.com/add.php?api_key=CHANGEME');

?>
